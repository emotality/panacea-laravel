<?php

namespace Emotality\Panacea;

class PanaceaMessage
{
    /**
     * SMS recipient.
     *
     * @var string $to
     */
    protected $to;

    /**
     * SMS message.
     *
     * @var string $message
     */
    protected $message;

    /**
     * PanaceaMessage constructor.
     *
     * @param  string|null  $to
     * @param  string|null  $message
     * @return void
     */
    public function __construct(string $to = null, string $message = null)
    {
        $this->to = $to;
        $this->message = $message;
    }

    /**
     * Set SMS recipient.
     *
     * @param  string  $to
     * @return $this
     */
    public function to(string $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set SMS body.
     *
     * @param  string  $message
     * @return $this
     */
    public function message(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Send SMS.
     *
     * @return void
     * @throws \Exception
     */
    public function send()
    {
        if (! $this->to) {
            throw new \Exception('SMS has no recipient address.');
        }

        if (! $this->message) {
            throw new \Exception('SMS message can\'t be empty.');
        }

        \PanaceaMobile::sms($this->to, $this->message);
    }
}