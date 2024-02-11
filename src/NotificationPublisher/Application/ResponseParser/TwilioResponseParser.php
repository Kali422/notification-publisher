<?php

namespace App\NotificationPublisher\Application\ResponseParser;

use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\ProviderResponse\TwilioProviderResponse;
use DateTimeImmutable;
use Exception;
use JsonException;

class TwilioResponseParser implements ResponseParserInterface
{
    /**
     * @throws JsonException
     * @throws Exception
     */
    public function parseResponse(string $content): ProviderResponseInterface
    {
        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new TwilioProviderResponse(
            $decoded['sid'],
            DateTimeImmutable::createFromFormat('D, d M Y H:i:s O', $decoded['date_created']),
            DateTimeImmutable::createFromFormat('D, d M Y H:i:s O', $decoded['date_sent']),
            $decoded['error_code'] ?? null,
            $decoded['error_message'] ?? null
        );
    }
}
