<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'user_id',
        'disease_id',
        'confidence',
        'selected_symptoms',
        'category',
    ];

    protected $casts = [
        'selected_symptoms' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
