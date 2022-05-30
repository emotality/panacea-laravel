<?php

namespace Emotality\Panacea;

use GuzzleHttp\Client as Http;

class PanaceaMobileAPI
{
    /**
     * Guzzle HTTP client.
     *
     * @var \GuzzleHttp\Client
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
     * @var string
     */
    protected $username;

    /**
     * PanaceaMobile login password.
     *
     * @var string
     */
    protected $password;

    /**
     * PanaceaMobile from number/name.
     *
     * @var string|null
     */
    protected $from = null;

    /**
     * PanaceaMobile constructor.
     *
     * @param  array|null  $config
     * @return void
     */
    public function __construct(array $config = null)
    {
        $config = $config ?? config('panacea');

        $this->client = new Http([
            'base_uri' => $this->url,
            'timeout'  => 15.0,
            'headers'  => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'verify'   => $config['ssl'] ?? true,
        ]);

        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->from = $config['from'] ?? null;
    }

    /**
     * Send SMS to a single recipient.
     *
     * @param  string  $recipient
     * @param  string  $message
     * @param  string|null  $from
     * @return bool
     * @throws \Exception
     */
    public function sms(string $recipient, string $message, string $from = null) : bool
    {
        return $this->sendRequest($recipient, $message, $from);
    }

    /**
     * Send SMS to multiple recipients.
     *
     * @param  array  $recipients
     * @param  string  $message
     * @param  string|null  $from
     * @return array
     * @throws \Exception
     */
    public function smsMany(array $recipients, string $message, string $from = null) : array
    {
        $response = [];

        foreach (array_unique($recipients) as $recipient) {
            $response[$recipient] = $this->sendRequest(strval($recipient), $message, $from);
        }

        return $response;
    }

    /**
     * Handle API request.
     *
     * @param  string  $recipient
     * @param  string  $message
     * @param  string|null  $from
     * @return bool
     * @throws \Exception
     */
    private function sendRequest(string $recipient, string $message, string $from = null) : bool
    {
        if (strpos($recipient, '+') !== 0) {
            $recipient = '+'.$recipient;
        }

        $from = $from ?? $this->from ?? null;

        if ($from && ! ctype_alnum($from)) {
            throw new \Exception('PanaceaMobile: The "from" field can only contain alpha numeric characters!');
        }

        $parameters = [
            'action'   => 'message_send',
            'username' => $this->username,
            'password' => $this->password,
            'from'     => $from,
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
