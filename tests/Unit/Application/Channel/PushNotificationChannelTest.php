<?php

namespace App\Tests\Unit\Application\Channel;

use App\NotificationPublisher\Application\Channel\PushNotificationChannel;
use App\NotificationPublisher\Domain\Command\PushNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;
use App\NotificationPublisher\UserInterface\User;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class PushNotificationChannelTest extends TestCase
{

    public function testSend(): void
    {
        $notification = new Notification('test', 'test');
        $user = new User(1, 'email', true, 123, true, 'abc', true);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                new PushNotificationCommand($notification, new PushReceiver($user->getId(), $user->getMobileToken()))
            )
            ->willReturn(new Envelope(new stdClass()));

        $channel = new PushNotificationChannel($messageBus);
        $channel->send($notification, $user);
    }
}
