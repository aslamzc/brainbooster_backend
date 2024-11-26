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

    public function getAllActiveQuiz(): ?Collection
    {
        return $this->quiz->where('status', 'active')->get()->load(["user"]);
    }
    public function getActiveQuizById(int $id): ?Quiz
    {
        $quiz = $this->quiz->where('id', $id)->where('status', 'active')->first();
        if ($quiz) $quiz->load(['user', 'question', 'question.answer']);
        return $quiz;
    }

    public function create(array $data): ?Quiz
    {
        return $this->quiz->create($data);
    }

    public function getUserQuizById(int $id, int $userId): ?Quiz
    {
        $quiz = $this->quiz->where('id', $id)->where('user_id', $userId)->first();
        if ($quiz) $quiz->load(['question', 'question.answer']);
        return $quiz;
    }
}
