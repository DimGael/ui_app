<?php

namespace App\Constants;

class GeminiConstants
{
    public const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/'.self::GEMINI_MODEL;
    public const GEMINI_MODEL = 'gemini-3-flash-preview';
    public const MAX_TOKENS = 1000;

}
