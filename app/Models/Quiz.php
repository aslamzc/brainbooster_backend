<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'language',
        "status"
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): HasMany
    {
        return $this->hasMany(Question::class)->where('status', 'active')->orderBy('order');
    }
}
