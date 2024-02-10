<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Application\ResponseParser\TwilioResponseParser;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\Domain\Receiver\WhatsAppReceiver;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class TwilioProvider implements SmsProviderInterface, WhatsAppProviderInterface
{
    public const NAME = 'Twilio';

    private string $senderNumber;

    private string $accountSid;

    private TwilioResponseParser $responseParser;

    private HttpClientInterface $twilioApiClient;

    public function __construct(
        string $senderNumber,
        string $accountSid,
        TwilioResponseParser $responseParser,
        HttpClientInterface $twilioApiClient
    ) {
        $this->senderNumber = $senderNumber;
        $this->accountSid = $accountSid;
        $this->responseParser = $responseParser;
        $this->twilioApiClient = $twilioApiClient;
    }

    public function sendSMS(Notification $notification, SmsReceiver $receiver): ProviderResponseInterface
    {
        try {
            $httpResponse = $this->twilioApiClient->request(
                'POST',
                sprintf('/2010-04-01/Accounts/%s/Messages.json', $this->accountSid),
                [
                    'headers' => ['Content-Type: application/x-www-form-urlencoded'],
                    'body' => [
                        'From' => $this->senderNumber,
                        'Body' => $notification->getMessage(),
                        'To' => $receiver->getPhoneNumber(),
                    ],
                ]
            );

            return $this->responseParser->parseResponse($httpResponse->getContent());
        } catch (Throwable $exception) {
            throw new ProviderFailedException(
                "Provider failed, reason: ".$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function sendWhatsAppMessage(
        Notification $notification,
        WhatsAppReceiver $receiver
    ): ProviderResponseInterface {
        try {
            $httpResponse = $this->twilioApiClient->request(
                'POST',
                sprintf('/2010-04-01/Accounts/%s/Messages.json', $this->accountSid),
                [
                    'headers' => ['Content-Type: application/x-www-form-urlencoded'],
                    'body' => [
                        'From' => "whatsapp:".$this->senderNumber,
                        'Body' => $notification->getMessage(),
                        'To' => "whatsapp:".$receiver->getPhoneNumber(),
                    ],
                ]
            );

            return $this->responseParser->parseResponse($httpResponse->getContent());
        } catch (Throwable $exception) {
            throw new ProviderFailedException(
                "Provider failed, reason: ".$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
