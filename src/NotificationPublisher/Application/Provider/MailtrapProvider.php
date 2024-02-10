<?php

namespace App\NotificationPublisher\Application\Provider;

use App\NotificationPublisher\Application\ResponseParser\MailtrapResponseParser;
use App\NotificationPublisher\Domain\Notification;
use App\NotificationPublisher\Domain\ProviderResponse\ProviderResponseInterface;
use App\NotificationPublisher\Domain\Receiver\EmailReceiver;
use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailtrapProvider implements EmailProviderInterface
{
    public const NAME = 'Mailtrap';

    private MailtrapClient $client;

    private string $fromAddress;

    private MailtrapResponseParser $responseParser;

    public function __construct(MailtrapClient $client, string $fromAddress, MailtrapResponseParser $responseParser)
    {
        $this->client = $client;
        $this->fromAddress = $fromAddress;
        $this->responseParser = $responseParser;
    }

    public function sendEmail(Notification $notification, EmailReceiver $receiver): ProviderResponseInterface
    {
        $email = (new Email())
            ->from(new Address($this->fromAddress))
            ->replyTo(new Address($this->fromAddress))
            ->to(new Address($receiver->getEmail()))
            ->subject($notification->getTitle())
            ->text($notification->getMessage());

        $response = $this->client->sending()->emails()->send($email);

        return $this->responseParser->parseResponse(ResponseHelper::toString($response));
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
