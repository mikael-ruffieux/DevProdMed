@extends('template')

@section('header')
@if(Auth::check())
<div class="btn-group pull-right">
    <a href='{{route("articles.create")}}' class='btn btn-info'>Cr&eacute;er un article</a>
    <a href='{{url("logout")}}' class='btn btn-warning'>Deconnexion</a>
</div>
@else
<a href='{{url("login")}}' class='btn btn-info pull-right'>Se connecter</a>
@endif
@endsection

@section('contenu')

<div id="vue-app">
    <articles :articles='@json($articles)' :limit='10'></articles>
</div>

@endsection