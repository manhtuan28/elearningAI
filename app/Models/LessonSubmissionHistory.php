<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSubmissionHistory extends Model
{
    use HasFactory;
    protected $fillable = ['lesson_submission_id', 'attempt_number', 'score', 'submission_content', 'submitted_at'];
    
    protected $casts = [
        'submitted_at' => 'datetime',
    ];
}