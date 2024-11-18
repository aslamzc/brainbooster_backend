<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Services\ChatGPTService;
use App\Services\Interfaces\IQuizService;

class QuizController extends Controller
{
    private IQuizService $service;
    private ChatGPTService $chatGPTService;

    public function __construct(IQuizService $quizService, ChatGPTService $chatGPTService)
    {
        $this->service = $quizService;
        $this->chatGPTService = $chatGPTService;
    }

    public function getQuizzes()
    {
        try {
            $response['data'] = $this->service->getAllQuiz();
            $response['message'] = "Success";
            return QuizResource::collection($response['data']);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function test()
    {

        return    $this->chatGPTService->askChatGPT("A waterfall is a natural or artificial feature where water flows over a vertical drop or a series of drops in the course of a stream, river, or other water body. Waterfalls are admired for their beauty, power, and calming effect, making them popular natural landmarks and design inspirations.");
    }
}
