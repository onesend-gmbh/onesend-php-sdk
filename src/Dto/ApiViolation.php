<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Dto;

readonly class ApiViolation
{
    public function __construct(
        private string $propertyPah,
        private string $message,
    ) {
    }

    public function getPropertyPah(): string
    {
        return $this->propertyPah;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
