<?php

namespace App\NotificationPublisher\Domain\Receiver;

class EmailReceiver extends AbstractReceiver
{
    private string $email;

    public function __construct(int $userId, string $email)
    {
        parent::__construct($userId);
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
