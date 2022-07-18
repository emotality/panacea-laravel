<?php

namespace Emotality\Panacea;

class PanaceaMobileSms
{
    /**
     * SMS recipients.
     *
     * @var array $to
     */
    protected $to = [];

    /**
     * SMS message.
     *
     * @var string $message
     */
    protected $message;

    /**
     * PanaceaMobileSms constructor.
     *
     * @param  string|array|null  $to
     * @param  string|null  $message
     * @return void
     */
    public function __construct($to = null, string $message = null)
    {
        if ($to) {
            $this->to = is_array($to) ? $to : [$to];
        }
        $this->message = $message;
    }

    /**
     * Add SMS recipient.
     *
     * @param  string  $to
     * @return $this
     */
    public function to(string $to)
    {
        $this->to[] = $to;

        return $this;
    }

    /**
     * Add many SMS recipients.
     *
     * @param  array  $to
     * @return $this
     */
    public function toMany(array $to)
    {
        $this->to = array_merge($this->to, $to);

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
     * Send SMS(es).
     *
     * @return void
     * @throws \Emotality\Panacea\PanaceaException
     */
    public function send()
    {
        if (! count($this->to)) {
            throw new PanaceaException('SMS has no recipient(s) attached.');
        }

        if (! $this->message) {
            throw new PanaceaException('SMS message can\'t be empty.');
        }

        PanaceaMobileFacade::smsMany($this->to, $this->message);
    }
}