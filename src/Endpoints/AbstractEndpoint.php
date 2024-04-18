<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Endpoints;

use Http\Discovery\Psr17FactoryDiscovery;
use OnesendGmbh\OnesendPhpSdk\Dto\ApiViolation;
use OnesendGmbh\OnesendPhpSdk\Exception\ApiUnavailableException;
use OnesendGmbh\OnesendPhpSdk\Exception\ViolationException;
use OnesendGmbh\OnesendPhpSdk\OneSendApi;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractEndpoint
{
    protected OneSendApi $client;
    protected RequestFactoryInterface $requestFactory;

    public function __construct(OneSendApi $client)
    {
        $this->client = $client;
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
    }

    /**
     * @throws ViolationException
     * @throws ClientExceptionInterface
     * @throws ApiUnavailableException
     * @throws \JsonException
     */
    protected function createResource(string $iri, array $payload): ResponseInterface
    {
        $request = $this->requestFactory->createRequest('POST', OneSendApi::API_ENDPOINT.$iri);

        $request->withHeader('Content-Type', 'application/json');
        $request->withHeader('Accept', 'application/json');
        $request->withHeader('Authorization', 'ProjectKey '.$this->client->getApiKey());
        $request->withBody(
            Psr17FactoryDiscovery::findStreamFactory()->createStream(json_encode($payload, \JSON_THROW_ON_ERROR))
        );

        $response = $this->client->doHttpRequest($request);

        if (502 === $response->getStatusCode()) {
            throw new ApiUnavailableException('Api is temporarily unavailable, please try again in a few seconds', 502);
        }

        if (422 === $response->getStatusCode()) {
            $responsePayload = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);
            throw new ViolationException(array_map(static fn (array $violation) => new ApiViolation($violation['propertyPath'], $violation['message']), $responsePayload));
        }

        return $response;
    }

    /**
     * @throws ClientExceptionInterface
     */
    protected function getResource(string $iri): ResponseInterface
    {
        $request = $this->requestFactory->createRequest('GET', OneSendApi::API_ENDPOINT.$iri);

        $request->withHeader('Accept', 'application/json');
        $request->withHeader('Authorization', 'ProjectKey '.$this->client->getApiKey());

        return $this->client->doHttpRequest($request);
    }
}
