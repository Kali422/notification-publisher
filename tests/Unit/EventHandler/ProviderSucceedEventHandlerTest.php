<?php

namespace App\Tests\Unit\EventHandler;

use App\NotificationPublisher\Domain\Events\ProviderSucceedEvent;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\TwilioProviderResponse;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\EventHandler\ProviderSucceedEventHandler;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProviderSucceedEventHandlerTest extends TestCase
{

    public function test__invoke()
    {
        $notification = new Notification('test', 'test');
        $receiver = new SmsReceiver(123, '123');
        $response = new TwilioProviderResponse(1, new DateTimeImmutable(), new DateTimeImmutable(), null, null);

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects(self::once())
            ->method('info')
            ->with(
                "Provider name succeed, notification send via channel channel",
                [
                    'provider' => 'name',
                    'channel' => 'channel',
                    'notification' => $notification,
                    'receiver' => $receiver,
                    'response' => $response,
                ]
            );

        $handler = new ProviderSucceedEventHandler($logger);
        $handler->__invoke(new ProviderSucceedEvent('name', 'channel', $notification, $receiver, $response));
    }
}
