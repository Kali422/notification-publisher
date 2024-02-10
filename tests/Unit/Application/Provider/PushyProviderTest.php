<?php

namespace App\Tests\Unit\Application\Provider;

use App\NotificationPublisher\Application\Provider\PushyProvider;
use App\NotificationPublisher\Application\ResponseParser\PushyResponseParser;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\PushyProviderResponse;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PushyProviderTest extends TestCase
{

    public function testSendPushNotification(): void
    {
        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpResponse
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('json');

        $providerResponse = new PushyProviderResponse('123', '123', null, null);

        $responseParser = $this->createMock(PushyResponseParser::class);
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

        $provider = new PushyProvider('123', $responseParser, $apiClient);
        $actual = $provider->sendPushNotification(new Notification('test', 'test'), new PushReceiver(1, 'token'));
        self::assertEquals($providerResponse, $actual);
    }

    public function testSendPushNotificationError(): void
    {
        $responseParser = $this->createMock(PushyResponseParser::class);

        $apiClient = $this->createMock(HttpClientInterface::class);
        $apiClient
            ->expects(self::once())
            ->method('request')
            ->with('POST')
            ->willThrowException(new Exception('error'));

        $provider = new PushyProvider('123', $responseParser, $apiClient);
        $this->expectException(ProviderFailedException::class);
        $provider->sendPushNotification(new Notification('test', 'test'), new PushReceiver(1, 'token'));
    }
}
