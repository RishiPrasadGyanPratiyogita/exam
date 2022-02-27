<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_online';
    protected $fillable = [
        'hallticket',
        'name',
        'parentname',
        'email',
        'mobile',
        'dob',
        'age',
        'qualification',
        'id_proof',
        'address',
        'agegroup',
        'sign',
        'state',
        'district',
        'zone',
        'pincode',
        'city_village',
        'tehsil',
        'country',
        'post',
        'password',
        'success',
        'registermode',
        'created_at',
        'updated_at'



    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
