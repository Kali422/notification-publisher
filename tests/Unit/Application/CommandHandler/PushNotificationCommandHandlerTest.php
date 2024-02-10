<?php

namespace App\Tests\Unit\Application\CommandHandler;

use App\NotificationPublisher\Application\CommandHandler\PushNotificationCommandHandler;
use App\NotificationPublisher\Application\Provider\PushProviderInterface;
use App\NotificationPublisher\Domain\Command\PushNotificationCommand;
use App\NotificationPublisher\Domain\Events\NotificationFailedEvent;
use App\NotificationPublisher\Domain\Events\ProviderFailedEvent;
use App\NotificationPublisher\Domain\Events\ProviderSucceedEvent;
use App\NotificationPublisher\Domain\Exception\NotificationFailedException;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class PushNotificationCommandHandlerTest extends TestCase
{

    public function test__invoke()
    {
        $notification = new Notification('test', 'test');
        $receiver = new PushReceiver(1, 'token');

        $providerResponse = $this->createMock(ProviderResponseInterface::class);
        $providerResponse
            ->expects(self::once())
            ->method('isSuccess')
            ->willReturn(true);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new ProviderSucceedEvent('providerName', 'push', $notification, $receiver, $providerResponse))
            ->willReturn(new Envelope(new stdClass()));

        $provider = $this->createMock(PushProviderInterface::class);
        $provider
            ->expects(self::once())
            ->method('sendPushNotification')
            ->with($notification, $receiver)
            ->willReturn($providerResponse);

        $provider
            ->expects(self::once())
            ->method('getName')
            ->willReturn('providerName');

        $handler = new PushNotificationCommandHandler($eventBus);
        $handler->addProvider($provider);
        $handler->__invoke(new PushNotificationCommand($notification, $receiver));
    }

    public function test__invokeFailed()
    {
        $notification = new Notification('test', 'test');
        $receiver = new PushReceiver(1, 'token');

        $providerResponse = $this->createMock(ProviderResponseInterface::class);
        $providerResponse
            ->expects(self::once())
            ->method('isSuccess')
            ->willReturn(false);

        $providerResponse
            ->expects(self::once())
            ->method('getError')
            ->willReturn('error');

        $provider = $this->createMock(PushProviderInterface::class);
        $provider
            ->expects(self::once())
            ->method('sendPushNotification')
            ->with($notification, $receiver)
            ->willReturn($providerResponse);

        $provider
            ->expects(self::once())
            ->method('getName')
            ->willReturn('providerName');

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [
                    new ProviderFailedEvent(
                        'providerName',
                        'push',
                        $notification,
                        $receiver,
                        null,
                        new ProviderFailedException($provider::class.' failed, error: error')
                    ),
                ],
                [
                    new NotificationFailedEvent($notification, $receiver),
                ]
            )
            ->willReturnOnConsecutiveCalls(new Envelope(new stdClass()), new Envelope(new stdClass()));

        $handler = new PushNotificationCommandHandler($eventBus);
        $handler->addProvider($provider);

        $this->expectException(NotificationFailedException::class);
        $handler->__invoke(new PushNotificationCommand($notification, $receiver));
    }
}
