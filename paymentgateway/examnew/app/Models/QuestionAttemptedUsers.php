<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class QuestionAttemptedUsers extends Model
{
  

    protected $table = 'question_attempted_users';
    protected $fillable = ['user_id', 'question_id'];
}
