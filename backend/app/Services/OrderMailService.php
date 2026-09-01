<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Rider;
use App\Models\User;
use App\Models\Setting;
use App\Mail\AdminNewOrderMail;
use App\Mail\RiderOrderAssignedMail;
use App\Mail\OrderStatusUpdateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderMailService
{
    /**
     * Run mail sending asynchronously/deferred using Laravel 11 native defer helper
     * so HTTP response completes first without blocking on SMTP.
     */
    private static function defer(callable $callback)
    {
        if (function_exists('Illuminate\Support\defer')) {
            \Illuminate\Support\defer(function () use ($callback) {
                try {
                    $callback();
                } catch (\Throwable $e) {
                    Log::error('Deferred OrderMailService error: ' . $e->getMessage());
                }
            });
        } else {
            try {
                $callback();
            } catch (\Throwable $e) {
                Log::error('OrderMailService error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send email notification to Admin when a new order is placed.
     *
     * @param Order $order
     * @return void
     */
    public static function sendAdminNewOrderMail(Order $order)
    {
        self::defer(function () use ($order) {
            try {
                // Find admin email address
                $adminEmail = env('ADMIN_EMAIL');
                if (empty($adminEmail)) {
                    $adminEmail = Setting::where('key', 'admin_email')->value('value');
                }
                if (empty($adminEmail)) {
                    $adminEmail = Setting::where('key', 'site_email')->value('value');
                }
                if (empty($adminEmail)) {
                    $adminEmail = config('mail.from.address', 'royalfishstores@gmail.com');
                }

                if (!empty($adminEmail)) {
                    Mail::to($adminEmail)->send(new AdminNewOrderMail($order));
                }
            } catch (\Throwable $e) {
                Log::error('Failed sending AdminNewOrderMail for order ' . $order->id . ': ' . $e->getMessage());
            }
        });
    }

    /**
     * Send email notification to Rider when assigned to an order.
     *
     * @param Order $order
     * @param Rider $rider
     * @return void
     */
    public static function sendRiderAssignedMail(Order $order, Rider $rider)
    {
        self::defer(function () use ($order, $rider) {
            try {
                if ($rider && !empty($rider->email)) {
                    Mail::to($rider->email)->send(new RiderOrderAssignedMail($order, $rider));
                }
            } catch (\Throwable $e) {
                Log::error('Failed sending RiderOrderAssignedMail for order ' . $order->id . ' to rider ' . ($rider->email ?? 'N/A') . ': ' . $e->getMessage());
            }
        });
    }

    /**
     * Send email notification to User (Customer) on order status update.
     *
     * @param Order $order
     * @param string|null $statusTitle
     * @return void
     */
    public static function sendCustomerStatusMail(Order $order, string $statusTitle = null)
    {
        self::defer(function () use ($order, $statusTitle) {
            try {
                $order->loadMissing('items', 'user');
                $address = $order->address_data ?? [];

                $customerEmail = null;
                if ($order->user && !empty($order->user->email) && strpos($order->user->email, '@royalfishstore.com') === false) {
                    $customerEmail = $order->user->email;
                } elseif (!empty($address['email'])) {
                    $customerEmail = $address['email'];
                } elseif (!empty($address['user_email'])) {
                    $customerEmail = $address['user_email'];
                } elseif ($order->user && !empty($order->user->email)) {
                    $customerEmail = $order->user->email;
                }

                if (!empty($customerEmail)) {
                    Mail::to($customerEmail)->send(new OrderStatusUpdateMail($order, $statusTitle));
                }
            } catch (\Throwable $e) {
                Log::error('Failed sending OrderStatusUpdateMail for order ' . $order->id . ': ' . $e->getMessage());
            }
        });
    }
}
