<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['board_id', 'title', 'description', 'status', 'order', 'author_user_id', 'executor_user_id'];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_user_id');
    }

    public function executor()
    {
        return $this->belongsTo('App\Models\User', 'executor_user_id');
    }
}
