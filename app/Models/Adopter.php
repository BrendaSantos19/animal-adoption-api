<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Adopter extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'phone'];
    protected $hidden = ['password'];
}

