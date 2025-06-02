<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function animals()
    {
        return $this->belongsToMany(Animal::class, 'animal_symptom');
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'rules');
    }
}
