<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Exception;

class ApiUnavailableException extends OneSendApiException
{
    protected bool $retryable = true;
}
