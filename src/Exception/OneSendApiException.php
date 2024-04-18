<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Exception;

class OneSendApiException extends \Exception
{
    protected bool $retryable = false;

    public function __construct(string $message, int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function isRetryable(): bool
    {
        return $this->retryable;
    }
}
