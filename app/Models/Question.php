<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class)->where('status', 'active')->orderBy('order');
    }
}
