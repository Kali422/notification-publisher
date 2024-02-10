<?php

namespace App\Tests\Unit\Application\Provider;

use App\NotificationPublisher\Application\Provider\MailtrapProvider;
use App\NotificationPublisher\Application\ResponseParser\MailtrapResponseParser;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\MailtrapProviderResponse;
use App\NotificationPublisher\Domain\Receiver\EmailReceiver;
use Mailtrap\AbstractMailtrapClient;
use Mailtrap\Api\Sending\Emails;
use Mailtrap\MailtrapClient;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

class MailtrapProviderTest extends TestCase
{

    public function testSendEmail(): void
    {
        $emails = $this->createMock(Emails::class);
        $emails
            ->expects(self::once())
            ->method('send')
            ->willReturn(new Response(200, [], 'json'));

        $senderClient = $this
            ->getMockBuilder(AbstractMailtrapClient::class)
            ->disableOriginalConstructor()
            ->addMethods(['emails'])
            ->getMock();

        $senderClient
            ->expects(self::once())
            ->method('emails')
            ->willReturn($emails);

        $client = $this
            ->getMockBuilder(MailtrapClient::class)
            ->disableOriginalConstructor()
            ->addMethods(['sending'])
            ->getMock();

        $client
            ->expects(self::once())
            ->method('sending')
            ->willReturn($senderClient);

        $responseParser = $this->createMock(MailtrapResponseParser::class);
        $responseParser
            ->expects(self::once())
            ->method('parseResponse')
            ->with('json')
            ->willReturn(new MailtrapProviderResponse(true, '123', null));

        $provider = new MailtrapProvider($client, 'fromaddress@abc', $responseParser);
        $provider->sendEmail(new Notification('abc', 'abc'), new EmailReceiver(1, 'email@email.email'));
    }
}
