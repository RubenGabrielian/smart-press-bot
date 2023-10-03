<?php

namespace App\Http\Controllers;

use App\Http\ChooseCategory;
use App\Http\ChooseLanguage;
use App\Http\ChooseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\ConstantCategories;

class TelegramController extends Controller
{

    public $language = 'grdon';

    public function chooseCategory($telegram, $chatId, $language)
    {

        $keyboardEng = [
            [
                ['text' => "STOCKS", 'callback_data' => "cat_stocks_{$language}"],
                ['text' => "MARKETS", 'callback_data' => "cat_markets_{$language}"],
                ['text' => "ECONOMICS", 'callback_data' => "cat_economics_{$language}"],
                ['text' => "COMMODITIES", 'callback_data' => "cat_commodities_{$language}"],
            ],
            [
                ['text' => "TECH", 'callback_data' => "cat_tech_{$language}"],
                ['text' => "CRYPTO", 'callback_data' => "cat_crypto_{$language}"],
                ['text' => "AI", 'callback_data' => "cat_ai_{$language}"],
                ['text' => "ECOLOGY", 'callback_data' => "cat_ecology_{$language}"]
            ],
            [
                ['text' => "SCIENCE", 'callback_data' => "cat_science_{$language}"],
                ['text' => "AUTO", 'callback_data' => "cat_auto_{$language}"],
                ['text' => "FASHION", 'callback_data' => "cat_fashion_{$language}"],
                ['text' => "HEALTH", 'callback_data' => "cat_health_{$language}"]
            ],
            [
                ['text' => "WELLNESS", 'callback_data' => "cat_wellness_{$language}"],
                ['text' => "SPORTS", 'callback_data' => "cat_sports_{$language}"],
                ['text' => "LIFESTYLE", 'callback_data' => "cat_lifestyle_{$language}"],
            ],
        ];

        $keyboardJson = json_encode(['inline_keyboard' => $keyboardEng, 'resize_keyboard' => true]);

        // Build the response with the inline keyboard
        $responseEng = 'Choose your category 🧐';
        $responseRus = 'Выберите свою категорию 🧐';
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $this->language === "en" ? $responseEng : $responseRus,
            'reply_markup' => $keyboardJson,
        ]);
    }

    public function webhook(Request $request)
    {
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $response = $telegram->getMe();
        $update = $telegram->getWebhookUpdate();
        if ($update->getMessage()) {
            $conversationState = 'initial_state';
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            $user = $message->getChat()->getFirstName();

            // Handle different commands or messages
            if ($text === '/start') {
                $chooseLang = new ChooseLanguage();
                $chooseLang->ChooseLang($telegram, $chatId);
            }
            elseif ($update->getCallbackQuery()) {
                // Handle button click events
                $callbackQuery = $update->getCallbackQuery();
                $chatId = $callbackQuery->getMessage()->getChat()->getId();
                $data = $callbackQuery->getData();
                // Respond based on the button clicked
                $exploded_data = explode('_', $data);
                $resourceMultiData = explode(':', $data);
                if ($exploded_data[0] === "cat") {
                    $options = [
                        ["Option 1", "Option 2"],
                        ["Option 3", "Option 4"],
                        // Add more options as needed
                    ];

                    $keyboard = [
                        'inline_keyboard' => [],
                    ];

                    foreach ($options as $row) {
                        $keyboardRow = [];
                        foreach ($row as $option) {
                            $keyboardRow[] = [
                                'text' => $option,
                                'callback_data' => 'select_option:' . $option,
                            ];
                        }
                        $keyboard['inline_keyboard'][] = $keyboardRow;
                    }
                    $encodedKeyboard = json_encode($keyboard);
                    $selectedLanguage = $exploded_data[2];
                    $responseRus = "Вы выбрали Инженерное категорию  🎉, Теперь выберите ресурс ";
                    $responseEng = "You are choose Engineering category 🎉, now choose resource ";
                    $keyboardEng = [
                        [
                            ['text' => "Digital Trends", 'callback_data' => "multi_res_digitaltrends_{$selectedLanguage}"],
                            ['text' => 'The Verge', 'callback_data' => "multi_res_theverge_{$selectedLanguage}"],
                            ['text' => 'Tech Crunch', 'callback_data' => "multi_res_techcrunch_{$selectedLanguage}"],
                            ['text' => 'Other', 'callback_data' => "multi_res_other_{$selectedLanguage}"],
                        ],
                        [
                            ['text' => 'Finish Selecting ✅', 'callback_data' => "multi_finish_{$selectedLanguage}"],
                        ]
                    ];
                    $keyboardRus = [
                        [
                            ['text' => 'Digital Rus', 'callback_data' => "multi_res_digitaltrends_{$selectedLanguage}"],
                            ['text' => 'The Rus', 'callback_data' => "multi_res_theverge_{$selectedLanguage}"],
                            ['text' => 'Tech Rus', 'callback_data' => "multi_res_techcrunch_{$selectedLanguage}"],
                            ['text' => 'Другой', 'callback_data' => "multi_res_other_{$selectedLanguage}"],
                        ],
                        [
                            ['text' => 'Finish Selecting ✅', 'callback_data' => "multi_finish_{$selectedLanguage}"],
                        ]
                    ];
                    $keyboardJson = json_encode(['inline_keyboard' => $selectedLanguage == "ru" ? $keyboardRus : $keyboardEng, 'resize_keyboard' => true]);
                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $selectedLanguage == "en" ? $responseEng : $responseRus,
                        'reply_markup' => $keyboardJson
                    ]);
                }  if ($exploded_data[0] === "multi") {
                    $selectedOption = $exploded_data[2];
                    if ($exploded_data[1] == "finish") {
                        $chooseResource = new ChooseResource();
                        $chooseResource->ChooseResource($telegram, $chatId, $exploded_data[2]);
                    } else {
                        $customCategoryEngText = "Please write your resource";
                        $customCategoryRusText = "Пожалуйста, напишите свой ресурс";
                        if ($selectedOption == "other") {
                            $telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => $exploded_data[3] == "eng" ? $customCategoryEngText : $customCategoryRusText,
                                'context' => 'asking_category',
                            ]);
                        } else {
                            $telegram->sendMessage([
                                'chat_id' => $chatId,
                                'text' => "Your choice {$selectedOption}",
                                'context' => 'asking_category',
                            ]);
                        }
                    }
                } else if ($exploded_data[0] === "rew") {
                    if ($exploded_data[1] == "rewrite") {
                        $telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => 'Apple spent some time focusing on games during Tuesday’s iPhone 15 launch event, earn Apple a place as a must-target destination for big budget, major studio game releases. The A17 Pro that’s powering the iPhone 15 Pro and Pro Max is at least on par with the kinds of processors that are powering devices like the Steam Deck, Asus ROG Ally, Lenovo Legion Go and many other portable form factor console PCs hitting the market and in development. And paired with devices like the Backbone One USB-C controller, which already announced support for the iPhone 15 immediately following its launch, the iPhone 15 Pro stands poised to make standalone gaming hardware redundant.',
                        ]);
                    }
                } else if ($exploded_data[0] === "lang") {
                    $this->language = $exploded_data[1];
                    $this->chooseCategory($telegram, $chatId, $exploded_data[1]);
                } else if ($exploded_data[0] === "start") {
                    $chooseLang = new ChooseLanguage();
                    $chooseLang->ChooseLang($telegram, $chatId);
                }

            }
            else  {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Thank you for providing your custom resource',
                ]);
            }

            return response()->json(['status' => 'ok']);
        }
    }


    public function user()
    {
        $result = Http::post("https://onex.am/extension/getusers", ['userid', '3'])->json();
        return $result;
    }

}
