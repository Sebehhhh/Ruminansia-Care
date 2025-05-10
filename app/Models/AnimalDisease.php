<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalDisease extends Model
{
    protected $table = 'animal_disease';
    protected $fillable = [
        'animal_id',
        'disease_id',
        // 'notes',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}