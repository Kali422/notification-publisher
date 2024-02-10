<?php

namespace App\Tests\Unit\Application\ResponseParser;

use App\NotificationPublisher\Application\ResponseParser\TwilioResponseParser;
use App\NotificationPublisher\Domain\ProviderResponse\TwilioProviderResponse;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TwilioResponseParserTest extends TestCase
{

    public function testParseResponse(): void
    {
        $expected = new TwilioProviderResponse(
            'sid',
            new DateTimeImmutable('2020-01-01'),
            new DateTimeImmutable('2020-01-02'),
            null,
            null
        );
        $json = json_encode(['sid' => 'sid', 'date_created' => '2020-01-01', 'date_send' => '2020-01-02'],
            JSON_THROW_ON_ERROR);

        $parser = new TwilioResponseParser();
        self::assertEquals($expected, $parser->parseResponse($json));
    }
}
