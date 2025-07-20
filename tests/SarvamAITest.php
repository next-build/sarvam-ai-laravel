<?php

namespace NextBuild\SarvamAI\Tests;

use NextBuild\SarvamAI\SarvamAI;
use NextBuild\SarvamAI\Exceptions\SarvamAIException;
use NextBuild\SarvamAI\Responses\SarvamAIResponse;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;

class SarvamAITest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \NextBuild\SarvamAI\SarvamAIServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'SarvamAI' => \NextBuild\SarvamAI\Facades\SarvamAI::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sarvam-ai.api_key', 'test-api-key');
    }

    public function test_can_instantiate_sarvam_ai()
    {
        $sarvamAI = new SarvamAI('test-api-key');
        $this->assertInstanceOf(SarvamAI::class, $sarvamAI);
        $this->assertEquals('test-api-key', $sarvamAI->getApiKey());
    }

    public function test_throws_exception_without_api_key()
    {
        $this->expectException(SarvamAIException::class);
        $this->expectExceptionMessage('API key is required');
        
        new SarvamAI();
    }

    public function test_can_set_api_key()
    {
        $sarvamAI = new SarvamAI('test-api-key');
        $result = $sarvamAI->setApiKey('new-api-key');
        
        $this->assertInstanceOf(SarvamAI::class, $result);
        $this->assertEquals('new-api-key', $sarvamAI->getApiKey());
    }

    public function test_can_set_timeout()
    {
        $sarvamAI = new SarvamAI('test-api-key');
        $result = $sarvamAI->setTimeout(60);
        
        $this->assertInstanceOf(SarvamAI::class, $result);
    }

    public function test_translate_text_makes_correct_request()
    {
        Http::fake([
            'api.sarvam.ai/translate' => Http::response([
                'translated_text' => 'नमस्ते संसार',
            ], 200),
        ]);

        $sarvamAI = new SarvamAI('test-api-key');
        $response = $sarvamAI->translateText('Hello world', 'en-IN', 'hi-IN');

        $this->assertInstanceOf(SarvamAIResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('नमस्ते संसार', $response->getTranslatedText());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.sarvam.ai/translate' &&
                   $request->header('api-subscription-key') === ['test-api-key'] &&
                   $request->data() === [
                       'input' => 'Hello world',
                       'source_language_code' => 'en-IN',
                       'target_language_code' => 'hi-IN',
                   ];
        });
    }

    public function test_identify_language_makes_correct_request()
    {
        Http::fake([
            'api.sarvam.ai/text-lid' => Http::response([
                'detected_language' => 'en-IN',
            ], 200),
        ]);

        $sarvamAI = new SarvamAI('test-api-key');
        $response = $sarvamAI->identifyLanguage('Hello world');

        $this->assertInstanceOf(SarvamAIResponse::class, $response);
        $this->assertEquals('en-IN', $response->getDetectedLanguage());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.sarvam.ai/text-lid' &&
                   $request->header('api-subscription-key') === ['test-api-key'] &&
                   $request->data() === ['input' => 'Hello world'];
        });
    }

    public function test_transliterate_text_makes_correct_request()
    {
        Http::fake([
            'api.sarvam.ai/transliterate' => Http::response([
                'transliterated_text' => 'namaste duniya',
            ], 200),
        ]);

        $sarvamAI = new SarvamAI('test-api-key');
        $response = $sarvamAI->transliterateText('नमस्ते दुनिया', 'hi-IN', 'en-IN');

        $this->assertInstanceOf(SarvamAIResponse::class, $response);
        $this->assertEquals('namaste duniya', $response->getTransliteratedText());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.sarvam.ai/transliterate' &&
                   $request->header('api-subscription-key') === ['test-api-key'] &&
                   $request->data() === [
                       'input' => 'नमस्ते दुनिया',
                       'source_language_code' => 'hi-IN',
                       'target_language_code' => 'en-IN',
                   ];
        });
    }

    public function test_text_to_speech_makes_correct_request()
    {
        Http::fake([
            'api.sarvam.ai/text-to-speech' => Http::response([
                'audio_url' => 'https://example.com/audio.mp3',
            ], 200),
        ]);

        $sarvamAI = new SarvamAI('test-api-key');
        $response = $sarvamAI->textToSpeech('Hello world', 'en-IN');

        $this->assertInstanceOf(SarvamAIResponse::class, $response);
        $this->assertEquals('https://example.com/audio.mp3', $response->getAudioUrl());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.sarvam.ai/text-to-speech' &&
                   $request->header('api-subscription-key') === ['test-api-key'] &&
                   $request->data() === [
                       'text' => 'Hello world',
                       'target_language_code' => 'en-IN',
                   ];
        });
    }

    public function test_chat_completions_makes_correct_request()
    {
        Http::fake([
            'api.sarvam.ai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Hello! How can I help you?',
                            'role' => 'assistant',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Hello'],
        ];

        $sarvamAI = new SarvamAI('test-api-key');
        $response = $sarvamAI->chatCompletions($messages);

        $this->assertInstanceOf(SarvamAIResponse::class, $response);
        $this->assertEquals('Hello! How can I help you?', $response->getChatCompletionContent());

        Http::assertSent(function ($request) use ($messages) {
            return $request->url() === 'https://api.sarvam.ai/v1/chat/completions' &&
                   $request->header('api-subscription-key') === ['test-api-key'] &&
                   $request->data() === [
                       'messages' => $messages,
                       'model' => 'sarvam-m',
                   ];
        });
    }

    public function test_speech_to_text_throws_exception_for_missing_file()
    {
        $this->expectException(SarvamAIException::class);
        $this->expectExceptionMessage('File not found');

        $sarvamAI = new SarvamAI('test-api-key');
        $sarvamAI->speechToText('/path/to/nonexistent/file.wav');
    }

    public function test_handles_api_errors()
    {
        Http::fake([
            'api.sarvam.ai/translate' => Http::response([
                'error' => 'Invalid API key',
            ], 401),
        ]);

        $this->expectException(SarvamAIException::class);
        $this->expectExceptionMessage('API request failed with status 401');

        $sarvamAI = new SarvamAI('test-api-key');
        $sarvamAI->translateText('Hello world', 'en-IN', 'hi-IN');
    }
}