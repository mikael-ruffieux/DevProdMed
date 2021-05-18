<!DOCTYPE html>
<html>
    <body>
        Ton prénom est {{$results['prenom']}}.<br>
        Tu as {{$results['age']}} ans.<br>
        Tu connais <?php echo sizeof($results['q1']);?> language(s) de programmation.<br>
        Ton genre : {{$results['genre']}}.<br>
        Tes préférences :<br>
        @foreach ($results['preferences'] as $preference)
            - {{$preference}} <br>
        @endforeach
    </body>
</html>