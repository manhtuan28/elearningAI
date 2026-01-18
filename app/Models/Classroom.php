<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'code', 'major_id'];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function students()
    {
        return $this->hasMany(User::class);
    }
}
