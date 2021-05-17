@extends('template')

@section('header')
@if(Auth::check())
<div class="btn-group pull-right">
    <a href='{{route("cars.create")}}' class='btn btn-info'>Ajouter un véhicule</a>
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
<table class="table table-hover">
    <thead>
        <tr>
            <th>Marque</th><th>Modèle</th><th>Propriétaire</th><th>Depuis le</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cars as $car)
        <tr>
            <td>{{$car->brand}}</td>
            <td>{{$car->model}}</td>
            <td>{{$car->user->name}}</td>
            <td>{!! $car->created_at->format('d-m-Y') !!}</td>
            <td>
                @if(Auth::check())
                    @if(Auth::user()->admin || Auth::user()->id == $car->user->id)
                    <form method="POST" action="{{route('cars.destroy', [$car->id])}}" accept-charset="UTF-8">
                        @csrf
                        @method('DELETE')
                        <input class="btn btn-danger btn-xs" onclick="return confirm('Vraiment supprimer ce véhicule ?')" type="submit" value="Suppr.">
                    </form>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $links !!}
@endsection