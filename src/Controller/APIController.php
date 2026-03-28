<?php

namespace App\Controller;

use App\Constants\GeminiConstants;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class APIController extends AbstractController
{
    public function __construct(
        public HttpClientInterface $httpClient
    )
    {
    }

    #[Route('api/test', name: 'api_test')]
    public function testingGemini(): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $response = $this->httpClient->request('POST', GeminiConstants::GEMINI_API_URL . ':generateContent?key=' . $_ENV['GEMINI_API_KEY'], [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "Salut c'est un message de test tu peux faire une réponse un peu farfelue ?"]
                            ]
                        ]
                    ]
                ]
            ]);

            $result = $response->toArray();
            $msgResponse = $result["candidates"][0]["content"]["parts"][0]["text"];
        } catch (Exception $e) {
            throw new \RuntimeException($e);
        }

        return $this->render('api/test.html.twig', [
            "result" => $msgResponse ?? "ERROR"
        ]);
    }

    #[Route('api/component/modify', name: 'api_component_modify', methods: ['POST'])]
    public function modify(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $prompt = $data['prompt'] ?? '';
        $htmlCode = $data['htmlCode'] ?? '';

        try {
            $response = $this->httpClient->request('POST', GeminiConstants::GEMINI_API_URL . ':generateContent?key=' . $_ENV['GEMINI_API_KEY'], [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "Tu modifies uniquement des composants HTML. Retourne UNIQUEMENT le code HTML modifié, sans explication, sans balise markdown.\n\nVoici le composant :\n\n{$htmlCode}\n\nInstruction : {$prompt}"]
                            ]
                        ]
                    ]
                ]
            ]);

            $result = $response->toArray();
            $code = $result['candidates'][0]['content']['parts'][0]['text'] ?? $htmlCode;
        } catch (Exception $exception) {
            throw new \RuntimeException($exception);
        }

        // Nettoyer les balises markdown si Claude en ajoute
        $code = preg_replace('/```html\n?/', '', $code);
        $code = preg_replace('/```\n?/', '', $code);
        $code = trim($code);

        return new JsonResponse(['code' => $code]);
    }
}
