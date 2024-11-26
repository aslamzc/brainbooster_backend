<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'status',
        'order'
    ];

    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class)->orderBy('order');
    }
}
