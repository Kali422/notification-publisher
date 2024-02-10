<?php

namespace App\NotificationPublisher\Domain\Receiver;

class SmsReceiver extends AbstractReceiver
{
    private string $phoneNumber;

    public function __construct(int $userId, string $phoneNumber)
    {
        parent::__construct($userId);
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}
