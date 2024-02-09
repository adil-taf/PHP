<?php

declare(strict_types=1);

namespace App\Services\Emailable;

use App\Interfaces\EmailValidationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class EmailValidationService implements EmailValidationInterface
{
    private string $baseUrl = 'https://api.emailable.com/v1/';

    public function __construct(private string $apiKey)
    {
    }

    public function verify(string $email): array
    {
        $stack = HandlerStack::create();

        $client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'timeout'  => 5
            ]
        );

        $params = [
            'api_key' => $this->apiKey,
            'email'   => $email,
        ];

        $response = $client->get('verify', ['query' => $params]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
