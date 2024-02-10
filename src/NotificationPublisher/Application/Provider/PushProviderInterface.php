<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;

interface PushProviderInterface
{
    public function sendPushNotification(Notification $notification, PushReceiver $receiver): ProviderResponseInterface;

    public function getName(): string;
}
