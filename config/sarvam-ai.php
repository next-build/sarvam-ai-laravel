<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sarvam AI API Key
    |--------------------------------------------------------------------------
    |
    | Your Sarvam AI API key. You can find this in your Sarvam AI dashboard.
    | Make sure to keep this key secret and never commit it to version control.
    |
    */
    'api_key' => env('SARVAM_AI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Language Codes
    |--------------------------------------------------------------------------
    |
    | Default language codes for various operations. These can be overridden
    | when calling the methods.
    |
    */
    'default_source_language' => env('SARVAM_AI_DEFAULT_SOURCE_LANGUAGE', 'auto'),
    'default_target_language' => env('SARVAM_AI_DEFAULT_TARGET_LANGUAGE', 'en-IN'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the HTTP client used to make requests to the Sarvam AI API.
    |
    */
    'timeout' => env('SARVAM_AI_TIMEOUT', 30),
    'retry' => env('SARVAM_AI_RETRY', 3),

    /*
    |--------------------------------------------------------------------------
    | Chat Completions Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration for chat completions.
    |
    */
    'chat' => [
        'default_model' => env('SARVAM_AI_DEFAULT_MODEL', 'sarvam-m'),
        'max_tokens' => env('SARVAM_AI_MAX_TOKENS', 1000),
        'temperature' => env('SARVAM_AI_TEMPERATURE', 0.7),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Language Codes
    |--------------------------------------------------------------------------
    |
    | List of supported language codes for reference.
    |
    */
    'supported_languages' => [
        'hi-IN' => 'Hindi (India)',
        'en-IN' => 'English (India)',
        'bn-IN' => 'Bengali (India)',
        'ta-IN' => 'Tamil (India)',
        'te-IN' => 'Telugu (India)',
        'kn-IN' => 'Kannada (India)',
        'ml-IN' => 'Malayalam (India)',
        'mr-IN' => 'Marathi (India)',
        'gu-IN' => 'Gujarati (India)',
        'pa-IN' => 'Punjabi (India)',
        'or-IN' => 'Odia (India)',
        'ur-IN' => 'Urdu (India)',
        'as-IN' => 'Assamese (India)',
        'ne-IN' => 'Nepali (India)',
        'si-IN' => 'Sinhala (India)',
        'my-IN' => 'Myanmar (India)',
    ],
];
