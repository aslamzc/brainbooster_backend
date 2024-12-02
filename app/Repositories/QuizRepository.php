<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Repositories\Interfaces\IQuizRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

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

    public function updateUserQuizById(array $data, int $id, int $userId): ?Quiz
    {
        $quizData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'language' => $data['language']['value'],
            'status' => $data['status']['value']
        ];

        $quiz = $this->quiz->where('id', $id)->where('user_id', $userId)->first();
        abort_unless($quiz, Response::HTTP_NOT_FOUND, "Quiz not found.");

        $quiz->update($quizData);
        $quiz->question()->delete();

        $questionData = [];
        foreach ($data['questions'] as $key => $question) {
            $questionData[] = [
                'question' => $question['question'],
                'status' => 'active',
                'order' => $key
            ];
        }
        $questions = $quiz->question()->createMany($questionData);
        abort_unless($question, Response::HTTP_NOT_FOUND, "Question not found.");

        foreach ($questions as $key => $question) {
            $answer = $data['questions'][$key];
            $answerData = [];
            foreach ($answer['answer'] as $key2 =>  $value) {
                $answerData[] = [
                    'answer' => $value,
                    'is_correct' => $answer['correctAnswer']['value'] == $key2 ? 1 : 0,
                    'status' => 'active',
                    'order' => $key2
                ];
            }
            $answers = $question->answer()->createMany($answerData);
            abort_unless($answers, Response::HTTP_NOT_FOUND, "Answer not found.");
        }

        return $quiz;
    }

    public function getUserQuiz(int $userId): ?Collection
    {
        return $this->quiz->where('user_id', $userId)->get();
    }

    public function delete(int $id): bool
    {
        return $this->quiz->where('id', $id)->delete();
    }
}
