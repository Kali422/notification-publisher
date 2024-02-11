<?php

namespace App\NotificationPublisher\Application\Command;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\NotificationPublisher;
use App\NotificationPublisher\UserInterface\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test',
    description: 'Testing',
    aliases: ['app:test'],
    hidden: false
)]
class TestingConsoleCommand extends Command
{
    private NotificationPublisher $notificationPublisher;

    public function __construct(NotificationPublisher $notificationPublisher, string $name = null)
    {
        $this->notificationPublisher = $notificationPublisher;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->notificationPublisher->send(
            new Notification('title', 'message'),
            [new User(1, 'test@test.test', true, '123123123', true, '123', true)]
        );

        return Command::SUCCESS;
    }
}
