<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGPTController extends Controller
{
    protected $httpClient;

    public function __construct () {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function askToChatGpt () {

        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'max_tokens' => 50,
            'prompt' => "Please translate this text to russian 'hello my name is Ruben how are you?'",
        ]);

        echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.



    }
}
