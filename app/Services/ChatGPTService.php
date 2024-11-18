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
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => 'Here is a paragraph: ' . $prompt],
                    ['role' => 'user', 'content' => 'Create five questions and their answers based on the paragraph. Provide the result as an json list of question-answer pairs.']
                ],
            ],
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        return $responseBody['choices'][0]['message']['content'] ?? null;
    }
}
