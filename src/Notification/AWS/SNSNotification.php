<?php
declare(strict_types=1);

namespace App\Notification\AWS;

use App\Notification\SMSNotification;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;

final class SNSNotification implements SMSNotification
{
    private string $phoneNumber;

    private SnsClient $snsClient;

    public function __construct(string $phoneNumber, SnsClient $snsClient)
    {
        $this->phoneNumber = $phoneNumber;
        $this->snsClient = $snsClient;
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
            error_log($e->getMessage());
            return false;
        }
    }
}
