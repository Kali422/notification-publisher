<?php

namespace App\NotificationPublisher\Domain\ProviderResponse;

interface ProviderResponseInterface
{
    public function isSuccess(): bool;

    public function getError(): ?string;
}
