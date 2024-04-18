<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OneSendApi
{
    public const API_ENDPOINT = 'https://api.onesend.de';

    protected ClientInterface $httpClient;
    protected string $apiKey;

    public function __construct(?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
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
