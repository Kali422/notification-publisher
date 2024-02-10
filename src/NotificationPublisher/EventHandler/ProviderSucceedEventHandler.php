<?php

namespace App\NotificationPublisher\EventHandler;

use App\NotificationPublisher\Domain\Events\ProviderSucceedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProviderSucceedEventHandler implements MessageHandlerInterface
{
    private LoggerInterface $providerLogger;

    public function __construct(LoggerInterface $providerLogger)
    {
        $this->providerLogger = $providerLogger;
    }

    public function __invoke(ProviderSucceedEvent $event): void
    {
        $this->providerLogger->info(
            "Provider {$event->getProviderName()} succeed, notification send via channel {$event->getChannel()}",
            [
                'provider' => $event->getProviderName(),
                'channel' => $event->getChannel(),
                'notification' => $event->getNotification(),
                'receiver' => $event->getReceiver(),
                'response' => $event->getResponse(),
            ]
        );
    }
}
