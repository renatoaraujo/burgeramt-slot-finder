<?php
declare(strict_types=1);

namespace App\Notification;

interface Notification
{
    public function send(string $content): bool;
}
