<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'species', 'breed', 'age', 'size', 'status'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}

