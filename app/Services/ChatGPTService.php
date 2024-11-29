<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Response;

class ChatGPTService
{
    protected $client;
    protected $apiKey;

    private $correctAnswerOptions = [
        ['value' => 0, 'label' => 'Answer 1'],
        ['value' => 1, 'label' => 'Answer 2'],
        ['value' => 2, 'label' => 'Answer 3'],
        ['value' => 3, 'label' => 'Answer 4']
    ];

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
                        'content' => "Based on the following paragraph or regarding text, create as many quiz questions as possible. Each question should have four answer choices, with one correct answer. Provide the output as a JSON list in the format: [{\"question\": \"...\", \"choices\": [\"...\", \"...\", \"...\", \"...\"], \"correctAnswer\": <index_of_correct_choice>}]. Here is the paragraph: \"$prompt\""
                    ]
                ],
            ],
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        $data = $responseBody['choices'][0]['message']['content'] ?? null;
        if ($data) $data = json_decode($data);
        abort_unless($data, Response::HTTP_UNPROCESSABLE_ENTITY, "Unable to generate questions. Please try again.");
        return $this->formatData($data);
    }

    private function formatData($data): array
    {
        $newData = [];
        if ($data) {
            foreach ($data as  $value) {
                $newData[] = [
                    'question' => $value->question,
                    'answer' => $value->choices,
                    'correctAnswer' => $this->correctAnswerOptions[$value->correctAnswer]
                ];
            }
        }
        return $newData;
    }
}
