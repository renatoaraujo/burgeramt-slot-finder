<?php
declare(strict_types=1);

namespace App\IFTTT;

use Symfony\Component\HttpClient\HttpClient;

final class WebHookEventTrigger
{
    private string $webHookTriggerUri;

    private bool $isIFTTTWebHookEnabled;

    public function __construct(bool $isIFTTTWebHookEnabled, string $webHookKey, string $webHookEventName)
    {
        $this->webHookTriggerUri = sprintf("https://maker.ifttt.com/trigger/%s/with/key/%s", $webHookEventName, $webHookKey);
        $this->isIFTTTWebHookEnabled = $isIFTTTWebHookEnabled;
    }

    public function trigger(): void
    {
        if ($this->isIFTTTWebHookEnabled) {
            $httpClient = HttpClient::create();
            $httpClient->request("GET", $this->webHookTriggerUri);
        }
    }
}
