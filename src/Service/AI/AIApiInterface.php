<?php

namespace App\Service\AI;

interface AIApiInterface
{
    public function simplePromptRequest(string $prompt): ?string;

}
