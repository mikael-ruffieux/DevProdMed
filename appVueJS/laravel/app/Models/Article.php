<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    // sans rien indiquer de plus, Laravel rattache automatiquement 
    // ce modèle à la table "articles"
    // Il cherche une table nommée comme la classe mais en rajoutant un 's'
    // => nom de la classe Article => recherche la table "articles" dans la bd
    
    protected $fillable=['titre','contenu','user_id'];  // pour plus tard ;-)
    
    public function user() {			        // NOUVEAU !!!!!!!!!!
        return $this->belongsTo(User::class);            // Relation 1(:N)
    }                                                   // NOUVEAU !!!!!!!!!!
    
    // Relation n:n entre un article et les mots-clés
    public function motcles() {
        return $this->belongsToMany(Motcle::class);
    }
}