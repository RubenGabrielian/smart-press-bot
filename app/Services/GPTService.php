<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class GPTService
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }




    public function doSomething($telegram, $chatId)
    {
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Please wait...",
        ]);

        $message = "Please translate this text to Russian: hello, my name is Ruben, how are you?";
        $apiUrl = 'https://api.openai.com/v1/engines/gpt-3.5-turbo/completions';

        $responsePromise = Http::async()->post($apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer YOUR_API_KEY',
            ],
            'json' => [
                'max_tokens' => 50,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are'],
                    ['role' => 'user', 'content' => $message],
                ],
            ],
        ]);


        $responsePromise->then(function ($response) use ($telegram, $chatId) {
            // Check if the response was successful
            if ($response->successful()) {
                $translatedText = $response->json('choices.0.text');
                // Send the translated text to the user
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $translatedText,
                ]);
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'chexav',
                ]);
            }
        },
            function ($exception) use ($telegram, $chatId) {
                // Handle exceptions
                \Log::error('Exception: ' . $exception->getMessage());
                // Send an error message to the user or take appropriate action
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'An unexpected error occurred while processing your request.',
                ]);
            }
        );
    }
}
