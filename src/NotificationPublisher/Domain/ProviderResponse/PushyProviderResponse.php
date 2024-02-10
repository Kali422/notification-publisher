<?php

namespace App\NotificationPublisher\Domain\ProviderResponse;

class PushyProviderResponse implements ProviderResponseInterface
{
    private ?string $deviceDate;

    private ?string $devicePlatform;

    private ?string $errorCode;

    private ?string $errorMessage;

    public function __construct(?string $deviceDate, ?string $devicePlatform, ?string $errorCode, ?string $errorMessage)
    {
        $this->deviceDate = $deviceDate;
        $this->devicePlatform = $devicePlatform;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function getDeviceDate(): ?string
    {
        return $this->deviceDate;
    }

    public function getDevicePlatform(): ?string
    {
        return $this->devicePlatform;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function isSuccess(): bool
    {
        return $this->errorCode === false;
    }

    public function getError(): ?string
    {
        return $this->errorMessage;
    }
}
