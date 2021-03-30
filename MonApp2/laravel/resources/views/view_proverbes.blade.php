@extends('monTemplate')

@section('titre')
    Mes 10 proverbes
@endsection

@section('contenu')
<h1>Mes 10 proverbes</h1>
<b>{{$source}}</b>
<ul>
    @foreach($proverbes as $proverbe)
    <li>{{$proverbe}}</li>
    @endforeach
</ul>
@endsection