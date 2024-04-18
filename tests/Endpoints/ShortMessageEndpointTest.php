<?php

declare(strict_types=1);

namespace Endpoints;

use Http\Mock\Client;
use OnesendGmbh\OnesendPhpSdk\Exception\AccessDeniedException;
use OnesendGmbh\OnesendPhpSdk\Exception\ApiUnavailableException;
use OnesendGmbh\OnesendPhpSdk\Exception\UnauthorizedException;
use OnesendGmbh\OnesendPhpSdk\Exception\ViolationException;
use OnesendGmbh\OnesendPhpSdk\OneSendApi;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ShortMessageEndpointTest extends TestCase
{
    public function testSendShortMessage(): void
    {
        $mockClient = new Client();
        $api = new OneSendApi('test123', $mockClient);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockStream = $this->createMock(StreamInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(201);
        $mockResponse->method('getBody')->willReturn($mockStream);
        $mockStream->method('getContents')->willReturn('{
          "@context": "/api/contexts/ShortMessage",
          "@id": "/api/contexts/short_messages/1",
          "@type": "ShortMessage",
          "id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
          "createdAt": "2024-03-18T10:44:04.933Z",
          "from": "TEST",
          "to": "+4915730955123",
          "message": "THIS IS A TEST",
          "numberOfParts": 1,
          "senderIsPhoneNumber": false,
          "status": "pending",
          "messageEvents": [],
          "pricePerPartInCredits": 8500,
          "gsmEncoded": true,
          "multiPartSms": false
        }');

        $mockClient->setDefaultResponse($mockResponse);

        $shortMessageResource = $api->shortMessages->send([
            'to' => '+4915730955123',
            'from' => 'TEST',
            'message' => 'THIS IS A TEST',
        ]);

        self::assertEquals('THIS IS A TEST', $shortMessageResource->getMessage());
        self::assertEquals('TEST', $shortMessageResource->getFrom());
        self::assertEquals('+4915730955123', $shortMessageResource->getTo());
        self::assertEquals('3fa85f64-5717-4562-b3fc-2c963f66afa6', $shortMessageResource->getId());
        self::assertEquals(1, $shortMessageResource->getNumberOfParts());
        self::assertEquals('pending', $shortMessageResource->getStatus());
        self::assertEquals('2024-03-18T10:44:04.933Z', $shortMessageResource->getCreatedAt());
    }

    public function testApiUnavailable(): void
    {
        $mockClient = new Client();
        $api = new OneSendApi('test123', $mockClient);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(502);
        $mockClient->setDefaultResponse($mockResponse);

        try {
            $api->shortMessages->send([
                'to' => '+4915730955123',
                'from' => 'TEST',
                'message' => 'THIS IS A TEST',
            ]);
            self::fail('No exception was thrown');
        } catch (ApiUnavailableException $exception) {
            self::assertTrue($exception->isRetryable());
        }
    }

    public function testUnauthorized(): void
    {
        $mockClient = new Client();
        $api = new OneSendApi('test123', $mockClient);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(401);
        $mockClient->setDefaultResponse($mockResponse);

        try {
            $api->shortMessages->send([
                'to' => '+4915730955123',
                'from' => 'TEST',
                'message' => 'THIS IS A TEST',
            ]);
            self::fail('No exception was thrown');
        } catch (UnauthorizedException $exception) {
            self::assertFalse($exception->isRetryable());
        }
    }

    public function testAccessDenied(): void
    {
        $mockClient = new Client();
        $api = new OneSendApi('test123', $mockClient);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(403);
        $mockClient->setDefaultResponse($mockResponse);

        try {
            $api->shortMessages->send([
                'to' => '+4915730955123',
                'from' => 'TEST',
                'message' => 'THIS IS A TEST',
            ]);
            self::fail('No exception was thrown');
        } catch (AccessDeniedException $exception) {
            self::assertFalse($exception->isRetryable());
        }
    }

    public function testViolations(): void
    {
        $mockClient = new Client();
        $api = new OneSendApi('test123', $mockClient);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockStream = $this->createMock(StreamInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(422);
        $mockResponse->method('getBody')->willReturn($mockStream);
        $mockStream->method('getContents')->willReturn('{
            "status": 422,
            "detail": "This value should not be blank.",
            "type": "ApiError",
            "title": "This value should not be blank.",
            "violations": [
                {
                    "propertyPath": "to",
                    "message": "This value should not be blank."
                }
            ]
        }');
        $mockClient->setDefaultResponse($mockResponse);

        try {
            $api->shortMessages->send([
                'from' => 'TEST',
                'message' => 'THIS IS A TEST',
            ]);
            self::fail('No exception was thrown');
        } catch (ViolationException $exception) {
            self::assertFalse($exception->isRetryable());
            self::assertCount(1, $exception->getViolations());
            $violation = $exception->getViolations()[0];
            self::assertEquals('This value should not be blank.', $violation->getMessage());
            self::assertEquals('to', $violation->getPropertyPath());
        }
    }
}
