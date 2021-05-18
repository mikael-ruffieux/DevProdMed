<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'termine'
    ];

    public function personnes() {
        return $this->belongsToMany(Personne::class);
    }
}