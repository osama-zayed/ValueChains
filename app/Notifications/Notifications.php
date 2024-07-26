<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class Notifications extends Notification
{
    use Queueable;
    protected $details;
    public function __construct($details)
    {
        $this->details = $details;
    }
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    public function toDatabase($notifiable)
    {
        return [
            "data" => $this->details["body"],
        ];
    }
}
