<?php
declare(strict_types=1);

namespace App\Notification\AWS;

use App\Notification\EmailNotification;

// @todo: Implement SES email send
final class SESNotification implements EmailNotification
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function send(string $content): bool
    {
        return false;
    }
}
