<?php

namespace Emotality\Panacea;

class PanaceaMobileFacade
{
    /**
     * PanaceaMobile API class.
     *
     * @return \Emotality\Panacea\PanaceaMobileAPI
     */
    private static function api()
    {
        return app(PanaceaMobileAPI::class);
    }

    /**
     * Send SMS to a single recipient.
     *
     * @param  string  $recipient
     * @param  string  $message
     * @param  string|null  $from
     * @return bool
     * @throws \Emotality\Panacea\PanaceaException
     */
    public static function sms(string $recipient, string $message, string $from = null) : bool
    {
        return self::api()->sendSms($recipient, $message, $from);
    }

    /**
     * Send SMS to multiple recipients.
     *
     * @param  array  $recipients
     * @param  string  $message
     * @param  string|null  $from
     * @return array<string, bool>
     * @throws \Emotality\Panacea\PanaceaException
     */
    public static function smsMany(array $recipients, string $message, string $from = null) : array
    {
        $response = [];

        foreach (array_unique($recipients) as $recipient) {
            $response[$recipient] = self::api()->sendSms(strval($recipient), $message, $from);
        }

        return $response;
    }
}
