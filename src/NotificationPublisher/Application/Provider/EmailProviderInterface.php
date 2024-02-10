<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\EmailReceiver;

interface EmailProviderInterface
{
    public function sendEmail(Notification $notification, EmailReceiver $receiver): ProviderResponseInterface;

    public function getName(): string;
}
