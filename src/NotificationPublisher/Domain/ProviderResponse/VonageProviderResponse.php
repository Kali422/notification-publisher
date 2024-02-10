<?php

namespace App\NotificationPublisher\Domain\ProviderResponse;

use DateTimeImmutable;

class VonageProviderResponse implements ProviderResponseInterface
{
    private ?string $messageUuid;

    private ?string $errorType;

    private ?string $errorTitle;

    private ?string $errorDetail;

    private ?string $errorInstance;

    public function __construct(
        ?string $messageUuid,
        ?string $errorType = null,
        ?string $errorTitle = null,
        ?string $errorDetail = null,
        ?string $errorInstance = null
    ) {
        $this->messageUuid = $messageUuid;
        $this->errorType = $errorType;
        $this->errorTitle = $errorTitle;
        $this->errorDetail = $errorDetail;
        $this->errorInstance = $errorInstance;
    }

    public function getMessageUuid(): ?string
    {
        return $this->messageUuid;
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getErrorTitle(): ?string
    {
        return $this->errorTitle;
    }

    public function getErrorDetail(): ?string
    {
        return $this->errorDetail;
    }

    public function getErrorInstance(): ?string
    {
        return $this->errorInstance;
    }

    public function isSuccess(): bool
    {
        return $this->messageUuid !== null;
    }

    public function getError(): ?string
    {
        return $this->errorDetail;
    }
}
