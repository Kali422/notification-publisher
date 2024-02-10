<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Application\ResponseParser\PushyResponseParser;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\PushReceiver;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class PushyProvider implements PushProviderInterface
{
    public const NAME = 'Pushy';

    private string $secretApiKey;

    private PushyResponseParser $responseParser;

    private HttpClientInterface $pushyApiClient;

    public function __construct(
        string $secretApiKey,
        PushyResponseParser $responseParser,
        HttpClientInterface $pushyApiClient
    ) {
        $this->secretApiKey = $secretApiKey;
        $this->responseParser = $responseParser;
        $this->pushyApiClient = $pushyApiClient;
    }

    public function sendPushNotification(Notification $notification, PushReceiver $receiver): ProviderResponseInterface
    {
        try {
            $httpRequest = $this->pushyApiClient->request(
                'POST',
                sprintf('/push?api_key=%s', $this->secretApiKey),
                [
                    'headers' => ['Content-Type: application/json'],
                    'body' => json_encode([
                        'to' => $receiver->getMobileToken(),
                        'data' => ['message' => $notification->getMessage()],
                        'notification' => ['title' => $notification->getTitle(), 'body' => $notification->getMessage()],
                    ], JSON_THROW_ON_ERROR),
                ]
            );

            return $this->responseParser->parseResponse($httpRequest->getContent());
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
