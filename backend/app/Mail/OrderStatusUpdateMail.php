<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $statusTitle;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param string $statusTitle
     */
    public function __construct(Order $order, string $statusTitle = null)
    {
        $this->order = $order;
        $this->statusTitle = $statusTitle ?: ($order->shipment_status ?: $order->status);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '📦 Order Update: #' . $this->order->id . ' is now [' . strtoupper($this->statusTitle) . ']';
        return $this->subject($subject)
                    ->view('emails.order_status_update');
    }
}
