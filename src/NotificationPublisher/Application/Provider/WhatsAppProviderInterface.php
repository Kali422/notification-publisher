<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\WhatsAppReceiver;

interface WhatsAppProviderInterface
{
    public function sendWhatsAppMessage(Notification $notification, WhatsAppReceiver $receiver): ProviderResponseInterface;

    public function getName(): string;
}
