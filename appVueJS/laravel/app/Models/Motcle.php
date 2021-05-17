<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motcle extends Model
{
    use HasFactory;
    
    // sans rien indiquer de plus, Laravel rattache automatiquement 
    // ce modèle à la table "articles"
    // Il cherche une table nommée comme la classe mais en rajoutant un 's'
    // => nom de la classe Article => recherche la table "articles" dans la bd
    
    protected $fillable=['mot','mot_url'];
    
    public function articles() {
        return $this->belongsToMany(Article::class); // chaque mot-clé peut être référencé par
                                                     // plusieurs articles
    }
}