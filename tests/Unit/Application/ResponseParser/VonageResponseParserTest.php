<?php

namespace App\Tests\Unit\Application\ResponseParser;

use App\NotificationPublisher\Application\ResponseParser\VonageResponseParser;
use App\NotificationPublisher\Domain\ProviderResponse\VonageProviderResponse;
use PHPUnit\Framework\TestCase;

class VonageResponseParserTest extends TestCase
{

    public function testParseResponse(): void
    {
        $expected = new VonageProviderResponse(
            'uuid', null, null, null, null
        );
        $json = json_encode(['message_uuid' => 'uuid'], JSON_THROW_ON_ERROR);

        $parser = new VonageResponseParser();
        self::assertEquals($expected, $parser->parseResponse($json));
    }
}
