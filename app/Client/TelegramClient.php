<?php

namespace App\Client;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class TelegramClient
{
    private string $token;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
    }

    public function getUpdates()
    {
        return Http::get(
            config('services.telegram.url') . '/bot'
            . config('services.telegram.token')
            . '/getUpdates'
        )->json();
    }

    public function sendMessage(int $chatId, string $text): bool
    {
        $response = Http::post(
            config('services.telegram.url') . '/bot'
            . config('services.telegram.token')
            . '/sendMessage',
            [
                'chat_id' => $chatId,
                'text' => $text,
            ]
        );

        $data = $response->json();

        if (!$response->successful() || !($data['ok'] ?? false)) {
            throw new RuntimeException(
                'Telegram API error: ' . ($data['description'] ?? 'Unknown error')
            );
        }

        return true;
    }
}
