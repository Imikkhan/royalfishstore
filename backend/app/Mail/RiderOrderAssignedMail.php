<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Rider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RiderOrderAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $rider;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param Rider $rider
     */
    public function __construct(Order $order, Rider $rider)
    {
        $this->order = $order;
        $this->rider = $rider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🛵 Delivery Task Assigned: Order #' . $this->order->id)
                    ->view('emails.rider_order_assigned');
    }
}
