<?php

namespace App\Tests\Unit\Application\Channel;

use App\NotificationPublisher\Application\Channel\SmsNotificationChannel;
use App\NotificationPublisher\Domain\Command\SmsNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\UserInterface\User;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SmsNotificationChannelTest extends TestCase
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
                new SmsNotificationCommand($notification, new SmsReceiver($user->getId(), $user->getPhoneNumber()))
            )
            ->willReturn(new Envelope(new stdClass()));

        $channel = new SmsNotificationChannel($messageBus);
        $channel->send($notification, $user);
    }
}
