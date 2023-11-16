<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $booking_id;
    protected $request_id;
    protected $req_user_id;


    public function __construct($booking_id,$request_id,$req_user_id)
    {
        $this->booking_id = $booking_id;
        $this->request_id = $request_id;
        $this->req_user_id = $req_user_id;
    }

    public function via( $notifiable): array
    {
        return ['database'];
    }

    public function toArray( $notifiable): array
    {
        return [
            'booking_id'    => $this->booking_id,
            'request_id'    => $this->request_id,
            'req_user_id'    => $this->req_user_id,

        ];
    }
}
