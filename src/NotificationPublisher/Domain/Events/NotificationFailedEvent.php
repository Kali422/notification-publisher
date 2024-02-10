<?php

namespace App\NotificationPublisher\Domain\Events;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\ReceiverInterface;

class NotificationFailedEvent
{
    private Notification $notification;

    private ReceiverInterface $receiver;

    public function __construct(Notification $notification, ReceiverInterface $receiver)
    {
        $this->notification = $notification;
        $this->receiver = $receiver;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getReceiver(): ReceiverInterface
    {
        return $this->receiver;
    }
}
