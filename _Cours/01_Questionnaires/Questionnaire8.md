Questionnaire 8
---------------

> Remarque : Merci de m'envoyer les réponses du questionnaire dans une conversation "privée" dans Teams
> 					(Ne pas glisser-déposer de fichier, mais juste tout sélectionner le texte et le copier.)

01.)	Qu'est ce qu'une relation de type `n:n` dans le domaine de la base de donnée ? (Veuillez donner un exemple)

Une relation n-n est une relation qui implique qu'un objet A peut avoir 0, 1 ou N objets B, et l'objet B peut appartenir à 0, 1 ou N objets A *(versus 1-n, où l'objet B n'appartient qu'à un seul objet A)*. 

Par un exemple, dans un portfolio, un projet peut appartenir à 0, 1 ou N catégories de projets, et les catégories de projets définissent 0, 1 ou N projets.

02.)	Combien de tables sont impliquées dans une relation `n-n` ?

3 : les 2 tables principales, et la table pivot.

03.)	Comment doit se nommer la table pivot dans Laravel ?

Les noms des 2 objets liés, au singulier, par ordre alphabétique. Par exemple : CategorieProjet.

04.)	Dans les classes modèles `Personne` et `Entreprise` comment définit-on la relation `n-n` ?

Dans la classe modèle `Personne`, on ajoute une méthode `entreprises()`, dans laquelle on retourne : 

```php
return $this->belongsToMany(Entreprise::class);
```

... ce qui indique qu'une personne peut "appartenir" à plusieurs entreprises.

Et on fait la même chose dans la classe modèle `Entreprise`, dans une méthode `personnes()`. 

05.)	Dans l'exemple (ProgrammeJour8) qui implémente une relation `n-n`, 
			quelle méthode du contrôleur permet de mettre à jour la table pivot ?

La méthode `store(ArticleRequest $request)` de `ArticleController`.

06.)	Dans la méthode qui met à jour la table pivot (question 4), 
			quelle instruction met à jour la table pivot ?

```php
$mot_ref = Motcle::where('mot_url', $mot_url)->first();

if (is_null($mot_ref)) {
  $mot_ref = new Motcle([
    'mot' => $motcle,
    'mot_url' => $mot_url
  ]);
  $article->motcles()->save($mot_ref);
} else {
  $article->motcles()->attach($mot_ref->id);
}
```

