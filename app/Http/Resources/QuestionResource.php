<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "question" => $this->question,
            "answers" => $this->whenLoaded('answer', fn($answer) => $answer->map(fn($answer) => new AnswerResource($answer))),
        ];
    }
}
