<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin  extends Authenticatable
{
   


    protected $table = 'admin';
    protected $fillable = ['name', 'email', 'mobile','password'];
}
