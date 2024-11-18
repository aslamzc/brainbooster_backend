<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
