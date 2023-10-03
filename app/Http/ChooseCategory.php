<?php

namespace App\Http;

use App\Http\Controllers\Controller;

class ChooseCategory
{
    public function ChooseCategory($telegram, $chatId, $language)
    {
        $responseRus = "Вы выбрали Инженерное категорию {$language} 🎉, Теперь выберите ресурс ";
        $responseEng = "You are choose Engineering category 🎉, now choose resource ";

        $keyboard = [
            [
                ['text' => 'Digital Trends', 'callback_data' => 'res_digitaltrends'],
                ['text' => 'The Verge', 'callback_data' => 'res_theverge'],
                ['text' => 'Tech Crunch', 'callback_data' => 'res_techcrunch'],
            ],
        ];


        // Convert the keyboard to JSON
        $keyboardJson = json_encode(['inline_keyboard' => $keyboard, 'resize_keyboard' => true]);

        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $language === "eng" ? $responseEng : $responseRus,
            'reply_markup' => $keyboardJson
        ]);

    }
}
