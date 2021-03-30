@extends('template_form')

@section('contenu')
Veuillez choisir les personnes concernées par les entrevues :
<form action="{{ url('agenda') }}" method="post" accept-charset="UTF-8">
    @csrf

    @for ($i = 0; $i < sizeof($etudiants); $i++)
        <input type="checkbox" name="etudiants[{{$i}}]" value="{{$etudiants[$i]}}">
        <label for="etudiants[{{$i}}]">{{$etudiants[$i]}}</label><br/>
    @endfor

    <label for="heureDebut"><b>Entrez une heure de début :</b></label>
    <input type="time" name="heureDebut" value="08:30"><br/>

    <label for="heureFin"><b>Entrez une heure de fin :</b></label>
    <input type="time" name="heureFin" value="12:00"><br/>

    <label for="tempsPause"><b>Entrez un temps de pause :</b></label>
    <input type="time" name="tempsPause" value="00:05"><br/>

    <input type="submit" name="submit" value="Envoyer"/>    
</form>

@endsection