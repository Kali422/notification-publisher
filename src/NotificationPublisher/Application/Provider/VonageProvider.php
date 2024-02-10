<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Application\ResponseParser\VonageResponseParser;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\SmsReceiver;
use App\NotificationPublisher\Domain\Receiver\WhatsAppReceiver;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class VonageProvider implements SmsProviderInterface, WhatsAppProviderInterface
{
    public const NAME = 'Vonage';

    private string $senderNumber;

    private VonageResponseParser $responseParser;

    private HttpClientInterface $vonageApiClient;

    public function __construct(
        string $senderNumber,
        VonageResponseParser $responseParser,
        HttpClientInterface $vonageApiClient
    ) {
        $this->senderNumber = $senderNumber;
        $this->responseParser = $responseParser;
        $this->vonageApiClient = $vonageApiClient;
    }

    public function sendSMS(Notification $notification, SmsReceiver $receiver): ProviderResponseInterface
    {
        try {
            $httpResponse = $this->vonageApiClient->request(
                'POST',
                '/v1/messages',
                [
                    'headers' => ['Content-Type: application/json'],
                    'body' => json_encode([
                        'message_type' => 'text',
                        'test' => 'abc',
                        'to' => $receiver->getPhoneNumber(),
                        'from' => $this->senderNumber,
                        'channel' => 'sms',
                    ], JSON_THROW_ON_ERROR),
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
            $httpResponse = $this->vonageApiClient->request(
                'POST',
                '/v1/messages',
                [
                    'headers' => ['Content-Type: application/json'],
                    'body' => json_encode([
                        'message_type' => 'text',
                        'text' => $notification->getMessage(),
                        'to' => $receiver->getPhoneNumber(),
                        'from' => $this->senderNumber,
                        'channel' => 'whatsapp',
                    ], JSON_THROW_ON_ERROR),
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
