<?php

namespace App\Tests\Unit\EventHandler;

use App\NotificationPublisher\Domain\Events\NotificationFailedEvent;
use App\NotificationPublisher\Domain\Events\ProviderFailedEvent;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\TwilioProviderResponse;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\EventHandler\NotificationFailedEventHandler;
use App\NotificationPublisher\EventHandler\ProviderFailedEventHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class NotificationFailedEventHandlerTest extends TestCase
{

    public function test__invoke()
    {
        $notification = new Notification('test', 'test');
        $receiver = new SmsReceiver(123, '123');

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects(self::once())
            ->method('error')
            ->with(
                "Notification sending failed",
                [
                    'notification' => $notification,
                    'receiver' => $receiver
                ]
            );

        $handler = new NotificationFailedEventHandler($logger);
        $handler->__invoke(new NotificationFailedEvent($notification, $receiver));
    }
}
