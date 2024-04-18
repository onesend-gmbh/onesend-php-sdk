<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Endpoints;

use OnesendGmbh\OnesendPhpSdk\Exception\AccessDeniedException;
use OnesendGmbh\OnesendPhpSdk\Exception\ApiUnavailableException;
use OnesendGmbh\OnesendPhpSdk\Exception\OneSendApiException;
use OnesendGmbh\OnesendPhpSdk\Exception\ViolationException;
use OnesendGmbh\OnesendPhpSdk\Resources\BaseResource;
use OnesendGmbh\OnesendPhpSdk\Resources\ShortMessage;
use Psr\Http\Client\ClientExceptionInterface;

class ShortMessageEndpoint extends AbstractEndpoint
{
    private const RESOURCE_IRI = '/api/short_messages';

    protected function getResourceObject(): BaseResource
    {
        return new ShortMessage();
    }

    /**
     * @throws ViolationException
     * @throws ClientExceptionInterface
     * @throws ApiUnavailableException
     * @throws OneSendApiException
     * @throws AccessDeniedException
     */
    public function send(array $payload): ShortMessage
    {
        $resource = $this->createResource(self::RESOURCE_IRI, $payload);
        \assert($resource instanceof ShortMessage);

        return $resource;
    }
}
