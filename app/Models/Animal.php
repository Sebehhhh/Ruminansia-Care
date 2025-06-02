<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rule;

class Animal extends Model
{
    protected $fillable = ['name'];

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'animal_symptom');
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'animal_disease');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
