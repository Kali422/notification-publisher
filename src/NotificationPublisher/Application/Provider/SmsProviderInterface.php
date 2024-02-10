<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;

interface SmsProviderInterface
{
    /**
     * @throws ProviderFailedException
     */
    public function sendSMS(Notification $notification, SmsReceiver $receiver): ProviderResponseInterface;

    public function getName(): string;
}
