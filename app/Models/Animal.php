<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = ['name'];

    protected $perPage = 5;

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'animal_disease');
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'animal_symptom');
    }
}
