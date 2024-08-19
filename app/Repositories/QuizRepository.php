<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Repositories\Interfaces\IQuizRepository;
use Illuminate\Database\Eloquent\Collection;

class QuizRepository extends BaseRepository implements IQuizRepository
{
    private Quiz $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function getAllQuiz(): ?Collection
    {
        return $this->quiz->all();
    }
}
