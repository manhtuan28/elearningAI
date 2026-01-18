<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'submission_content',
        'score',
        'status',
        'attempt_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function histories()
    {
        return $this->hasMany(LessonSubmissionHistory::class)->orderBy('attempt_number', 'desc');
    }
}
