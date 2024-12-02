<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Services\ChatGPTService;
use App\Services\Interfaces\IQuizService;
use Illuminate\Http\Request;

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

    public function getQuiz($id)
    {
        try {
            $response['data'] = $this->service->getQuiz($id);
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $response['data'] = $this->service->createQuiz($request->all());
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function edit($id)
    {
        try {
            $response['data'] = $this->service->getUserQuizById($id, auth()->user()->id);
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $response['data'] = $this->service->updateQuiz($request->all(), $id);
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function textToQuiz(Request $request)
    {
        try {
            $response['data'] =  $this->chatGPTService->askChatGPT($request->text);
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function getUserQuiz()
    {
        try {
            $response['data'] = $this->service->getUserQuiz(auth()->user()->id);
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function delete($id)
    {
        try {
            $response['data'] = $this->service->deleteQuiz($id);
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }
}
