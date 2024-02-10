<?php

namespace App\NotificationPublisher\Application\CommandHandler;

use App\NotificationPublisher\Application\Provider\EmailProviderInterface;
use App\NotificationPublisher\Domain\Command\EmailNotificationCommand;
use App\NotificationPublisher\Domain\Events\NotificationFailedEvent;
use App\NotificationPublisher\Domain\Events\ProviderFailedEvent;
use App\NotificationPublisher\Domain\Events\ProviderSucceedEvent;
use App\NotificationPublisher\Domain\Exception\NotificationFailedException;
use App\NotificationPublisher\Domain\Exception\ProviderFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailNotificationCommandHandler implements MessageHandlerInterface
{
    public const CHANNEL = 'email';

    /** @var EmailProviderInterface[] */
    private array $providers = [];

    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function addProvider(EmailProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    public function __invoke(EmailNotificationCommand $command): void
    {
        foreach ($this->providers as $provider) {
            try {
                $response = $provider->sendEmail($command->getNotification(), $command->getReceiver());

                if ($response->isSuccess() === false) {
                    throw new ProviderFailedException($provider::class." failed, error: ".$response->getError());
                }

                $this->eventBus->dispatch(
                    new ProviderSucceedEvent(
                        $provider->getName(),
                        self::CHANNEL,
                        $command->getNotification(),
                        $command->getReceiver(),
                        $response
                    )
                );

                return;
            } catch (ProviderFailedException $exception) {
                $this->eventBus->dispatch(
                    new ProviderFailedEvent(
                        $provider->getName(),
                        self::CHANNEL,
                        $command->getNotification(),
                        $command->getReceiver(),
                        null,
                        $exception
                    )
                );
            }
        }

        $this->eventBus->dispatch(new NotificationFailedEvent($command->getNotification(), $command->getReceiver()));

        throw new NotificationFailedException("Every provider failed for channel ".self::CHANNEL);
    }
}
