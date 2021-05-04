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
    <form method="POST" action="play" accept-charset="UTF-8">
    @csrf
        @foreach ($questions as $key => $question)
        <div class="col-md-4 col-sm-6 col-12">
            <label for="{{$key}}">{{$question['question']}}</label>
            <input type="number" name="answers[{{$key}}][answer]" value="4">
            <input class="hidden" type="number" name="answers[{{$key}}][correct]" value="{{$question['answer']}}">
        </div>
        @endforeach
        <input type="submit" value="Envoyer">
    </form>
</div>


@endsection