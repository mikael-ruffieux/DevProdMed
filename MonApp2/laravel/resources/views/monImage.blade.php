@extends('monTemplate')

@section('titre')
Ma belle image
@endsection

@section('contenu')
<p>
    Voici ma première image :
    <br><br>
    <img src="{{ asset('storage/images/img01.jpg') }}" alt="Mon image"/>
</p>
@endsection