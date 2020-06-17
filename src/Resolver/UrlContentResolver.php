<?php
declare(strict_types=1);

namespace App\Resolver;

use Symfony\Component\HttpClient\HttpClient;

final class UrlContentResolver
{
    private string $fullUrl;

    public function __construct(string $fullUrl)
    {
        $this->fullUrl = $fullUrl;
    }

    public function getContents(): string
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request("GET", $this->fullUrl);
        return $response->getContent();
    }
}
