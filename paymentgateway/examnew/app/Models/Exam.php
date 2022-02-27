<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{


    protected $table = 'exam';
    protected $fillable = ['title', 'competition_id','category','agegroup','language','duration','noquestion','passmarks','totalmarks','status','type','cost','fromdate','todate','instruction'];
}
