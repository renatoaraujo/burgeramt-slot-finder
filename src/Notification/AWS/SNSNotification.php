<?php
declare(strict_types=1);

namespace App\Notification\AWS;

use App\Notification\SMSNotification;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Psr\Log\LoggerInterface;

final class SNSNotification implements SMSNotification
{
    private string $topicArn;

    private SnsClient $snsClient;

    private LoggerInterface $logger;

    public function __construct(
        string $topicArn,
        SnsClient $snsClient,
        LoggerInterface $logger
    ) {
        $this->snsClient = $snsClient;
        $this->logger = $logger;
        $this->topicArn = $topicArn;
    }

    public function send(string $content): bool
    {
        try {
            $this->snsClient->SetSMSAttributes([
                'attributes' => [
                    'DefaultSMSType' => 'Transactional',
                ],
            ]);

            $this->snsClient->publish([
                'Message' => $content,
                'TopicArn' => $this->topicArn,
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
