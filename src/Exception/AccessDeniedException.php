<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Exception;

class AccessDeniedException extends OneSendApiException
{
    public function __construct()
    {
        parent::__construct('You do not have permission to access this resource.', 403);
    }
}
