<?php

namespace App\NotificationPublisher\Application\ResponseParser;

use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\ProviderResponse\TwilioProviderResponse;
use App\NotificationPublisher\Domain\ProviderResponse\VonageProviderResponse;
use Exception;
use JsonException;

class VonageResponseParser implements ResponseParserInterface
{
    /**
     * @throws JsonException
     * @throws Exception
     */
    public function parseResponse(string $content): ProviderResponseInterface
    {
        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new VonageProviderResponse(
            $decoded['message_uuid'] ?? null,
            $decoded['type'] ?? null,
            $decoded['detail'] ?? null,
            $decoded['instance'] ?? null
        );
    }
}
