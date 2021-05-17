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
    @foreach ($data['results'] as $id => $question)
        <div class="col-md-4 col-sm-6 col-12">
            <label for="{{$id}}">{{$question['val1']." * ".$question['val2']}}</label>
            <input type="number" value="{{$question['userAnswer']}}" disabled>
            @if ($question['userAnswer'] == $question['val1'] * $question['val2'])
                <i class="fas fa-check color-success"></i>
            @else
                <i class="fas fa-times color-danger"></i> ({{$question['val1'] * $question['val2']}})
            @endif
        </div>

    @endforeach
</div>

<div class="row mt-3">
    <div class="col-12">
    
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
            @foreach ($user->scores()->orderBy('nbSecondes','ASC')->limit(3)->get() as $score)
            <tr>
                <td>{{$score->created_at}}</td>
                <td>{{$score->nbSecondes}} [s]</td>
                <td>{{$score->pourcentageBonnesReponses}} %</td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>
</div>


@endsection