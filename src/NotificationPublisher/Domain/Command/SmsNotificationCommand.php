<?php

namespace App\NotificationPublisher\Domain\Command;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;

class SmsNotificationCommand
{
    private Notification $notification;

    private SmsReceiver $receiver;

    public function __construct(Notification $notification, SmsReceiver $receiver)
    {
        $this->notification = $notification;
        $this->receiver = $receiver;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getReceiver(): SmsReceiver
    {
        return $this->receiver;
    }
}
