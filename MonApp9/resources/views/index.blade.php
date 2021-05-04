@extends('template')

@section('header')
@if(Auth::check())
<div class="btn-group pull-right">
    <a href='{{url("logout")}}' class='btn btn-warning'>Deconnexion</a>
</div>
@else 
<a href='{{url("login")}}' class='btn btn-info pull-right'>Se connecter</a>
@endif
@endsection

@section('content')
@if(isset($info))
<div class='row alert alert-info'> {{$info}}</div>
@endif

<p>Voici votre challenge :</p>

<p>Répondre le plus rapidement possible à 22 questions sur les livrets 2 à 12 (2 questions au hasard par table de multiplication)</p>

<p>Pour pouvoir faire partie du tableau des scores, il faut vous authetifier et avoir répondu juste à toutes les questions.</p>

<p>Cliquez sur le bouton Go dès que vous êtes prêts.</p>

<a class="btn btn-primary" href="{{ route("play") }}">Go</a>

<br><br>

<p><u>Tableau des 3 meilleurs scores :</u></p>

<table class="table">
    <tr>
        <th>Date</th>
        <th>Utilisateur</th>
        <th>Temps</th>
    </tr>
@foreach ($scores as $score)
    <tr>
        <td>{{$score->created_at}}</td>
        <td>{{$score->user->name}}</td>
        <td>{{$score->nbSecondes}} [s]</td>
    </tr>
@endforeach
</table>

@endsection