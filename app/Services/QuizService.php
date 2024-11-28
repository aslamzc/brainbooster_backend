<?php

namespace App\Services;

use App\Http\Resources\QuizResource;
use App\Repositories\Interfaces\IQuizRepository;
use App\Services\Interfaces\IQuizService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizService extends BaseService implements IQuizService
{
    private IQuizRepository $repo;

    public function __construct(IQuizRepository $quizRepository)
    {
        $this->repo = $quizRepository;
    }

    public function getAllQuiz(): Collection
    {
        $quizzes = $this->repo->getAllActiveQuiz();
        abort_unless($quizzes, Response::HTTP_NOT_FOUND, "Quiz not found.");
        return $quizzes;
    }

    public function getQuiz($id): QuizResource
    {
        $quiz = $this->repo->getActiveQuizById($id);
        abort_unless($quiz, Response::HTTP_NOT_FOUND, "Quiz not found.");
        return new QuizResource($quiz);
    }

    public function createQuiz(array $data): QuizResource
    {
        $quizData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'language' => $data['language']['value'],
            'status' => $data['status']['value'],
            'user_id' => Auth::user()->id
        ];
        $quiz = $this->repo->create($quizData);
        abort_unless($quiz, Response::HTTP_NOT_FOUND, "Quiz not found.");

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
        return new QuizResource($quiz);
    }

    public function getUserQuizById(int $id, int $userId): ?QuizResource
    {
        $quiz = $this->repo->getUserQuizById($id, $userId);
        abort_unless($quiz, Response::HTTP_NOT_FOUND, "Quiz not found.");
        return new QuizResource($quiz);
    }

    public function updateQuiz(array $data, int $id): ?QuizResource
    {
        $quiz = $this->repo->updateUserQuizById($data, $id, Auth::user()->id);
        abort_unless($quiz, Response::HTTP_NOT_FOUND, "Quiz not found.");
        return new QuizResource($quiz);
    }

    public function getUserQuiz(int $userId): AnonymousResourceCollection
    {
        $quizzes = $this->repo->getUserQuiz($userId);
        abort_unless($quizzes, Response::HTTP_NOT_FOUND, "Quiz not found.");
        return QuizResource::collection($quizzes);
    }
}
