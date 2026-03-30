<?php

namespace App\Service\AI;

use App\Constants\GeminiConstants;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeminiAPI implements AIApiInterface
{

    public function __construct(
        public HttpClientInterface $httpClient
    ){}
    public function simplePromptRequest(string $prompt): ?string
    {
        $msgResponse = null;
        try {
            $response = $this->httpClient->request('POST', GeminiConstants::GEMINI_API_URL . ':generateContent?key=' . $_ENV['GEMINI_API_KEY'], [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            ]);

            $result = $response->toArray();
            $msgResponse = $result["candidates"][0]["content"]["parts"][0]["text"];
        } catch (\Exception|TransportExceptionInterface|DecodingExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            // TODO - Log the error
            dump($e);
        }

        return $msgResponse;
    }
}
