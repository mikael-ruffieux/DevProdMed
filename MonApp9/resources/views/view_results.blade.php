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


            
<div class="row">
    @foreach ($data['answers']['answers'] as $key => $question)

    @endforeach

    <p><u>Votre résultat</u></p>

    <p>{{$data['time']}} [s]</p>

    <p>{{$data['score']}} %</p>

    <p><a href="/play" class="btn">Recommencer</a></p>

    @if ($user)
    <p><u>Vos meilleurs scores</u></p>
    <table class="table">
        <tr>
            <th>Date</th>
            <th>Temps</th>
            <th>Score</th>
        </tr>
        @foreach ($user->scores()->orderBy('nbSecondes','ASC')->get() as $score)
        <tr>
            <td>{{$score->created_at}}</td>
            <td>{{$score->nbSecondes}} [s]</td>
            <td>{{$score->pourcentageBonnesReponses}} %</td>x
        </tr>
        @endforeach
    </table>
    @endif
</div>


@endsection