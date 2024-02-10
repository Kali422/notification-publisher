<?php

namespace App\NotificationPublisher\Domain\Receiver;

class PushReceiver extends AbstractReceiver
{
    private string $mobileToken;

    public function __construct(int $userId, string $mobileToken)
    {
        parent::__construct($userId);
        $this->mobileToken = $mobileToken;
    }

    public function getMobileToken(): string
    {
        return $this->mobileToken;
    }
}
