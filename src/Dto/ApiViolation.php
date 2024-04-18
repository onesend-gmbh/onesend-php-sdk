<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Dto;

readonly class ApiViolation
{
    public function __construct(
        private string $propertyPath,
        private string $message,
    ) {
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
