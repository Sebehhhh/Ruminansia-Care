<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalSymptom extends Model
{
    protected $fillable = [
        'animal_id',
        'symptom_id',
        'notes',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function symptom()
    {
        return $this->belongsTo(Symptom::class);
    }
}