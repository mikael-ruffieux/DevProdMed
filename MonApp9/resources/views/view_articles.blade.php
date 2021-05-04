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
@if(isset($info))
<div class='row alert alert-info'> {{$info}}</div>
@endif
{!!$links!!}
@foreach($articles as $article)
<article class="row bg-primary">
    <div class="col-md-12">
        <header>
            <h1>{{$article->titre}}</h1>
        </header>
        <hr>
        <section>
            <p>{{$article->contenu}}</p>
            @if(Auth::check() and Auth::user()->admin)
            <form method="POST" action="{{route('articles.destroy', [$article->id])}}" accept-charset="UTF-8">
                @csrf
                @method('DELETE')
                <input class="btn btn-danger btn-xs" onclick="return confirm('Vraiment supprimer cet article ?')" type="submit" value="Supprimer cet article">
            </form>
            @endif
            <em class="pull-right">
                <span class="gliphicon glyphicon-pencil"></span>
                {{$article->user->name}} le {!! $article->created_at->format('d-m-Y') !!}
            </em>
        </section>
    </div>
</article>
<br>
@endforeach
{!! $links !!}
@endsection