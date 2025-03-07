<?php

namespace Emotality\Panacea;

use Illuminate\Support\Facades\App;

class PanaceaMobileFacade
{
    /**
     * PanaceaMobile API class.
     */
    private static function api(): PanaceaMobileAPI
    {
        return App::get(PanaceaMobileAPI::class);
    }

    /**
     * Send SMS to a single recipient.
     *
     * @throws \Emotality\Panacea\PanaceaException
     */
    public static function sms(string $recipient, string $message, ?string $from = null): bool
    {
        return self::api()->sendSms($recipient, $message, $from);
    }

    /**
     * Send SMS to multiple recipients.
     *
     * @return array<string, bool>
     * @throws \Emotality\Panacea\PanaceaException
     */
    public static function smsMany(array $recipients, string $message, ?string $from = null): array
    {
        $response = [];

        foreach (array_unique($recipients) as $recipient) {
            $response[$recipient] = self::api()->sendSms(strval($recipient), $message, $from);
        }

        return $response;
    }
}
