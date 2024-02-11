<?php

namespace App\Tests\Unit\Application\Service;

use App\NotificationPublisher\Application\Channel\NotificationChannelInterface;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\NotificationPublisher;
use App\NotificationPublisher\UserInterface\User;
use PHPUnit\Framework\TestCase;

class NotificationPublisherTest extends TestCase
{

    public function testSend(): void
    {
        $notification = new Notification('test', 'test');
        $user1 = new User(1, 'email', true, null, false, null, false);
        $user2 = new User(2, 'email2', true, '123', true, 'token', true);

        $channel = $this->createMock(NotificationChannelInterface::class);
        $channel
            ->expects(self::exactly(2))
            ->method('send')
            ->withConsecutive([$notification, $user1], [$notification, $user2]);

        $sender = new NotificationPublisher();
        $sender->addChannel($channel);
        $sender->send(
            $notification,
            [
                $user1,
                $user2,
            ]
        );
    }
}
