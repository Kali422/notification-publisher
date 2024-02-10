<?php

namespace App\NotificationPublisher\Domain\ProviderResponse;

use DateTimeImmutable;

class TwilioProviderResponse implements ProviderResponseInterface
{
    private string $sid;

    private ?DateTimeImmutable $dateCreated;

    private ?DateTimeImmutable $dateSend;

    private ?int $errorCode;

    private ?string $errorMessage;

    public function __construct(
        string $sid,
        ?DateTimeImmutable $dateCreated,
        ?DateTimeImmutable $dateSend,
        ?int $errorCode,
        ?string $errorMessage
    ) {
        $this->sid = $sid;
        $this->dateCreated = $dateCreated;
        $this->dateSend = $dateSend;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function getSid(): string
    {
        return $this->sid;
    }

    public function getDateCreated(): ?DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function getDateSend(): ?DateTimeImmutable
    {
        return $this->dateSend;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function isSuccess(): bool
    {
        return $this->errorCode === null;
    }

    public function getError(): ?string
    {
        return "Error code: ".$this->errorCode.', message: '.$this->errorMessage;
    }

}
