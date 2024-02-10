<?php

namespace App\NotificationPublisher\Application\Channel;

use App\NotificationPublisher\Domain\Command\SmsNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\UserInterface\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SmsNotificationChannel implements NotificationChannelInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function send(Notification $notification, User $user): void
    {
        if ($user->getPhoneNumber() !== null && $user->isTeleMarketingAgree()) {
            $this->messageBus->dispatch(
                new SmsNotificationCommand(
                    $notification, new SmsReceiver($user->getId(), $user->getPhoneNumber())
                )
            );
        }
    }
}
