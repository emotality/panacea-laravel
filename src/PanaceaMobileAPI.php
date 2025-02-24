<?php

namespace Emotality\Panacea;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PanaceaMobileAPI
{
    /**
     * The PanaceaMobile API client.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $client;

    /**
     * The PanaceaMobile config.
     *
     * @var array
     */
    protected $config;

    /**
     * PanaceaMobileAPI constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = Config::get('panacea') ?? [];

        if ($this->hasCredentials()) {
            $this->client = Http::withOptions([
                'base_uri'        => 'https://api.panaceamobile.com',
                'debug'           => false,
                'verify'          => true,
                'version'         => 2.0,
                'connect_timeout' => 30,
                'timeout'         => 60,
            ])->acceptJson();
        }
    }

    /**
     * Check if credentials are set.
     */
    private function hasCredentials(): bool
    {
        return strlen($this->config['username'] ?? null)
            && strlen($this->config['password'] ?? null);
    }

    /**
     * Run checks before sending API requests.
     *
     * @throws \Emotality\Panacea\PanaceaException
     */
    private function runChecks(): void
    {
        if (! $this->hasCredentials()) {
            // Run: php artisan vendor:publish --provider="Emotality\Panacea\PanaceaMobileServiceProvider"
            // Add: PANACEA_USERNAME=""
            // Add: PANACEA_PASSWORD=""
            throw new PanaceaException('Your PanaceaMobile username and password is required!');
        }
    }

    /**
     * Handle API request to send SMS(es).
     *
     * @throws \Emotality\Panacea\PanaceaException
     */
    public function sendSms(string $recipient, string $message, ?string $from = null): bool
    {
        $this->runChecks();

        if (strpos($recipient, '+') !== 0) {
            $recipient = '+'.$recipient;
        }

        $from = $from ?? $this->config['from'] ?? null;

        if ($from && ! ctype_alnum($from)) {
            return $this->smsError(sprintf('The "from" field can only contain alpha numeric characters! [%s]', $recipient));
        }

        $response = $this->client->get($this->queryUri('/json', [
            'action'   => 'message_send',
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'from'     => ($from ? trim($from) : null),
            'to'       => trim($recipient),
            'text'     => $message,
        ]));

        $json = $response->object();

        if ($response->failed() || (isset($json->status) && $json->status != 1)) {
            if (isset($json->details) && ! empty($json->details)) {
                $message = $json->details;
            } elseif (isset($json->message) && ! empty($json->message)) {
                $message = $json->message;
            } else {
                $message = 'SMS failed to send.';
            }

            $this->smsError(sprintf('%s [%s]', $message, $recipient), $response->status() ?? 1337);

            return false;
        }

        return true;
    }

    /**
     * Build query with parameters.
     */
    private function queryUri(string $uri, array $parameters = []): string
    {
        if (count($parameters)) {
            return sprintf('%s?%s', $uri, http_build_query($parameters));
        }

        return $uri;
    }

    /**
     * Throw exception or log error message.
     *
     * @throws \Emotality\Panacea\PanaceaException
     */
    private function smsError(string $message, int $code = 1337): bool
    {
        if ($this->config['exceptions']) {
            throw new PanaceaException($message, $code);
        } else {
            Log::critical(sprintf('PanaceaMobile SMS Error: "%s"', $message));
        }

        return false;
    }
}
