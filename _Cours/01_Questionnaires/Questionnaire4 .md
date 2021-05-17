Questionnaire 4
===============

1.) Comment se nomme la classe que l'on doit créer pour pouvoir valider un formulaire ?

Request

2.) Quelle est la commande `php artisan`  permettant de créer cette classe ?

php artisan make:request nomRequest

3.) Quelles sont les modifications à apporter dans cette classe pour qu'elle fonctionne correctement

Changer le authorize() => true

4.) Quelles sont les modifications à apporter dans le contrôleur pour que la validation de formulaire prenne effet ?

Il faut modifier le type d'objet de la méthode de Request à nomRequest

5.) Comment se nomme l'objet impliqué lors de la validation de formulaire dans une vue ?

@csrf ? 

6.) Quel est le fichier que l'on doit modifier pour que l'envoi d'email fonctionne dans Laravel ?

.env

7.) Comment se nomme la classe permettant l'envoi d'un email ?

Mail

8.) Comment se nomme la méthode permettant l'envoi d'un email ?

Mail::send(...)

9.) Combien de paramètres nécessite la méthode d'envoi d'email pour pouvoir fonctionner ?

3

10) Décrivez ces paramètres et donnez leur fonctions ?

Mail::send('vue_du_mail', $request->all(), function(...) {...})

Le premier paramètre est la vue qui sera utilisé pour l'affichage du mail

La seconde sont les valeurs transmises à travers la requête

Le dernier créer le "head" de l'e-mail, avec le destinataire et le sujet.