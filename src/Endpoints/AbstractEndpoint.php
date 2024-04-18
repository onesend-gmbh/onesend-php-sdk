<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Endpoints;

use Http\Discovery\Psr17FactoryDiscovery;
use OnesendGmbh\OnesendPhpSdk\Dto\ApiViolation;
use OnesendGmbh\OnesendPhpSdk\Exception\AccessDeniedException;
use OnesendGmbh\OnesendPhpSdk\Exception\ApiUnavailableException;
use OnesendGmbh\OnesendPhpSdk\Exception\OneSendApiException;
use OnesendGmbh\OnesendPhpSdk\Exception\UnauthorizedException;
use OnesendGmbh\OnesendPhpSdk\Exception\ViolationException;
use OnesendGmbh\OnesendPhpSdk\OneSendApi;
use OnesendGmbh\OnesendPhpSdk\Resources\BaseResource;
use OnesendGmbh\OnesendPhpSdk\Resources\ResourceFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
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

    abstract protected function getResourceObject(): BaseResource;

    /**
     * @throws ViolationException
     * @throws ClientExceptionInterface
     * @throws ApiUnavailableException
     * @throws OneSendApiException
     * @throws AccessDeniedException
     */
    protected function createResource(string $iri, array $payload): BaseResource
    {
        $request = $this->requestFactory->createRequest('POST', OneSendApi::API_ENDPOINT.$iri);

        $request->withHeader('Content-Type', 'application/json');
        $request->withHeader('Accept', 'application/json');
        $request->withHeader('Authorization', 'ProjectKey '.$this->client->getApiKey());
        try {
            $request->withBody(
                Psr17FactoryDiscovery::findStreamFactory()->createStream(json_encode($payload, \JSON_THROW_ON_ERROR))
            );
        } catch (\JsonException $e) {
            throw new OneSendApiException('Unable to convert your payload to Json: '.$e->getMessage());
        }

        return $this->doRequestAndMapResponse($request);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws OneSendApiException
     */
    protected function getResource(string $iri): BaseResource
    {
        $request = $this->requestFactory->createRequest('GET', OneSendApi::API_ENDPOINT.$iri);

        $request->withHeader('Accept', 'application/json');
        $request->withHeader('Authorization', 'ProjectKey '.$this->client->getApiKey());

        return $this->doRequestAndMapResponse($request);
    }

    /**
     * @throws AccessDeniedException
     * @throws ViolationException
     * @throws ClientExceptionInterface
     * @throws ApiUnavailableException
     * @throws OneSendApiException
     * @throws UnauthorizedException
     */
    private function doRequestAndMapResponse(RequestInterface $request): BaseResource
    {
        $response = $this->client->doHttpRequest($request);

        $this->handleErrorResponses($response);

        try {
            return ResourceFactory::createFromApiResponse(
                json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR),
                $this->getResourceObject()
            );
        } catch (\JsonException $exception) {
            throw new OneSendApiException('Error converting API response to Resource: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws ViolationException
     * @throws OneSendApiException
     * @throws ApiUnavailableException
     * @throws AccessDeniedException
     * @throws UnauthorizedException
     */
    private function handleErrorResponses(ResponseInterface $response): void
    {
        if (502 === $response->getStatusCode()) {
            throw new ApiUnavailableException('Api is temporarily unavailable, please try again in a few seconds', 502);
        }

        if (422 === $response->getStatusCode()) {
            try {
                $responsePayload = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);
                throw new ViolationException(array_map(static fn (array $violation) => new ApiViolation($violation['propertyPath'], $violation['message']), $responsePayload['violations']));
            } catch (\JsonException) {
            }
        }

        if (401 === $response->getStatusCode()) {
            throw new UnauthorizedException();
        }

        if (403 === $response->getStatusCode()) {
            throw new AccessDeniedException();
        }

        if ($response->getStatusCode() > 299) {
            try {
                $responsePayload = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);
                $message = $responsePayload['detail'] ?? 'An unknown error occurred';
            } catch (\JsonException) {
                $message = 'An unknown error occurred';
            }

            throw new OneSendApiException($message, $response->getStatusCode());
        }
    }
}
