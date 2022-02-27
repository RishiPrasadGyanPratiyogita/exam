<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CorrectAnswer extends Model
{
   

    protected $fillable = ['question_id', 'answer_id'];
}
