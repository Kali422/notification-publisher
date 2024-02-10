<?php

namespace App\NotificationPublisher\Domain\Events;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\ReceiverInterface;
use Throwable;

class ProviderFailedEvent
{
    private string $providerName;

    private string $channel;

    private Notification $notification;

    private ReceiverInterface $receiver;

    private ?ProviderResponseInterface $response;

    private ?Throwable $exception;

    public function __construct(
        string $providerName,
        string $channel,
        Notification $notification,
        ReceiverInterface $receiver,
        ?ProviderResponseInterface $response = null,
        ?Throwable $exception = null
    ) {
        $this->providerName = $providerName;
        $this->channel = $channel;
        $this->notification = $notification;
        $this->receiver = $receiver;
        $this->response = $response;
        $this->exception = $exception;
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

    public function getResponse(): ?ProviderResponseInterface
    {
        return $this->response;
    }

    public function getException(): ?Throwable
    {
        return $this->exception;
    }
}
