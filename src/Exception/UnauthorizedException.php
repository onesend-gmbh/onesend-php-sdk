<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Exception;

class UnauthorizedException extends OneSendApiException
{
    public function __construct()
    {
        parent::__construct('You are not authorized to access this resource, please check your Project Key', 401);
    }
}
