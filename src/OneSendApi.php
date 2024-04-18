<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk;

use Http\Discovery\Psr18ClientDiscovery;
use OnesendGmbh\OnesendPhpSdk\Endpoints\ShortMessageEndpoint;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OneSendApi
{
    public const API_ENDPOINT = 'https://api.onesend.de';

    protected ClientInterface $httpClient;
    protected string $apiKey;

    public readonly ShortMessageEndpoint $shortMessage;

    public function __construct(string $apiKey, ?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->apiKey = $apiKey;
        $this->initEndpoints();
    }

    private function initEndpoints(): void
    {
        $this->shortMessage = new ShortMessageEndpoint($this);
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function doHttpRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $request
        );
    }
}
