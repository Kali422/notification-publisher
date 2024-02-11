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
            DateTimeImmutable::createFromFormat('D, d M Y H:i:s O', 'Thu, 24 Aug 2023 05:01:45 +0000'),
            DateTimeImmutable::createFromFormat('D, d M Y H:i:s O', 'Thu, 24 Aug 2023 05:01:45 +0000'),
            null,
            null
        );
        $json = json_encode(
            [
                'sid' => 'sid',
                'date_created' => 'Thu, 24 Aug 2023 05:01:45 +0000',
                'date_sent' => 'Thu, 24 Aug 2023 05:01:45 +0000',
            ],
            JSON_THROW_ON_ERROR
        );

        $parser = new TwilioResponseParser();
        self::assertEquals($expected, $parser->parseResponse($json));
    }
}
