<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['name', 'slug'];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
