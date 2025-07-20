<?php

namespace NextBuild\SarvamAI\Responses;

use Illuminate\Support\Str;
use Illuminate\Http\Client\Response;
use NextBuild\SarvamAI\Exceptions\SarvamAIException;

class SarvamAIResponse
{
    protected Response $response;
    protected array $data;

    public function __construct(Response $response)
    {
        $this->response = $response;

        if (!$response->successful()) {
            throw new SarvamAIException(
                "API request failed with status {$response->status()}: {$response->body()}",
                $response->status()
            );
        }

        $this->data = $response->json() ?? [];
    }

    /**
     * Get the raw response data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the HTTP status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->response->status();
    }

    /**
     * Get the raw response body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->response->body();
    }

    /**
     * Get the response headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->response->headers();
    }

    /**
     * Check if the response was successful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->response->successful();
    }

    /**
     * Get a specific field from the response data
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return data_get($this->data, $key, $default);
    }

    /**
     * Get the transcript for speech-to-text responses
     *
     * @return string|null
     */
    public function getTranscript(): ?string
    {
        return $this->get('transcript');
    }

    /**
     * Get the translated text for translation responses
     *
     * @return string|null
     */
    public function getTranslatedText(): ?string
    {
        return $this->get('translated_text');
    }

    /**
     * Get the detected language for language identification
     *
     * @return string|null
     */
    public function getDetectedLanguage(): ?string
    {
        return $this->get('detected_language');
    }

    /**
     * Get the transliterated text
     *
     * @return string|null
     */
    public function getTransliteratedText(): ?string
    {
        return $this->get('transliterated_text');
    }

    /**
     * Get the audio URL for text-to-speech responses
     *
     * @return string|null
     */
    public function getAudioUrl(): ?string
    {
        return $this->get('audio_url');
    }

    /**
     * Get the chat completion response
     *
     * @return array|null
     */
    public function getChatCompletion(): ?array
    {
        return $this->get('choices.0.message');
    }

    /**
     * Get the chat completion content
     *
     * @return string|null
     */
    public function getChatCompletionContent(): ?string
    {
        return $this->get('choices.0.message.content');
    }

    /**
     * Convert response to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Convert response to JSON
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->data);
    }

    /**
     * Handle dynamic method calls for getting data
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (str_starts_with($method, 'get')) {
            $key = Str::snake(substr($method, 3));
            return $this->get($key, $parameters[0] ?? null);
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}
