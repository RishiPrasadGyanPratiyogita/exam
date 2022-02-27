<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{


    protected $table = 'competition';
    protected $fillable = ['type', 'phase','institution_name','description','from_date','end_date','status'];
}
