<?php

namespace App\NotificationPublisher\Application\ResponseParser;

use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\ProviderResponse\PushyProviderResponse;
use JsonException;

class PushyResponseParser implements ResponseParserInterface
{

    /**
     * @throws JsonException
     */
    public function parseResponse(string $content): ProviderResponseInterface
    {
        $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new PushyProviderResponse(
            $json['device']['date'] ?? null,
            $json['device']['platform'] ?? null,
            $json['code'] ?? null,
            $json['error'] ?? null
        );
    }
}
