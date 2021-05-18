<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Personne extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'motdepasse'
    ];

    public function setPasswordAttribute($password) {
        $this->attributes['password'] = Hash::make($password);
    }

    public function projets() {
        return $this->belongsToMany(Projet::class);
    }
}
