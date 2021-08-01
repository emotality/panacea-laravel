<?php

namespace Emotality\Panacea;

use Illuminate\Notifications\Notification;

class PanaceaChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toPanacea')) {
            $notification->toPanacea($notifiable)->send();
        } else if (method_exists($notification, 'toSms')) {
            $notification->toSms($notifiable)->send();
        } else {
            throw new \Exception('Method not found in Notification to send SMS.');
        }
    }
}