<?php

namespace App\NotificationPublisher\Application\ResponseParser;

use App\NotificationPublisher\Domain\ProviderResponse\MailtrapProviderResponse;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use JsonException;

class MailtrapResponseParser implements ResponseParserInterface
{

    /**
     * @throws JsonException
     */
    public function parseResponse(string $content): ProviderResponseInterface
    {
        $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new MailtrapProviderResponse(
            $json['success'],
            $json['message_ids'][0] ?? null,
            $json['errors'][0] ?? null
        );
    }
}
