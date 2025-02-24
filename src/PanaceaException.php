<?php

namespace Emotality\Panacea;

class PanaceaException extends \Exception
{
    public function __construct(string $message, int $code = 1337, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
