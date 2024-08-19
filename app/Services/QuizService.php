<?php

namespace App\Services;

use App\Repositories\Interfaces\IQuizRepository;
use App\Services\Interfaces\IQuizService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

class QuizService extends BaseService implements IQuizService
{
    private IQuizRepository $repo;

    public function __construct(IQuizRepository $quizRepository)
    {
        $this->repo = $quizRepository;
    }

    public function getAllQuiz(): Collection
    {
        $quizzes = $this->repo->getAllQuiz();
        abort_unless($quizzes, Response::HTTP_NOT_FOUND, "Quiz not found.");
        return $quizzes;
    }
}
