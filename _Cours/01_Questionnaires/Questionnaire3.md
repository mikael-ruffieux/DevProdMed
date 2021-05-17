Questionnaire 3
---------------
**1.) En quoi consiste une attaque de type `CSRF` ?**

[Explications détaillées](https://fr.wikipedia.org/wiki/Cross-site_request_forgery)

**2.) Que doit-on mettre dans un formulaire pour se protéger contre les attaques de type `CSRF` ?**

À la création du formulaire dans le template blade, on ajoute `@crsf` après la balise `<form>`.

**3.) Comment fonctionne cette protection ?**

Le moteur Blade "injecte" dans le formulaire un champ caché contenant un jeton (`token`) dont la valeur est une chaîne de caractère aléatoire unique.

Une fois que les données du formulaires sont envoyées au serveur, celui-ci peut contrôler que le champ caché contient bien le jeton qu'il a injecté précédemment.

**4.) Quelle différence y-a-t'il entre une route commençant par :**

   - `Route::get(...)`

     et

   - `Route::post(...)`

La méthode `GET` permet de récupérer les informations contenues dans l'URL du navigateur. La méthode `POST` permet de récupérer les informations provenant d'un formulaire.

**5.) Comment se nomme l'objet permettant de récupérer les données d'un formulaire ?**

Request

**6.) Quelle instruction permet de récupérer la valeur du champ `"prenom"` d'un formulaire ?**

`$request->input('prénom')`

**7.) Peut-on récupérer les informations d'un formulaire dans une route ?**
		(Si oui, comment ?, Si non, pourquoi ?)

Oui, c'est possible. Il suffit d'utiliser un paramètre de type `Request` pour récupérer la requête (données des champs du formulaire) de l'utilisateur. 

Exemple :

```php
use Illuminate\Http\Request;  // ne pas oublier d'indiquer où est la classe Request !

Route::post('traiteFormulaire', function (Request $request) {
	dd($request->all());      // P.S. On voit notre jeton :-) (token)
});
```