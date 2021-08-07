<?php

namespace Emotality\Panacea;

use GuzzleHttp\Client as Http;

class PanaceaMobileAPI
{
    /**
     * Guzzle HTTP client.
     *
     * @var \GuzzleHttp\Client $client
     */
    protected $client;

    /**
     * PanaceaMobile base API URL.
     *
     * @var string
     */
    protected $url = 'https://api.panaceamobile.com';

    /**
     * PanaceaMobile login username.
     *
     * @var string $username
     */
    protected $username;

    /**
     * PanaceaMobile login password.
     *
     * @var string $password
     */
    protected $password;

    /**
     * PanaceaMobile constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Http([
            'base_uri' => $this->url,
            'timeout'  => 15.0,
            'headers'  => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'verify'   => config('panacea.ssl', true),
        ]);

        $this->username = config('panacea.username');
        $this->password = config('panacea.password');
    }

    /**
     * Send SMS to a single recipient.
     *
     * @param  string  $recipient
     * @param  string  $message
     * @return bool
     * @throws \Exception
     */
    public function sms(string $recipient, string $message) : bool
    {
        return $this->sendSms($recipient, $message);
    }

    /**
     * Send SMS to multiple recipients.
     *
     * @param  array  $recipients
     * @param  string  $message
     * @return array
     * @throws \Exception
     */
    public function smsMany(array $recipients, string $message) : array
    {
        $response = [];

        foreach (array_unique($recipients) as $recipient) {
            $response[$recipient] = $this->sendSms(strval($recipient), $message);
        }

        return $response;
    }

    /**
     * Handle API request.
     *
     * @param  string  $recipient
     * @param  string  $message
     * @return bool
     * @throws \Exception
     */
    private function sendSms(string $recipient, string $message) : bool
    {
        if (strpos($recipient, '+') !== 0) {
            throw new \Exception('Mobile number needs to be international! (Start with a + sign, eg. +27)');

            return false;
        }

        $parameters = [
            'action'   => 'message_send',
            'username' => $this->username,
            'password' => $this->password,
            'to'       => $recipient,
            'text'     => $message,
        ];

        $response = $this->client->request('GET', $this->queryUri('/json', $parameters));

        $json = json_decode($response->getBody());

        if (isset($json->status) && $json->status != 1) {
            if (isset($json->details) && ! empty($json->details)) {
                $message = $json->details;
            } elseif (isset($json->message) && ! empty($json->message)) {
                $message = $json->message;
            } else {
                $message = 'Unknown error occurred.';
            }

            throw new \Exception($message);

            return false;
        }

        return true;
    }

    /**
     * Build query with parameters.
     *
     * @param  string  $uri
     * @param  array  $parameters
     * @return string
     */
    private function queryUri(string $uri, array $parameters = []) : string
    {
        if (count($parameters)) {
            return sprintf('%s?%s', $uri, http_build_query($parameters));
        }

        return $uri;
    }
}
