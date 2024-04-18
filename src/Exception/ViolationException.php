<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Exception;

use OnesendGmbh\OnesendPhpSdk\Dto\ApiViolation;

class ViolationException extends OneSendApiException
{
    /** @var ApiViolation[] */
    private array $violations;

    /**
     * @param ApiViolation[] $violations
     */
    public function __construct(array $violations)
    {
        parent::__construct('There has been an error validating your request', 422);
        $this->violations = $violations;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
