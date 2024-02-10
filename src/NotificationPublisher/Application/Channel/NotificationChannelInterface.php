<?php

namespace App\NotificationPublisher\Application\Channel;

use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\UserInterface\User;

interface NotificationChannelInterface
{
    public function send(Notification $notification, User $user): void;

}
