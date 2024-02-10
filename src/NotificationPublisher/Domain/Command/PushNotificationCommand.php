<?php

namespace App\NotificationPublisher\Domain\Command;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;

class PushNotificationCommand
{
    private Notification $notification;

    private PushReceiver $receiver;

    public function __construct(Notification $notification, PushReceiver $receiver)
    {
        $this->notification = $notification;
        $this->receiver = $receiver;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getReceiver(): PushReceiver
    {
        return $this->receiver;
    }
}
