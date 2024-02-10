<?php

namespace App\NotificationPublisher\Domain\Events;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\ReceiverInterface;

class ProviderSucceedEvent
{
    private string $providerName;

    private string $channel;

    private Notification $notification;

    private ReceiverInterface $receiver;

    private ProviderResponseInterface $response;

    public function __construct(
        string $providerName,
        string $channel,
        Notification $notification,
        ReceiverInterface $receiver,
        ProviderResponseInterface $response
    ) {
        $this->providerName = $providerName;
        $this->channel = $channel;
        $this->notification = $notification;
        $this->receiver = $receiver;
        $this->response = $response;
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getReceiver(): ReceiverInterface
    {
        return $this->receiver;
    }

    public function getResponse(): ProviderResponseInterface
    {
        return $this->response;
    }
}
