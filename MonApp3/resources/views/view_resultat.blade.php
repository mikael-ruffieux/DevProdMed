@extends('template_form')

@section('contenu')
<div style="max-width: 640px; margin: auto;">
    <table style="width: 100%;">
        <tr>
            <th colspan="2" style="text-align:left; background-color: tomato;">Agenda des RDV</th>
        </tr>
        <tr>
            <td><b>Personnes</b></td><td><b>Tranches horaires</b></td>
        </tr>
        @foreach ($etudiants as $etudiant)
        <tr>
            <td>{{{$etudiant['nom']}}}</td><td>{{{$etudiant['debut']}}} - {{{$etudiant['fin']}}}</td>
        </tr>
            
        @endforeach
    </table>
</div>
@endsection