<?php

namespace Emotality\Panacea;

use Illuminate\Notifications\Notification;

class PanaceaMobileSmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     * @throws \Emotality\Panacea\PanaceaException
     */
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toPanacea')) {
            $notification->toPanacea($notifiable)->send();
        } elseif (method_exists($notification, 'toSms')) {
            $notification->toSms($notifiable)->send();
        } elseif (method_exists($notification, 'sms')) {
            $notification->sms($notifiable)->send();
        } else {
            throw new PanaceaException('toSms() function not found in Notification to send SMS.');
        }
    }
}