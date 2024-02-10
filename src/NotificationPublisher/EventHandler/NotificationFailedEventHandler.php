<?php

namespace App\NotificationPublisher\EventHandler;

use App\NotificationPublisher\Domain\Events\NotificationFailedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationFailedEventHandler implements MessageHandlerInterface
{
    private LoggerInterface $notificationLogger;

    public function __construct(LoggerInterface $notificationLogger)
    {
        $this->notificationLogger = $notificationLogger;
    }

    public function __invoke(NotificationFailedEvent $event): void
    {
        $this->notificationLogger->error(
            'Notification sending failed',
            [
                'notification' => $event->getNotification(),
                'receiver' => $event->getReceiver(),
            ]
        );
    }

}
