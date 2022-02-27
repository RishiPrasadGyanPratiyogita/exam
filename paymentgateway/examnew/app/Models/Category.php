<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected $table = 'categoryexam';
    protected $fillable = ['title', 'category_name', 'description'];
}
