<?php

namespace App\NotificationPublisher\EventHandler;

use App\NotificationPublisher\Domain\Events\ProviderFailedEvent;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class ProviderFailedEventHandler implements MessageHandlerInterface
{
    private LoggerInterface $providerLogger;

    public function __construct(LoggerInterface $providerLogger)
    {
        $this->providerLogger = $providerLogger;
    }

    public function __invoke(ProviderFailedEvent $event): void
    {
        $this->providerLogger->alert(
            "Provider {$event->getProviderName()} failed, notification not send via channel {$event->getChannel()}, reason: ".$this->getReason(
                $event->getResponse(),
                $event->getException()
            ),
            [
                'provider' => $event->getProviderName(),
                'notification' => $event->getNotification(),
                'channel' => $event->getChannel(),
                'receiver' => $event->getReceiver(),
                'response' => $event->getResponse(),
                'exception' => $event->getException(),
            ]
        );
    }

    private function getReason(?ProviderResponseInterface $response, ?Throwable $exception): string
    {
        if ($response !== null) {
            return $response->getError();
        }

        if ($exception !== null) {
            return $exception->getMessage();
        }

        return "unknown";
    }
}
