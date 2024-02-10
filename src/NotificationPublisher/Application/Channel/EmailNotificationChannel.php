<?php

namespace App\NotificationPublisher\Application\Channel;

use App\NotificationPublisher\Domain\Command\EmailNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\EmailReceiver;
use App\NotificationPublisher\UserInterface\User;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailNotificationChannel implements NotificationChannelInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function send(Notification $notification, User $user): void
    {
        if ($user->isEmailMarketingAgree()) {
            $this->messageBus->dispatch(
                new EmailNotificationCommand($notification, new EmailReceiver($user->getId(), $user->getEmail()))
            );
        }
    }
}
