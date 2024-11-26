<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "language" => $this->language,
            "status" => $this->status,
            "userName" => $this->whenLoaded('user', fn($user) => $user->name) ?? "",
            "createdAt" => $this->created_at,
            "questions" => $this->whenLoaded('question', fn($question) => $question->map(fn($question) => new QuestionResource($question))),
        ];
    }
}
