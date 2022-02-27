<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserExamAttempted extends Model
{

    protected $fillable = ['user_id','competition_id','exam_id', 'status'];
}
