<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class StarWarAPIService
{
    private HttpClientInterface $httpClient;
    public const BASE_URL = "https://swapi.dev/api/";

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    public function getInformations(string $endUrl): array
    {
        return $this->makeRequest($endUrl)["results"];
    }

    public function getElement(string $url, int $id): array
    {
        $endUrl = "$url$id/";
        return $this->makeRequest($endUrl);
    }

    public function getPersonnagesWithAOtherPage(string $url, int $page): array
    {
        $endUrl = "$url/?page=$page";
        return $this->makeRequest($endUrl)["results"];
    }

    public function getInfoNextUrl(string $url, int $page = null): array
    {
        $endUrl = $page ? "$url?page=$page" : "$url";
        return $this->makeRequest($endUrl);
    }

    private function makeRequest(string $endUrl): array
    {
        return $this->httpClient->request('GET', self::BASE_URL.$endUrl)->toArray();
    }
}
