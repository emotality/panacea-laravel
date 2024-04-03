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
     * SMS sender name.
     *
     * @var string|null $from
     */
    protected $from = null;

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
     * @param  string|null  $from
     * @return void
     */
    public function __construct($to = null, string $message = null, string $from = null)
    {
        if ($to) {
            $this->to = is_array($to) ? $to : [$to];
        }

        $this->from = $from;
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
     * Checks if 'to' array is empty.
     *
     * @param  string  $to
     * @return $this
     */
    public function isToEmpty()
    {
        return empty($this->to);
    }

    /**
     * Add SMS sender name.
     *
     * @param  string  $from
     * @return $this
     */
    public function from(string $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Checks if 'to' array is empty.
     *
     * @param  string  $to
     * @return $this
     */
    public function isFromNull()
    {
        return is_null($this->from);
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

        PanaceaMobileFacade::smsMany($this->to, $this->message, $this->from);
    }
}