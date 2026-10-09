<?php

namespace App\Http\Controllers;

use App\Client\TelegramClient;
use App\Models\User;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function __construct(
        private TelegramClient $telegramClient
    ) {}

    public function me(Request $request)
    {
        return $this->telegramClient->getUpdates();
    }

    public function webhook(Request $request)
    {
        $chatId = $request->input('message.chat.id');
        $text = $request->input('message.text');
        $username = $request->input('message.from.username');
        $firstName = $request->input('message.from.first_name', 'пользователь');

        if (! $chatId || ! $text) {
            return response()->json([
                'message' => 'Invalid Telegram message',
            ], 400);
        }

        if ($text === '/start') {
            $this->start($chatId, $username, $firstName);

            return response()->json([
                'message' => 'Start command handled',
            ]);
        }

        return response()->json([
            'message' => 'Command not handled',
        ]);
    }

    private function start(
        int $chatId,
        ?string $username,
        string $firstName
    ): void {
        if (! $username) {
            $this->telegramClient->sendMessage(
                $chatId,
                'Не удалось определить ваш Telegram username.'
            );

            return;
        }

        $user = User::where('telegram_login', $username)->first();

        if (! $user) {
            $this->telegramClient->sendMessage(
                $chatId,
                "Привет, {$firstName}!\n\n"
                ."Это Tasker Bot — помощник для управления задачами.\n\n"
                .'Ваш Telegram-аккаунт пока не привязан к Tasker.'
            );

            return;
        }

        $this->telegramClient->sendMessage(
            $chatId,
            "Привет, {$firstName}!\n\n"
            ."Это Tasker Bot — твой помощник для управления задачами.\n\n"
            ."Ты можешь просто написать мне человеческим языком:\n"
            ."• Завтра сходить в магазин\n"
            ."• Через неделю записаться к врачу\n"
            ."• Удалить задачу 42\n\n"
            .'Я пойму твою команду и передам её в Tasker.'
        );
    }
}
