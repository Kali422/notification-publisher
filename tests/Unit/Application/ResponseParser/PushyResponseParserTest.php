<?php

namespace App\Tests\Unit\Application\ResponseParser;

use App\NotificationPublisher\Application\ResponseParser\PushyResponseParser;
use App\NotificationPublisher\Domain\ProviderResponse\PushyProviderResponse;
use PHPUnit\Framework\TestCase;

class PushyResponseParserTest extends TestCase
{

    public function testParseResponse(): void
    {
        $expected = new PushyProviderResponse('deviceDate', 'devicePlatform', null, null);
        $json = json_encode(['device' => ['date' => 'deviceDate', 'platform' => 'devicePlatform']],
            JSON_THROW_ON_ERROR);

        $parser = new PushyResponseParser();
        self::assertEquals($expected, $parser->parseResponse($json));
    }
}
