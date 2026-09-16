<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Rider;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class OrderWhatsAppService
{
    /**
     * Run WhatsApp notifications safely.
     * Synchronous execution with Meta Cloud API takes ~400ms and guarantees
     * delivery on cPanel/FastCGI where deferred tasks get prematurely terminated.
     */
    private static function defer(callable $callback)
    {
        try {
            $callback();
        } catch (\Throwable $e) {
            Log::error('OrderWhatsAppService error: ' . $e->getMessage());
        }
    }

    /**
     * Get the service instance.
     */
    private static function getService(): WhatsAppService
    {
        return app(WhatsAppService::class);
    }

    /**
     * Get configured Admin WhatsApp Number.
     */
    public static function getAdminPhone(): ?string
    {
        $adminPhone = Setting::where('key', 'admin_whatsapp_phone')->value('value');
        if (empty($adminPhone)) {
            $adminPhone = env('ADMIN_WHATSAPP_PHONE');
        }
        if (empty($adminPhone)) {
            $adminPhone = Setting::where('key', 'website_phone')->value('value');
        }
        if (empty($adminPhone)) {
            // Check facebook ad hero whatsapp
            $hero = Setting::where('key', 'onepager_hero')->value('value');
            if ($hero) {
                $hData = json_decode($hero, true);
                if (!empty($hData['whatsapp_number'])) {
                    $adminPhone = $hData['whatsapp_number'];
                }
            }
        }

        // Do not return dummy demo numbers
        if (!empty($adminPhone) && (str_contains($adminPhone, '98765 43210') || str_contains($adminPhone, '9876543210'))) {
            return null;
        }

        return $adminPhone;
    }

    /**
     * Check if WhatsApp notifications are enabled in settings.
     */
    public static function isEnabled(string $key = 'notify_admin_whatsapp'): bool
    {
        $val = Setting::where('key', $key)->value('value');
        if ($val === null) return true; // Default enabled
        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Format a compact string of items in an order.
     */
    private static function formatItemsSummary(Order $order): string
    {
        $lines = [];
        if ($order->items && $order->items->isNotEmpty()) {
            foreach ($order->items as $item) {
                $name = $item->product_name ?: ($item->product ? $item->product->name : 'Fish Item');
                $lines[] = "{$item->quantity}x {$name}";
            }
        }
        return !empty($lines) ? implode(', ', $lines) : 'Seafood items';
    }

    /**
     * Format address into a single readable line.
     */
    private static function formatAddress(Order $order): string
    {
        $addr = $order->address_data;
        if (is_string($addr)) {
            $addr = json_decode($addr, true);
        }
        if (is_array($addr)) {
            $parts = array_filter([
                $addr['houseNo'] ?? ($addr['house_no'] ?? ''),
                $addr['address'] ?? ($addr['addressLine'] ?? ($addr['street'] ?? '')),
                $addr['area'] ?? ($addr['landmark'] ?? ''),
                $addr['city'] ?? 'Kolkata',
                $addr['pincode'] ?? ($addr['zipCode'] ?? ($addr['pin'] ?? ''))
            ]);
            return !empty($parts) ? implode(', ', $parts) : 'Customer Address';
        }
        return (string)($addr ?: 'Customer Address');
    }

    /**
     * Extract customer name.
     */
    private static function getCustomerName(Order $order): string
    {
        $order->loadMissing('user');
        $addr = $order->address_data;
        if (is_string($addr)) {
            $addr = json_decode($addr, true);
        }

        $name = null;
        if (is_array($addr) && !empty($addr['name']) && $addr['name'] !== 'Home (Default)') {
            $name = $addr['name'];
        }
        if (empty($name) && $order->user && !empty($order->user->name)) {
            $name = $order->user->name;
        }
        return $name ?: 'Valued Customer';
    }

    /**
     * Extract customer phone.
     */
    private static function getCustomerPhone(Order $order): ?string
    {
        $order->loadMissing('user');

        // 1. If customer has a registered account phone, prioritize it if it's real
        if ($order->user && !empty($order->user->phone)) {
            $uPhone = trim($order->user->phone);
            if (!str_contains($uPhone, '98765 43210') && !str_contains($uPhone, '9876543210')) {
                return $uPhone;
            }
        }

        // 2. Check address_data phone
        $addr = $order->address_data;
        if (is_string($addr)) {
            $addr = json_decode($addr, true);
        }
        if (is_array($addr) && !empty($addr['phone'])) {
            $addrPhone = trim($addr['phone']);
            if (!str_contains($addrPhone, '98765 43210') && !str_contains($addrPhone, '9876543210')) {
                return $addrPhone;
            }
        }

        // 3. Fallback to whatever is available if not dummy
        $fallback = ($order->user && !empty($order->user->phone)) 
            ? $order->user->phone 
            : ($addr['phone'] ?? null);

        if ($fallback && (str_contains($fallback, '98765 43210') || str_contains($fallback, '9876543210'))) {
            return null;
        }

        return $fallback;
    }

    /**
     * 1. Send WhatsApp notification to ADMIN on new order.
     *
     * @param Order $order
     * @return void
     */
    public static function sendAdminNewOrderNotification(Order $order): void
    {
        self::defer(function () use ($order) {
            try {
                if (!self::isEnabled('notify_admin_whatsapp')) {
                    Log::info("Admin WhatsApp notification disabled in settings for Order {$order->id}");
                    return;
                }

                $adminPhone = self::getAdminPhone();
                if (empty($adminPhone)) {
                    Log::warning("No admin WhatsApp phone configured for Order {$order->id}");
                    return;
                }

                $order->loadMissing('items', 'user');
                $customerName = self::getCustomerName($order);
                $customerPhone = self::getCustomerPhone($order) ?: 'N/A';
                $addressStr = self::formatAddress($order);
                $itemsSummary = self::formatItemsSummary($order);
                $totalFormatted = '₹' . number_format($order->total_price, 2);
                $payMethod = strtoupper($order->payment_method ?: 'COD');
                $slot = $order->estimated_delivery ?: '30-45 mins';

                // Rich formatted text for Admin
                $message = "🚨 *NEW ORDER RECEIVED!* 🐟\n\n"
                    . "*Order ID:* #{$order->id}\n"
                    . "*Customer:* {$customerName}\n"
                    . "*Phone:* {$customerPhone}\n"
                    . "*Address:* {$addressStr}\n\n"
                    . "*Items:* {$itemsSummary}\n"
                    . "*Total Amount:* {$totalFormatted} ({$payMethod})\n"
                    . "*Delivery Slot:* {$slot}\n\n"
                    . "👉 Please open Admin Panel to process and dispatch this order.";

                // Template parameters if Codebey template is approved
                $templateParams = [
                    $customerName,
                    (string)$order->id,
                    $itemsSummary,
                    $totalFormatted,
                    $payMethod,
                    $addressStr
                ];

                $service = self::getService();
                $res = $service->sendMessage(
                    $adminPhone,
                    $message,
                    'order_confirmation',
                    $templateParams,
                    '1904807637591601'
                );

                Log::info("Admin WhatsApp Order Notification sent to {$adminPhone} for Order {$order->id}", ['result' => $res]);
            } catch (\Throwable $e) {
                Log::error("Failed sending Admin WhatsApp Notification for Order {$order->id}: " . $e->getMessage());
            }
        });
    }

    /**
     * 2. Send WhatsApp confirmation to CUSTOMER on order success.
     *
     * @param Order $order
     * @return void
     */
    public static function sendCustomerOrderSuccessNotification(Order $order): void
    {
        self::defer(function () use ($order) {
            try {
                if (!self::isEnabled('notify_customer_order_placed')) {
                    Log::info("Customer order placed WhatsApp notification disabled in settings for Order {$order->id}");
                    return;
                }

                $order->loadMissing('items', 'user');
                $customerPhone = self::getCustomerPhone($order);
                if (empty($customerPhone)) {
                    Log::warning("No customer phone found for Order {$order->id}");
                    return;
                }

                $customerName = self::getCustomerName($order);
                $addressStr = self::formatAddress($order);
                $itemsSummary = self::formatItemsSummary($order);
                $totalFormatted = '₹' . number_format($order->total_price, 2);
                $payMethod = strtoupper($order->payment_method ?: 'COD');
                $slot = $order->estimated_delivery ?: '30-45 mins';

                // Direct message formatting
                $message = "🎉 *Order Placed Successfully!*\n\n"
                    . "Dear *{$customerName}*, thank you for ordering with *Royal Fish Store*! 🐟\n\n"
                    . "📋 *Order ID:* #{$order->id}\n"
                    . "🛍️ *Items:* {$itemsSummary}\n"
                    . "💰 *Total Amount:* {$totalFormatted}\n"
                    . "💳 *Payment Mode:* {$payMethod}\n"
                    . "📍 *Delivery Address:* {$addressStr}\n"
                    . "⏱️ *Estimated Delivery:* {$slot}\n\n"
                    . "We are packing your fresh cut seafood with hygienic cold-chain packaging. For queries, reply to this message.";

                $templateParams = [
                    $customerName,
                    (string)$order->id,
                    $itemsSummary,
                    $totalFormatted,
                    $payMethod,
                    $addressStr
                ];

                $service = self::getService();
                $res = $service->sendMessage(
                    $customerPhone,
                    $message,
                    'order_confirmation',
                    $templateParams,
                    '1904807637591601'
                );

                Log::info("Customer WhatsApp Order Placed Notification sent to {$customerPhone} for Order {$order->id}", ['result' => $res]);
            } catch (\Throwable $e) {
                Log::error("Failed sending Customer WhatsApp Order Placed Notification for Order {$order->id}: " . $e->getMessage());
            }
        });
    }

    /**
     * 3. Send WhatsApp notification to CUSTOMER on status change.
     *
     * @param Order $order
     * @param string|null $status
     * @return void
     */
    public static function sendCustomerOrderStatusNotification(Order $order, ?string $status = null): void
    {
        self::defer(function () use ($order, $status) {
            try {
                if (!self::isEnabled('notify_customer_status_change')) {
                    return;
                }

                $order->loadMissing('items', 'user', 'rider');
                $customerPhone = self::getCustomerPhone($order);
                if (empty($customerPhone)) {
                    return;
                }

                $customerName = self::getCustomerName($order);
                $currentStatus = $status ?: ($order->shipment_status ?: $order->status);
                $addressStr = self::formatAddress($order);
                $slot = $order->estimated_delivery ?: '30-45 mins';

                $riderInfo = "";
                if ($order->rider) {
                    $riderInfo = "\n🛵 *Delivery Partner:* {$order->rider->name} ({$order->rider->phone})";
                }

                $message = "📦 *Order Status Update*\n\n"
                    . "Dear *{$customerName}*, your Royal Fish Store order *#{$order->id}* status has been updated to:\n"
                    . "👉 *{$currentStatus}* 👈\n"
                    . $riderInfo . "\n"
                    . "⏱️ *Estimated Delivery:* {$slot}\n"
                    . "📍 *Delivery Address:* {$addressStr}\n\n"
                    . "Thank you for choosing Royal Fish Store! 🐟";

                $templateParams = [
                    $customerName,
                    (string)$order->id,
                    $currentStatus,
                    $slot,
                    $addressStr
                ];

                $service = self::getService();
                $res = $service->sendMessage(
                    $customerPhone,
                    $message,
                    'order_status_update',
                    $templateParams,
                    '28074372922185765'
                );

                Log::info("Customer WhatsApp Status Notification sent to {$customerPhone} for Order {$order->id} (Status: {$currentStatus})", ['result' => $res]);
            } catch (\Throwable $e) {
                Log::error("Failed sending Customer WhatsApp Status Notification for Order {$order->id}: " . $e->getMessage());
            }
        });
    }

    /**
     * 4. Send WhatsApp notification to DELIVERY BOY (RIDER) when assigned.
     *
     * @param Order $order
     * @param Rider $rider
     * @return void
     */
    public static function sendRiderAssignedNotification(Order $order, Rider $rider): void
    {
        self::defer(function () use ($order, $rider) {
            try {
                if (!self::isEnabled('notify_rider_assignment')) {
                    return;
                }

                if (empty($rider->phone)) {
                    Log::warning("Rider {$rider->id} ({$rider->name}) has no phone number for WhatsApp.");
                    return;
                }

                $order->loadMissing('items', 'user');
                $customerName = self::getCustomerName($order);
                $customerPhone = self::getCustomerPhone($order) ?: 'N/A';
                $addressStr = self::formatAddress($order);
                $itemsSummary = self::formatItemsSummary($order);
                $totalFormatted = '₹' . number_format($order->total_price, 2);
                $payMethod = strtoupper($order->payment_method ?: 'COD');
                $amountToCollect = ($payMethod === 'COD' || str_contains($payMethod, 'CASH'))
                    ? "{$totalFormatted} (Cash on Delivery)"
                    : "₹0 (Already Paid Online)";

                $message = "🛵 *NEW DELIVERY ASSIGNMENT!* 📦\n\n"
                    . "Hello *{$rider->name}*, a new order *#{$order->id}* has been assigned to you for delivery!\n\n"
                    . "👤 *Customer:* {$customerName}\n"
                    . "📞 *Phone:* {$customerPhone}\n"
                    . "📍 *Delivery Address:* {$addressStr}\n"
                    . "💵 *Amount to Collect:* {$amountToCollect}\n"
                    . "🛍️ *Items:* {$itemsSummary}\n\n"
                    . "⚡ Please open your Rider App to start delivery and navigate to location.";

                $templateParams = [
                    $rider->name,
                    (string)$order->id,
                    $customerName,
                    $customerPhone,
                    $addressStr,
                    $amountToCollect,
                    $itemsSummary
                ];

                $service = self::getService();
                $res = $service->sendMessage(
                    $rider->phone,
                    $message,
                    'rider_order_assigned',
                    $templateParams,
                    '1061770773101675'
                );

                Log::info("Rider WhatsApp Assignment Notification sent to {$rider->phone} for Order {$order->id}", ['result' => $res]);
            } catch (\Throwable $e) {
                Log::error("Failed sending Rider WhatsApp Assignment Notification for Order {$order->id}: " . $e->getMessage());
            }
        });
    }
}

