<?php

namespace App\NotificationPublisher\Domain\Receiver;

interface ReceiverInterface
{
    public function getUserId(): int;
}
