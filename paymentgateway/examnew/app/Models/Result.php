<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    protected $fillable = ['user_id', 'question_id', 'user_exam_attempted_id', 'answer_id', 'exam_id'];

    public function answers()
    {
        return $this->hasOne(Questionwithanswer::class,'id','answer_id');
    }
}
