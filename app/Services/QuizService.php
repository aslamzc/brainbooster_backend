<?php

namespace App\Services;

use App\Repositories\Interfaces\IQuizRepository;
use App\Services\Interfaces\IQuizService;

class QuizService extends BaseService implements IQuizService
{
    private IQuizRepository $repo;

    public function __construct(IQuizRepository $quizRepository)
    {
        $this->repo = $quizRepository;
    }
}
