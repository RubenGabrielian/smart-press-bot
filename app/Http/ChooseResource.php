<?php

namespace App\Http;

use App\Http\Controllers\Controller;

class ChooseResource
{
    public function ChooseResource($telegram, $chatId, $language)
    {
        $response = "Вы выбрали **Digital** Trends ресурс 🎉";

        $keyboardEng = [
            [
                ['text' => 'Create Esse', 'callback_data' => "gpt_create_esse_{$language}"],
                ['text' => 'Highlight bullet points', 'callback_data' => "rew_highlight_{$language}"],
            ],
            [
                ['text' => 'Translate', 'callback_data' => "rew_translate_{$language}"],
            ]
        ];

        $keyboardRus = [
            [
                ['text' => 'Создать Эссе', 'callback_data' => "rew_create_esse_{$language}"],
                ['text' => 'Выделите пункты списка', 'callback_data' =>"rew_highlight_{$language}"],
            ],
            [
                ['text' => 'Переводить', 'callback_data' => "rew_translate_{$language}"],
            ]
        ];

        // Convert the keyboard to JSON
        $keyboardJson = json_encode(['inline_keyboard' => $language == "en" ? $keyboardEng : $keyboardRus, 'resize_keyboard' => true]);

        for ($i = 0; $i < 3; $i++) {
            if ($i === 0) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'https://www.digitaltrends.com/cars/cybertruck-hubcap-flies-through-air/',
                    'reply_markup' => $keyboardJson
                ]);


            } else if ($i === 1) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'https://www.digitaltrends.com/space/nasa-frank-rubio-sets-space-record-aboard-the-iss/',
                    'reply_markup' => $keyboardJson
                ]);
            } else {
                $keyboardEng = [
                    [
                        ['text' => 'Start', 'callback_data' => 'start'],
                        ['text' => 'Load More', 'callback_data' => 'load_more'],

                    ],
                ];

                $keyboardRus = [
                    [
                        ['text' => 'Начинать', 'callback_data' => 'start'],
                        ['text' => 'Загрузи больше', 'callback_data' => 'load_more'],

                    ],
                ];

                // Convert the keyboard to JSON
                $keyboardJson = json_encode(['inline_keyboard' => $language == "en" ? $keyboardEng : $keyboardRus, 'resize_keyboard' => true]);
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $language == "en" ? "Please select one" : "Пожалуйста выберите",
                    'reply_markup' => $keyboardJson
                ]);
            }
        }
    }
}
