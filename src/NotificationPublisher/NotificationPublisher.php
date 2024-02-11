<?php

namespace App\NotificationPublisher;

use App\NotificationPublisher\Application\Channel\NotificationChannelInterface;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\UserInterface\User;

class NotificationPublisher
{
    /** @var NotificationChannelInterface[] */
    private array $channels = [];

    public function addChannel(NotificationChannelInterface $channel): void
    {
        $this->channels[] = $channel;
    }

    /**
     * @param Notification $notification
     * @param User[]       $users
     * @return void
     */
    public function send(Notification $notification, array $users): void
    {
        foreach ($users as $user) {
            foreach ($this->channels as $channel) {
                $channel->send($notification, $user);
            }
        }
    }

}
