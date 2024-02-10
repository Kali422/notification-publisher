<?php

namespace App\NotificationPublisher\Domain\ProviderResponse;

class MailtrapProviderResponse implements ProviderResponseInterface
{
    private bool $success;

    private ?string $messageId;

    private ?string $error;

    public function __construct(bool $success, ?string $messageId, ?string $error)
    {
        $this->success = $success;
        $this->messageId = $messageId;
        $this->error = $error;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}
