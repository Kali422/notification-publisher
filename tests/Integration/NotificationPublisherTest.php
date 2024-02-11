<?php

namespace App\Tests\Integration;

use App\NotificationPublisher\Application\Provider\TwilioProvider;
use App\NotificationPublisher\Application\Provider\VonageProvider;
use App\NotificationPublisher\Application\ResponseParser\TwilioResponseParser;
use App\NotificationPublisher\Application\ResponseParser\VonageResponseParser;
use App\NotificationPublisher\Domain\Command\PushNotificationCommand;
use App\NotificationPublisher\Domain\Command\SmsNotificationCommand;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\UserInterface\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use App\NotificationPublisher\Application\CommandHandler\SmsNotificationCommandHandler;

class NotificationPublisherTest extends KernelTestCase
{
    public function testSending(): void
    {
        $user = new User(1, 'testEmail', true, 'testNumber', true, 'testToken', true);
        $notification = new Notification('testTitle', 'testMessage');

        self::bootKernel();

        $container = static::getContainer();

        $notificationPublisher = $container->get('TestNotificationPublisher');
        $notificationPublisher->send($notification, [$user]);

        /** @var InMemoryTransport $smsTransport */
        $smsTransport = $container->get('messenger.transport.async_sms_transport');
        $sentSms = $smsTransport->getSent();
        self::assertCount(1, $sentSms);
        self::assertEquals(
            new SmsNotificationCommand($notification, new SmsReceiver($user->getId(), $user->getPhoneNumber())),
            $sentSms[0]->getMessage()
        );

        /** @var InMemoryTransport $pushTransport */
        $pushTransport = $container->get('messenger.transport.async_push_transport');
        $sentPush = $pushTransport->getSent();
        self::assertCount(1, $sentPush);
        self::assertEquals(
            new PushNotificationCommand($notification, new PushReceiver($user->getId(), $user->getMobileToken())),
            $sentPush[0]->getMessage()
        );
    }

    /**
     * @dataProvider consumingSmsDataProvider
     */
    public function testConsumingSms(array $twilioResponses, array $vonageResponses): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $twilioClient = new MockHttpClient($twilioResponses);
        $twilioProvider = new TwilioProvider('testNumber', 'testSid', new TwilioResponseParser(), $twilioClient);
        $container->set('TwilioProvider', $twilioProvider);

        $vonageClient = new MockHttpClient($vonageResponses);
        $vonageProvider = new VonageProvider('testNumber', new VonageResponseParser(), $vonageClient);
        $container->set('VonageProvider', $vonageProvider);

        $smsHandler = $container->get(SmsNotificationCommandHandler::class);
        $smsHandler->__invoke(
            new SmsNotificationCommand(
                new Notification('testTilte', 'testMessage'),
                new SmsReceiver(1, 'testPhoneNumber')
            )
        );

        self::assertEquals(1, $twilioClient->getRequestsCount());
        self::assertEquals(0, $vonageClient->getRequestsCount());
    }

    public function consumingSmsDataProvider(): array
    {
        return [
            [
                'twilioResponses' => [
                    new MockResponse(
                        '{
  "account_sid": "ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
  "api_version": "2010-04-01",
  "body": "Hi there",
  "date_created": "Thu, 24 Aug 2023 05:01:45 +0000",
  "date_sent": "Thu, 24 Aug 2023 05:01:45 +0000",
  "date_updated": "Thu, 24 Aug 2023 05:01:45 +0000",
  "direction": "outbound-api",
  "error_code": null,
  "error_message": null,
  "from": "+15557122661",
  "num_media": "0",
  "num_segments": "1",
  "price": null,
  "price_unit": null,
  "messaging_service_sid": "MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
  "sid": "SMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
  "status": "queued",
  "subresource_uris": {
    "media": "/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Messages/SMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Media.json"
  },
  "to": "+15558675310",
  "uri": "/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Messages/SMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.json"
}',
                        ['http_code' => 200]
                    ),
                ],
                'vonageResponses' => [
                    new MockResponse(
                        '{
   "message_uuid": "aaaaaaaa-bbbb-cccc-dddd-0123456789ab"
}',
                        ['http_code' => 202]
                    ),
                ],
            ],
        ];
    }

}
