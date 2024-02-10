<?php

namespace App\NotificationPublisher\Domain\Command;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\EmailReceiver;

class EmailNotificationCommand
{
    private Notification $notification;

    private EmailReceiver $receiver;

    public function __construct(Notification $notification, EmailReceiver $receiver)
    {
        $this->notification = $notification;
        $this->receiver = $receiver;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getReceiver(): EmailReceiver
    {
        return $this->receiver;
    }
}
