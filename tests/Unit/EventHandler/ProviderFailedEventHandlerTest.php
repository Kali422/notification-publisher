<?php

namespace App\Tests\Unit\EventHandler;

use App\NotificationPublisher\Domain\Events\ProviderFailedEvent;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\TwilioProviderResponse;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\EventHandler\ProviderFailedEventHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProviderFailedEventHandlerTest extends TestCase
{

    public function test__invoke()
    {
        $notification = new Notification('test', 'test');
        $receiver = new SmsReceiver(123, '123');
        $response = new TwilioProviderResponse(1, null, null, 1, 'error');

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects(self::once())
            ->method('alert')
            ->with(
                "Provider name failed, notification not send via channel channel, reason: Error code: 1, message: error",
                [
                    'provider' => 'name',
                    'channel' => 'channel',
                    'notification' => $notification,
                    'receiver' => $receiver,
                    'response' => $response,
                    'exception' => null
                ]
            );

        $handler = new ProviderFailedEventHandler($logger);
        $handler->__invoke(new ProviderFailedEvent('name', 'channel', $notification, $receiver, $response));
    }
}
