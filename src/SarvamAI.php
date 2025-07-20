<?php

namespace NextBuild\SarvamAI;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use NextBuild\SarvamAI\Exceptions\SarvamAIException;
use NextBuild\SarvamAI\Responses\SarvamAIResponse;

class SarvamAI
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.sarvam.ai';
    protected PendingRequest $client;

    public function __construct(string | null $apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('sarvam-ai.api_key');

        if (!$this->apiKey) {
            throw new SarvamAIException('API key is required. Please set SARVAM_AI_API_KEY in your environment or pass it to the constructor.');
        }

        $this->client = Http::withHeaders([
            'api-subscription-key' => $this->apiKey,
        ])->timeout(30);
    }

    /**
     * Convert speech to text
     *
     * @param string $filePath Path to audio file
     * @param string|null $model Model to use (default: 'saarika:v1')
     * @param string|null $languageCode Language code for the audio (e.g., 'en-IN')
     * @return SarvamAIResponse
     */
    public function speechToText(string $filePath, ?string $model = null, ?string $languageCode = null): SarvamAIResponse
    {
        if (!file_exists($filePath)) {
            throw new SarvamAIException("File not found: {$filePath}");
        }

        $parameters = [];

        if ($model) {
            $parameters['model'] = $model;
        }

        if ($languageCode) {
            $parameters['language_code'] = $languageCode;
        }

        $response = $this->client->attach(
            'file',
            file_get_contents($filePath),
            basename($filePath)
        )->post($this->baseUrl . '/speech-to-text', $parameters);

        return new SarvamAIResponse($response);
    }

    /**
     * Convert speech to text with translation
     *
     * @param string $filePath Path to audio file
     * @param string|null $prompt Optional prompt to guide the translation
     * @param string|null $model Optional model to use for translation (e.g., 'saarika:v1')
     * @return SarvamAIResponse
     */
    public function speechToTextTranslate(string $filePath, ?string $prompt = null, ?string $model = null): SarvamAIResponse
    {
        if (!file_exists($filePath)) {
            throw new SarvamAIException("File not found: {$filePath}");
        }

        $parameters = [];

        if ($prompt) {
            $parameters['prompt'] = $prompt;
        }

        if ($model) {
            $parameters['model'] = $model;
        }

        $response = $this->client->attach(
            'file',
            file_get_contents($filePath),
            basename($filePath)
        )->post($this->baseUrl . '/speech-to-text-translate', $parameters);

        return new SarvamAIResponse($response);
    }

    /**
     * Convert text to speech
     *
     * @param string $text Text to convert to speech
     * @param string $targetLanguageCode Target language code (e.g., 'bn-IN')
     * @return SarvamAIResponse
     */
    public function textToSpeech(string $text, string $targetLanguageCode): SarvamAIResponse
    {
        $response = $this->client->post($this->baseUrl . '/text-to-speech', [
            'text' => $text,
            'target_language_code' => $targetLanguageCode,
        ]);

        return new SarvamAIResponse($response);
    }

    /**
     * Translate text
     *
     * @param string $input Text to translate
     * @param string $sourceLanguageCode Source language code ('auto' for auto-detect)
     * @param string $targetLanguageCode Target language code
     * @return SarvamAIResponse
     */
    public function translateText(string $input, string $sourceLanguageCode = 'auto', string $targetLanguageCode = 'en-IN'): SarvamAIResponse
    {
        $response = $this->client->post($this->baseUrl . '/translate', [
            'input' => $input,
            'source_language_code' => $sourceLanguageCode,
            'target_language_code' => $targetLanguageCode,
        ]);

        return new SarvamAIResponse($response);
    }

    /**
     * Identify language of text
     *
     * @param string $input Text to identify language
     * @return SarvamAIResponse
     */
    public function identifyLanguage(string $input): SarvamAIResponse
    {
        $response = $this->client->post($this->baseUrl . '/text-lid', [
            'input' => $input,
        ]);

        return new SarvamAIResponse($response);
    }

    /**
     * Transliterate text
     *
     * @param string $input Text to transliterate
     * @param string $sourceLanguageCode Source language code ('auto' for auto-detect)
     * @param string $targetLanguageCode Target language code
     * @return SarvamAIResponse
     */
    public function transliterateText(string $input, string $sourceLanguageCode = 'auto', string $targetLanguageCode = 'en-IN'): SarvamAIResponse
    {
        $response = $this->client->post($this->baseUrl . '/transliterate', [
            'input' => $input,
            'source_language_code' => $sourceLanguageCode,
            'target_language_code' => $targetLanguageCode,
        ]);

        return new SarvamAIResponse($response);
    }

    /**
     * Chat completions
     *
     * @param array $messages Array of messages with 'content' and 'role' keys
     * @param string $model Model to use (default: 'sarvam-m')
     * @return SarvamAIResponse
     */
    public function chatCompletions(array $messages, string $model = 'sarvam-m'): SarvamAIResponse
    {
        $response = $this->client->post($this->baseUrl . '/v1/chat/completions', [
            'messages' => $messages,
            'model' => $model,
        ]);

        return new SarvamAIResponse($response);
    }

    /**
     * Set a custom API key
     *
     * @param string $apiKey
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        $this->client = Http::withHeaders([
            'api-subscription-key' => $this->apiKey,
        ])->timeout(30);

        return $this;
    }

    /**
     * Set custom timeout
     *
     * @param int $timeout Timeout in seconds
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->client = $this->client->timeout($timeout);
        return $this;
    }

    /**
     * Get the current API key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
