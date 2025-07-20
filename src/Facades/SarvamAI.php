<?php

namespace NextBuild\SarvamAI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse speechToText(string $filePath)
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse speechToTextTranslate(string $filePath)
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse textToSpeech(string $text, string $targetLanguageCode)
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse translateText(string $input, string $sourceLanguageCode = 'auto', string $targetLanguageCode = 'en-IN')
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse identifyLanguage(string $input)
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse transliterateText(string $input, string $sourceLanguageCode = 'auto', string $targetLanguageCode = 'en-IN')
 * @method static \NextBuild\SarvamAI\Responses\SarvamAIResponse chatCompletions(array $messages, string $model = 'sarvam-m')
 * @method static \NextBuild\SarvamAI\SarvamAI setApiKey(string $apiKey)
 * @method static \NextBuild\SarvamAI\SarvamAI setTimeout(int $timeout)
 * @method static string getApiKey()
 */
class SarvamAI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sarvam-ai';
    }
}