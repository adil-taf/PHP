<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Interfaces\EmailValidationInterface;

class ApiController
{
    public function __construct(private EmailValidationInterface $emailValidationService)
    {
    }

    #[Get('/emailValidation')]
    public function index()
    {
        $email  = 'adil@gmail.com';
        $result = $this->emailValidationService->verify($email);

        $score = $result->score;
        $isDeliverable = $result->isDeliverable;

        var_dump($score, $isDeliverable);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    #[Get('/curl')]
    public function curl()
    {
        $handle = curl_init();

        $apiKey = $_ENV['EMAILABLE_API_KEY'];
        $email  = 'adil@gmail.com';

        $params = [
            'api_key' => $apiKey,
            'email'   => $email,
        ];

        $url = 'https://api.emailable.com/v1/verify?' . http_build_query($params);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($handle);

        if ($content !== false) {
            $data = json_decode($content, true);

            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }
}
