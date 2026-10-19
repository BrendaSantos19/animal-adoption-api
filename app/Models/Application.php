<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'animal_id', 'adopter_id', 'status', 'rejection_reason'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function adopter()
    {
        return $this->belongsTo(Adopter::class);
    }
}

