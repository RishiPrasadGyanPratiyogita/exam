<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FinalResult extends Model
{
    protected $fillable = ['user_id','user_exam_attempted_id','total_mark', 'correct_question','exam_id'];
}
