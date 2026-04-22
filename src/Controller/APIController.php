<?php

namespace App\Controller;

use App\Service\AI\GeminiAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller used to call different APIS for prompt AI
 */
final class APIController extends AbstractController
{
    public function __construct(
        public GeminiAPI $geminiApi
    )
    {}

    // TODO : Delete this route
    #[Route('api/test', name: 'api_test')]
    public function testingGemini(): \Symfony\Component\HttpFoundation\Response
    {
        $prompt = "Salut c'est un message de test tu peux faire une réponse un peu farfelue ?";
        $msgResponse = $this->geminiApi->simplePromptRequest($prompt);

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

        $finalPrompt = "Tu modifies uniquement des composants HTML. Retourne UNIQUEMENT le code HTML modifié, sans explication, sans balise markdown.\n\nVoici le composant :\n\n{$htmlCode}\n\nInstruction : {$prompt}";

       $code = $this->geminiApi->simplePromptRequest($finalPrompt) ?? $htmlCode;

        $code = preg_replace('/```html\n?/', '', $code);
        $code = preg_replace('/```\n?/', '', $code);
        $code = trim($code);

        return new JsonResponse(['code' => $code]);
    }
}
