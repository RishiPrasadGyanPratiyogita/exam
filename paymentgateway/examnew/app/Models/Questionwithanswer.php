<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Questionwithanswer extends Model
{
   
    protected $table = 'questionwithanswer';
    protected $fillable = ['questionid', 'answer'];
}
