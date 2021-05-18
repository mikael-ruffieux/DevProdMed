# Partie 2 (60%) Avec doc. et PC

## 1.) (Route)  4 points

Veuillez ajouter le code nécessaire dans une application Laravel pour que celle-ci n'accepte que les `URLs` suivantes :

- localhost:8000/M47

  > Affiche par exemple : M47

- localhost:8000/M48

  > Affiche par exemple : M48

- localhost:8000/M49-1

  > etc...

- localhost:8000/M49-2

- localhost:8000/M49-3

Toute autre `URL` doit afficher l'écran suivant :          404 | NOT FOUND

___________________________________________________________________



## 2.) (Route - Contrôleur - Vue)  12 points

Veuillez ajouter le code nécessaire dans une application Laravel pour avoir les fonctionnalités suivantes :

L'URL : localhost:8000/quizz doit afficher le formulaire suivant :

![browser1](\img\browser1.png)

On ne doit pas pouvoir quitter le formulaire tant que TOUS les champs n'ont pas été remplis 
(sinon doit voir des messages d'erreur)

Les contraintes suivantes doivent (en plus) être effectives :

- Le prénom doit contenir au moins deux lettres
- L'âge doit être compris entre 1 et 128

![browser2](img\browser2.png)

Une fois tous les champs remplis correctement :

![browser3](img\browser3.png)

La page suivante s'affiche :

![browser4](img\browser4.png)	

Pour vous aider, voici une base pour le formulaire :

```html
<!DOCTYPE html>
<html>
    <body>
        <form action="{{url('traiteQuizz')}}" method="post" accept-charset="UTF-8">
            @csrf
            <div>
                <label for="Prenom">Prenom:</label><br>
                <input type="text" id="Prenom" name="prenom"><br>
                <label for="Age">Age (Entre 1 and 128):</label><br>
                <input type="number" id="Age" name="age" min="1" max="128"><br>
            </div>
            <br>
            <div>
                <label for="Question1">Choisi les languages que tu connais:</label>
                <select id="Question1" name="q1[]" multiple>
                    <option value="r1-1" >Java</option>
                    <option value="r1-2" >C#</option>
                    <option value="r1-3" >PHP</option>
                    <option value="r1-4" >Javascript</option>
                </select>
                <br>
            </div>
            <br>

            <div role="radiogroup" aria-labelledby="genre_label">
                <p id="genre_label">Quel est ton genre ?</p>
                <label><input type="radio" name="genre" value="Homme" > Homme</label>
                <label><input type="radio" name="genre" value="Femme" > Femme</label>
                <label><input type="radio" name="genre" value="Autre" > Autre</label>
            </div>
            <br>
            
            <div role="checkbox" aria-labelledby="preferences_label">
                <p id="preferences_label">Qu'aimes-tu faire ?</p>
                <label><input type="checkbox" name="preferences[]" value="Concevoir"> Concevoir</label>
                <label><input type="checkbox" name="preferences[]" value="Implementer"> Implementer</label>
                <label><input type="checkbox" name="preferences[]" value="Tester"> Tester</label>
            </div>    
        
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </body>
</html>
```

> ## Bonus : 4 points (Conseil : Faites la partie 3 avant)
>
> Veuillez faire en sorte que lorsque le formulaire n'a pas été saisi correctement, les valeurs précédemment entrée ne soient pas perdues.
>
> ![browserBonus](img\browserBonus.png)

_____________________________________________________________________



## 3.) (Base de données - Eloquent) 8 points

Veuillez ajouter : 

- Les migrations
- Les seeders
- Les classes-modèles

permettant à des personnes de participer à un ou plusieurs projets.

Une personne peut participer à plusieurs projets.
Plusieurs personnes peuvent participer à un même projet.

Un projet possède les champs suivant :

- identifiant
- nom
- description
- terminé (oui ou non)

Les trois projets suivants doivent être dans la base de données :

![database1](img\database1.png)

Une personne possède les champs suivants : 

- identifiant
- nom
- email
- mot de passe

Les trois personnes suivantes doivent être dans la base de données :

![database1](img\database2.png)

Les relations suivantes doivent aussi figurer dans la base de données :

Jacques travaille sur les projets :

- Projet parc éolien
- Projet gaz

Orianne travaille sur les projets :

- Projet gaz
- Projet `petroleum`

Dominique travaille sur le projet :

- Projet parc éolien

Veuillez écrire les requêtes permettant :

- D'obtenir la liste de tous les projets

```php
App\Models\Projet::all()
```

- D'obtenir la liste des utilisateurs avec les projets sur lesquels ils travaillent

Pour un utilisateur en particulier :

```php
App\Models\Personne::findOrFail(1)->projets()->get()
```

Pour tous les utilisateurs : 

```php
foreach (App\Models\Personne::all() as $personne) {
  echo $personne->projets()->get();
}
```

- D'obtenir la liste des projets terminés avec leur participants

```php
foreach (App\Models\Projet::where('termine', 1)->get() as $projetTermine) {
  echo $projetTermine->personnes;
}
```

