<?php

namespace App\Tests\Unit\Application\Provider;

use App\NotificationPublisher\Application\Provider\VonageProvider;
use App\NotificationPublisher\Application\ResponseParser\VonageResponseParser;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\VonageProviderResponse;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\Domain\Receiver\WhatsAppReceiver;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class VonageProviderTest extends TestCase
{
    public function testSendSMS(): void
    {
        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpResponse
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('json');

        $providerResponse = new VonageProviderResponse(
            '123', null, null, null, null
        );

        $responseParser = $this->createMock(VonageResponseParser::class);
        $responseParser
            ->expects(self::once())
            ->method('parseResponse')
            ->with('json')
            ->willReturn($providerResponse);

        $apiClient = $this->createMock(HttpClientInterface::class);
        $apiClient
            ->expects(self::once())
            ->method('request')
            ->with('POST')
            ->willReturn($httpResponse);

        $provider = new VonageProvider('123', $responseParser, $apiClient);
        $actual = $provider->sendSMS(new Notification('test', 'test'), new SmsReceiver(1, '123'));
        self::assertEquals($providerResponse, $actual);
    }

    public function testSendSMSError(): void
    {
        $responseParser = $this->createMock(VonageResponseParser::class);

        $apiClient = $this->createMock(HttpClientInterface::class);
        $apiClient
            ->expects(self::once())
            ->method('request')
            ->with('POST')
            ->willThrowException(new Exception('error'));

        $provider = new VonageProvider('123', $responseParser, $apiClient);
        $this->expectException(ProviderFailedException::class);
        $provider->sendSMS(new Notification('test', 'test'), new SmsReceiver(1, '123'));
    }

    public function testSendWhatsAppMessage(): void
    {
        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpResponse
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('json');

        $providerResponse = new VonageProviderResponse(
            '123', null, null, null, null
        );

        $responseParser = $this->createMock(VonageResponseParser::class);
        $responseParser
            ->expects(self::once())
            ->method('parseResponse')
            ->with('json')
            ->willReturn($providerResponse);

        $apiClient = $this->createMock(HttpClientInterface::class);
        $apiClient
            ->expects(self::once())
            ->method('request')
            ->with('POST')
            ->willReturn($httpResponse);

        $provider = new VonageProvider('123', $responseParser, $apiClient);
        $actual = $provider->sendWhatsAppMessage(new Notification('test', 'test'), new WhatsAppReceiver(1, '123'));
        self::assertEquals($providerResponse, $actual);
    }

    public function testSendWhatsAppMessageError(): void
    {
        $responseParser = $this->createMock(VonageResponseParser::class);

        $apiClient = $this->createMock(HttpClientInterface::class);
        $apiClient
            ->expects(self::once())
            ->method('request')
            ->with('POST')
            ->willThrowException(new Exception('error'));

        $provider = new VonageProvider('123', $responseParser, $apiClient);
        $this->expectException(ProviderFailedException::class);
        $provider->sendWhatsAppMessage(new Notification('test', 'test'), new WhatsAppReceiver(1, '123'));
    }
}

