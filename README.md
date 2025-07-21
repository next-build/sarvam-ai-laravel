# Sarvam AI Laravel Package

A comprehensive Laravel package for integrating [Sarvam](https://www.sarvam.ai/) AI's powerful speech-to-text, text-to-speech, translation, and chat completion capabilities into your Laravel applications.

## Features

- **Speech to Text**: Convert audio files to text
- **Speech to Text with Translation**: Convert audio to text with automatic translation
- **Text to Speech**: Generate audio from text
- **Text Translation**: Translate text between languages
- **Language Identification**: Detect the language of text
- **Text Transliteration**: Convert text between scripts
- **Chat Completions**: AI-powered chat responses
- **Laravel Integration**: Built specifically for Laravel with facades and service providers
- **Error Handling**: Comprehensive error handling with custom exceptions
- **Configuration**: Flexible configuration options

## Installation

Install the package via Composer:

```bash
composer require nextbuild/sarvam-ai-laravel
```

### Laravel Auto-Discovery

The package will automatically register itself with Laravel's package auto-discovery feature.

### Manual Registration (if needed)

If auto-discovery is disabled, manually add the service provider to your `config/app.php`:

```php
'providers' => [
    // Other providers...
    NextBuild\SarvamAI\SarvamAIServiceProvider::class,
],

'aliases' => [
    // Other aliases...
    'SarvamAI' => NextBuild\SarvamAI\Facades\SarvamAI::class,
],
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=sarvam-ai-config
```

Add your Sarvam AI API key to your `.env` file:

```env
SARVAM_AI_API_KEY=your-api-key-here
```

## Usage

### Using the Facade

```php
use NextBuild\SarvamAI\Facades\SarvamAI;

// Speech to Text
$response = SarvamAI::speechToText('/path/to/audio/file.wav', 'saarika:v1', 'hi-IN');
$transcript = $response->getTranscript();

// Text to Speech
$response = SarvamAI::textToSpeech('Hello world', 'en-IN');
$audioUrl = $response->getAudioUrl();

// Translation
$response = SarvamAI::translateText('Hello world', 'en-IN', 'hi-IN');
$translatedText = $response->getTranslatedText();

// Chat Completions
$messages = [
    ['role' => 'user', 'content' => 'Hello, how are you?']
];
$response = SarvamAI::chatCompletions($messages);
$reply = $response->getChatCompletionContent();
```

### Using Dependency Injection

```php
use NextBuild\SarvamAI\SarvamAI;

class YourController extends Controller
{
    protected $sarvamAI;

    public function __construct(SarvamAI $sarvamAI)
    {
        $this->sarvamAI = $sarvamAI;
    }

    public function translateText(Request $request)
    {
        $response = $this->sarvamAI->translateText(
            $request->input('text'),
            $request->input('source_language', 'auto'),
            $request->input('target_language', 'en-IN')
        );

        return response()->json([
            'translated_text' => $response->getTranslatedText(),
            'original_data' => $response->getData()
        ]);
    }
}
```

## API Methods

### Speech to Text

Convert audio files to text:

```php
$response = SarvamAI::speechToText('/path/to/audio/file.wav');
$transcript = $response->getTranscript();
```

### Speech to Text with Translation

Convert audio to text with automatic translation:

```php
$response = SarvamAI::speechToTextTranslate('/path/to/audio/file.wav', null, 'saarika:v1');
$transcript = $response->getTranscript();
```

### Text to Speech

Generate audio from text:

```php
$response = SarvamAI::textToSpeech('Hello world', 'en-IN');
$audioUrl = $response->getAudioUrl();
```

### Text Translation

Translate text between languages:

```php
$response = SarvamAI::translateText('Hello world', 'en-IN', 'hi-IN');
$translatedText = $response->getTranslatedText();
```

### Language Identification

Detect the language of text:

```php
$response = SarvamAI::identifyLanguage('Hello world');
$detectedLanguage = $response->getDetectedLanguage();
```

### Text Transliteration

Convert text between scripts:

```php
$response = SarvamAI::transliterateText('Hello world', 'en-IN', 'hi-IN');
$transliteratedText = $response->getTransliteratedText();
```

### Chat Completions

AI-powered chat responses:

```php
$messages = [
    ['role' => 'user', 'content' => 'What is the capital of India?']
];
$response = SarvamAI::chatCompletions($messages, 'sarvam-m');
$reply = $response->getChatCompletionContent();
```

## Response Handling

All methods return a `SarvamAIResponse` object with the following methods:

```php
$response = SarvamAI::translateText('Hello', 'en-IN', 'hi-IN');

// Get specific data
$translatedText = $response->getTranslatedText();
$statusCode = $response->getStatusCode();
$headers = $response->getHeaders();

// Get raw data
$data = $response->getData();
$body = $response->getBody();

// Convert to array or JSON
$array = $response->toArray();
$json = $response->toJson();

// Check if successful
$isSuccessful = $response->isSuccessful();
```

## Error Handling

The package throws `SarvamAIException` for API errors:

```php
use NextBuild\SarvamAI\Exceptions\SarvamAIException;

try {
    $response = SarvamAI::translateText('Hello world', 'en-IN', 'hi-IN');
    $translatedText = $response->getTranslatedText();
} catch (SarvamAIException $e) {
    // Handle the error
    Log::error('Sarvam AI Error: ' . $e->getMessage());
    return response()->json(['error' => 'Translation failed'], 500);
}
```

## Configuration Options

The configuration file includes the following options:

```php
return [
    'api_key' => env('SARVAM_AI_API_KEY'),
    'default_source_language' => env('SARVAM_AI_DEFAULT_SOURCE_LANGUAGE', 'auto'),
    'default_target_language' => env('SARVAM_AI_DEFAULT_TARGET_LANGUAGE', 'en-IN'),
    'timeout' => env('SARVAM_AI_TIMEOUT', 30),
    'retry' => env('SARVAM_AI_RETRY', 3),
    'chat' => [
        'default_model' => env('SARVAM_AI_DEFAULT_MODEL', 'sarvam-m'),
        'max_tokens' => env('SARVAM_AI_MAX_TOKENS=1000
SARVAM_AI_TEMPERATURE=0.7
```

## Advanced Usage

### Custom API Key

You can set a custom API key at runtime:

```php
SarvamAI::setApiKey('your-custom-api-key')->translateText('Hello', 'en-IN', 'hi-IN');
```

### Custom Timeout

Set a custom timeout for requests:

```php
SarvamAI::setTimeout(60)->speechToText('/path/to/long/audio/file.wav');
```

### Chaining Methods

You can chain configuration methods:

```php
$response = SarvamAI::setApiKey('custom-key')
    ->setTimeout(60)
    ->translateText('Hello world', 'en-IN', 'hi-IN');
```

### Working with Files

For speech-to-text operations, ensure your audio files are in supported formats:

```php
// Supported formats: WAV, MP3, FLAC, etc.
$audioFile = storage_path('app/audio/sample.wav');
$response = SarvamAI::speechToText($audioFile);

if ($response->isSuccessful()) {
    $transcript = $response->getTranscript();
    // Process the transcript
}
```

## Testing

The package includes comprehensive tests. Run them with:

```bash
composer test
```

For test coverage:

```bash
composer test-coverage
```

## Examples

### Complete Translation Service

```php
<?php

namespace App\Services;

use NextBuild\SarvamAI\Facades\SarvamAI;
use NextBuild\SarvamAI\Exceptions\SarvamAIException;

class TranslationService
{
    public function translateContent(string $content, string $targetLanguage = 'hi-IN'): array
    {
        try {
            // First, identify the language
            $langResponse = SarvamAI::identifyLanguage($content);
            $detectedLanguage = $langResponse->getDetectedLanguage();
            
            // Then translate
            $translateResponse = SarvamAI::translateText($content, $detectedLanguage, $targetLanguage);
            
            return [
                'success' => true,
                'original_text' => $content,
                'detected_language' => $detectedLanguage,
                'target_language' => $targetLanguage,
                'translated_text' => $translateResponse->getTranslatedText(),
            ];
        } catch (SarvamAIException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
```

### Audio Processing Service

```php
<?php

namespace App\Services;

use NextBuild\SarvamAI\Facades\SarvamAI;
use NextBuild\SarvamAI\Exceptions\SarvamAIException;
use Illuminate\Http\UploadedFile;

class AudioProcessingService
{
    public function processAudioFile(UploadedFile $audioFile, bool $withTranslation = false): array
    {
        try {
            // Store the uploaded file temporarily
            $path = $audioFile->storeAs('temp', uniqid() . '.' . $audioFile->getClientOriginalExtension());
            $fullPath = storage_path('app/' . $path);
            
            // Process based on requirements
            if ($withTranslation) {
                $response = SarvamAI::speechToTextTranslate($fullPath);
            } else {
                $response = SarvamAI::speechToText($fullPath);
            }
            
            // Clean up temporary file
            unlink($fullPath);
            
            return [
                'success' => true,
                'transcript' => $response->getTranscript(),
                'full_response' => $response->getData(),
            ];
        } catch (SarvamAIException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
```

### Chat Service

```php
<?php

namespace App\Services;

use NextBuild\SarvamAI\Facades\SarvamAI;
use NextBuild\SarvamAI\Exceptions\SarvamAIException;

class ChatService
{
    public function generateResponse(array $conversationHistory, string $userMessage): array
    {
        try {
            // Prepare messages array
            $messages = [];
            
            // Add conversation history
            foreach ($conversationHistory as $message) {
                $messages[] = [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ];
            }
            
            // Add current user message
            $messages[] = [
                'role' => 'user',
                'content' => $userMessage,
            ];
            
            // Get AI response
            $response = SarvamAI::chatCompletions($messages);
            
            return [
                'success' => true,
                'response' => $response->getChatCompletionContent(),
                'full_response' => $response->getData(),
            ];
        } catch (SarvamAIException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
```

## API Reference

### SarvamAI Class Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `speechToText()` | `string $filePath` | `SarvamAIResponse` | Convert audio to text |
| `speechToTextTranslate()` | `string $filePath` | `SarvamAIResponse` | Convert audio to text with translation |
| `textToSpeech()` | `string $text, string $targetLanguageCode` | `SarvamAIResponse` | Convert text to speech |
| `translateText()` | `string $input, string $sourceLanguageCode = 'auto', string $targetLanguageCode = 'en-IN'` | `SarvamAIResponse` | Translate text |
| `identifyLanguage()` | `string $input` | `SarvamAIResponse` | Identify language of text |
| `transliterateText()` | `string $input, string $sourceLanguageCode = 'auto', string $targetLanguageCode = 'en-IN'` | `SarvamAIResponse` | Transliterate text |
| `chatCompletions()` | `array $messages, string $model = 'sarvam-m'` | `SarvamAIResponse` | Get chat completions |
| `setApiKey()` | `string $apiKey` | `SarvamAI` | Set custom API key |
| `setTimeout()` | `int $timeout` | `SarvamAI` | Set request timeout |
| `getApiKey()` | - | `string` | Get current API key |

### SarvamAIResponse Class Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `getData()` | `array` | Get raw response data |
| `getStatusCode()` | `int` | Get HTTP status code |
| `getBody()` | `string` | Get raw response body |
| `getHeaders()` | `array` | Get response headers |
| `isSuccessful()` | `bool` | Check if request was successful |
| `get()` | `mixed` | Get specific field from response |
| `getTranscript()` | `string|null` | Get transcript from speech-to-text |
| `getTranslatedText()` | `string|null` | Get translated text |
| `getDetectedLanguage()` | `string|null` | Get detected language |
| `getTransliteratedText()` | `string|null` | Get transliterated text |
| `getAudioUrl()` | `string|null` | Get audio URL from text-to-speech |
| `getChatCompletion()` | `array|null` | Get chat completion response |
| `getChatCompletionContent()` | `string|null` | Get chat completion content |
| `toArray()` | `array` | Convert response to array |
| `toJson()` | `string` | Convert response to JSON |

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support, please open an issue on the GitHub repository or contact the maintainers.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email the maintainers instead of using the issue tracker.

## Credits

- [NextBuild](https://github.com/next-build)
- [All Contributors](../../contributors)

## About Sarvam AI

Sarvam AI provides cutting-edge AI solutions for Indian languages. Learn more at [sarvam.ai](https://sarvam.ai).TOKENS', 1000),
        'temperature' => env('SARVAM_AI_TEMPERATURE', 0.7),
    ],
];
```

## Supported Languages

The package supports multiple Indian languages:

- Hindi (hi-IN)
- English (en-IN)
- Bengali (bn-IN)
- Tamil (ta-IN)
- Telugu (te-IN)
- Kannada (kn-IN)
- Malayalam (ml-IN)
- Marathi (mr-IN)
- Gujarati (gu-IN)
- Punjabi (pa-IN)
- Odia (or-IN)
- Urdu (ur-IN)
- Assamese (as-IN)
- Nepali (ne-IN)
- Sinhala (si-IN)
- Myanmar (my-IN)

## Environment Variables

```env
SARVAM_AI_API_KEY=your-api-key-here
SARVAM_AI_DEFAULT_SOURCE_LANGUAGE=auto
SARVAM_AI_DEFAULT_TARGET_LANGUAGE=en-IN
SARVAM_AI_TIMEOUT=30
SARVAM_AI_RETRY=3
SARVAM_AI_DEFAULT_MODEL=sarvam-m
SARVAM_AI_MAX_
