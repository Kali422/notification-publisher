<?php

namespace App\Tests\Unit\Application\ResponseParser;

use App\NotificationPublisher\Application\ResponseParser\MailtrapResponseParser;
use App\NotificationPublisher\Domain\ProviderResponse\MailtrapProviderResponse;
use PHPUnit\Framework\TestCase;

class MailtrapResponseParserTest extends TestCase
{

    public function testParseResponse(): void
    {
        $expected = new MailtrapProviderResponse(true, '123', null);
        $json = json_encode(['success' => true, 'message_ids' => ['123']], JSON_THROW_ON_ERROR);

        $parser = new MailtrapResponseParser();
        self::assertEquals($expected, $parser->parseResponse($json));
    }
}
