<?php

namespace App\NotificationPublisher\Domain\Receiver;

abstract class AbstractReceiver implements ReceiverInterface
{
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
