<?php

namespace Emotality\Panacea;

class PanaceaMobileSms
{
    /**
     * SMS recipients.
     *
     * @var array
     */
    protected $to = [];

    /**
     * SMS sender name.
     *
     * @var string|null
     */
    protected $from = null;

    /**
     * SMS message.
     *
     * @var string
     */
    protected $message;

    /**
     * PanaceaMobileSms constructor.
     *
     * @param  string|array|null  $to
     * @return void
     */
    public function __construct($to = null, ?string $message = null, ?string $from = null)
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
     * @return $this
     */
    public function to(string $to): self
    {
        $this->to[] = $to;

        return $this;
    }

    /**
     * Checks if 'to' array is empty.
     */
    public function isToEmpty(): bool
    {
        return empty($this->to);
    }

    /**
     * Add SMS sender name.
     *
     * @return $this
     */
    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Checks if 'from' property is null.
     */
    public function isFromNull(): bool
    {
        return is_null($this->from);
    }

    /**
     * Add many SMS recipients.
     *
     * @return $this
     */
    public function toMany(array $to): self
    {
        $this->to = array_merge($this->to, $to);

        return $this;
    }

    /**
     * Set SMS body.
     *
     * @return $this
     */
    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Send SMS(es).
     *
     * @throws \Emotality\Panacea\PanaceaException
     */
    public function send(): void
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
