<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motcle extends Model
{
    use HasFactory;

    protected $fillable=['mot','mot_url'];
    
    public function articles() {
        return $this->belongsToMany(Article::class); // chaque mot-clé peut être référencé par
                                                     // plusieurs articles
    }
}
