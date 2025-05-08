<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by'
    ];

    public function interns()
    {
        return $this->belongsToMany(User::class, 'intern_task');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
