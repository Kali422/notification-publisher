<?php

namespace App\NotificationPublisher\Application\ResponseParser;

use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;

interface ResponseParserInterface
{
    public function parseResponse(string $content): ProviderResponseInterface;
}
