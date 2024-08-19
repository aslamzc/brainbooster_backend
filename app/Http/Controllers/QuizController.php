<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\IQuizService;

class QuizController extends Controller
{
    private IQuizService $service;

    public function __construct(IQuizService $quizService)
    {
        $this->service = $quizService;
    }

    public function getQuizzes()
    {
        try {
            $response['data'] = $this->service->getAllQuiz();
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }
}
