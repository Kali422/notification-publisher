<?php

namespace App\NotificationPublisher\Application\Channel;

use App\NotificationPublisher\Domain\Command\PushNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;
use App\NotificationPublisher\UserInterface\User;
use Symfony\Component\Messenger\MessageBusInterface;

class PushNotificationChannel implements NotificationChannelInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function send(Notification $notification, User $user): void
    {
        if ($user->getMobileToken() !== null && $user->isPushMarketingAgree()) {
            $this->messageBus->dispatch(
                new PushNotificationCommand($notification, new PushReceiver($user->getId(), $user->getMobileToken()))
            );
        }
    }
}
