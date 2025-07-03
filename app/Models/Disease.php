<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'recommendation',
        'code'
    ];

    public function animals()
    {
        return $this->belongsToMany(Animal::class, 'animal_disease');
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'rules');
    }
}

