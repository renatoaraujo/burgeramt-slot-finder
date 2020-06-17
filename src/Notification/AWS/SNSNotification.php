<?php
declare(strict_types=1);

namespace App\Notification\AWS;

use App\Notification\SMSNotification;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Psr\Log\LoggerInterface;

final class SNSNotification implements SMSNotification
{
    private string $phoneNumber;

    private SnsClient $snsClient;

    private LoggerInterface $logger;

    public function __construct(string $phoneNumber, SnsClient $snsClient, LoggerInterface $logger)
    {
        $this->phoneNumber = $phoneNumber;
        $this->snsClient = $snsClient;
        $this->logger = $logger;
    }

    public function send(string $content): bool
    {
        try {
            $this->snsClient->publish([
                'Message' => $content,
                'PhoneNumber' => $this->phoneNumber,
            ]);
            return true;
        } catch (AwsException $e) {
            $this->logger->error(
                sprintf('SMS message failed with message: %s', $e->getMessage())
            );
            return false;
        }
    }
}
