<?php

namespace App\Client;

use Illuminate\Support\Facades\Http;


class OllamaClient
{
    public function interpret(string $text, ?float $confidence, string $system_message): string
    {
        $response = Http::timeout(50)->post('http://ollama:11434/api/chat',
        [
            "model" => "qwen3:4b",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $system_message,
                ],
                [
                    "role" => "user",
                    "content" => "text: " . $text . "confidence: " . ($confidence ?? 0),
                ],
            ],
            "format" => "json",
            "think"  => false,
            "stream" => false,
        ]);
        return $response->json('message.content');
    }
}
