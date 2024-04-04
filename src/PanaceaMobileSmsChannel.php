<?php

namespace Emotality\Panacea;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

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
    public function send($notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('panacea', $notification);
        $from = Config::get('panacea.from');

        if (method_exists($notification, 'toPanacea')) {
            $message = $notification->toPanacea($notifiable);
        } elseif (method_exists($notification, 'toSms')) {
            $message = $notification->toSms($notifiable);
        } elseif (method_exists($notification, 'sms')) {
            $message = $notification->sms($notifiable);
        } else {
            throw new PanaceaException('Appropriate method to format the SMS message not found in Notification.');
        }

        if ($message->isToEmpty() && ! is_null($to)) {
            $message->to($to);
        }

        if ($message->isFromNull() && ! is_null($from)) {
            $message->from($from);
        }

        $message->send();
    }
}