<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'slug',
        'type',
        'video_url',
        'file_path',
        'content',
        'duration',
        'sort_order'
    ];
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
