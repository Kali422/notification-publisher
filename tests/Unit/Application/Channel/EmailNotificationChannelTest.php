<?php

namespace App\Tests\Unit\Application\Channel;

use App\NotificationPublisher\Application\Channel\EmailNotificationChannel;
use App\NotificationPublisher\Domain\Command\EmailNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\EmailReceiver;
use App\NotificationPublisher\UserInterface\User;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailNotificationChannelTest extends TestCase
{

    public function testSend(): void
    {
        $notification = new Notification('test', 'test');
        $user = new User(1, 'email', true, 123, true, 'abc', true);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new EmailNotificationCommand($notification, new EmailReceiver($user->getId(), $user->getEmail())))
            ->willReturn(new Envelope(new stdClass()));

        $channel = new EmailNotificationChannel($messageBus);
        $channel->send($notification, $user);
    }
}
