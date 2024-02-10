<?php

namespace App\NotificationPublisher\UserInterface;

class User
{
    private int $id;

    private string $email;

    private bool $emailMarketingAgree;

    private ?string $phoneNumber;

    private bool $teleMarketingAgree;

    private ?string $mobileToken;

    private bool $pushMarketingAgree;

    public function __construct(
        int $id,
        string $email,
        bool $emailMarketingAgree,
        ?string $phoneNumber,
        bool $teleMarketingAgree,
        ?string $mobileToken,
        bool $pushMarketingAgree
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->emailMarketingAgree = $emailMarketingAgree;
        $this->phoneNumber = $phoneNumber;
        $this->teleMarketingAgree = $teleMarketingAgree;
        $this->mobileToken = $mobileToken;
        $this->pushMarketingAgree = $pushMarketingAgree;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isEmailMarketingAgree(): bool
    {
        return $this->emailMarketingAgree;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function isTeleMarketingAgree(): bool
    {
        return $this->teleMarketingAgree;
    }

    public function getMobileToken(): ?string
    {
        return $this->mobileToken;
    }

    public function isPushMarketingAgree(): bool
    {
        return $this->pushMarketingAgree;
    }
}
