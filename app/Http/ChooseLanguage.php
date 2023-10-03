<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ChooseLanguage
{
    public function ChooseLang($telegram, $chatId)
    {
        $keyboard = [
            [
                ['text' => 'English 🇺🇸', 'callback_data' => 'lang_en'],
                ['text' => 'Russian 🇷🇺', 'callback_data' => 'lang_ru'],
            ]
        ];

        $keyboardJson = json_encode(['inline_keyboard' => $keyboard, 'resize_keyboard' => true]);
        $response = "Выберите язык 🇺🇸 🇷🇺 $chatId";
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $response,
            'reply_markup' => $keyboardJson,
        ]);
    }
}
