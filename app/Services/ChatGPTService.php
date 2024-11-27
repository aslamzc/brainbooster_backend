<?php

namespace App\Services;

use GuzzleHttp\Client;

class ChatGPTService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function askChatGPT($prompt)
    {
        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                "temperature" => 0.7,
                "max_tokens" => 300,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant who creates quiz questions.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Based on the following paragraph, create as many quiz questions as possible. Each question should have four answer choices, with one correct answer. Provide the output as a JSON list in the format: [{\"question\": \"...\", \"choices\": [\"...\", \"...\", \"...\", \"...\"], \"correctAnswer\": <index_of_correct_choice>}]. Here is the paragraph: \"$prompt\""
                    ]
                ],
            ],
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        return $responseBody['choices'][0]['message']['content'] ?? null;
    }
}
