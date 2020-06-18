<?php
declare(strict_types=1);

namespace App\IFTTT;

use Symfony\Component\HttpClient\HttpClient;

final class WebHookEventTrigger
{
    private string $webHookTriggerUri;

    public function __construct(string $webHookKey, string $webHookEventName)
    {
        $this->webHookTriggerUri = sprintf("https://maker.ifttt.com/trigger/%s/with/key/%s", $webHookEventName, $webHookKey);
    }

    public function trigger(): void
    {
        $httpClient = HttpClient::create();
        $httpClient->request("GET", $this->webHookTriggerUri);
    }
}
