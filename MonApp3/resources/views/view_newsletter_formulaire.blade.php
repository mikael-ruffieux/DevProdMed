@extends('template_newsletter')

@section('titre')
Formulaire de saisie d'email
@endsection

@section('contenu')
<div class="col-sm-offset-4 col-sm-4">
  <div class="panel panel-info">
    <div class="panel-heading">Inscription à la lettre d'information</div>
    <div class="panel-body">
      <form method="POST" action="{{ url('newsletter') }}" 
              accept-charset="UTF-8">
        @csrf
        <div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!}">
          <input class="form-control" 
                 placeholder="Entrez votre email" name="email" type="email" value="{{old('email')}}">
          {!! $errors->first('email', '<small class="help-block">:message</small>') !!}
        </div>
        <input class="btn btn-info pull-right" type="submit" value="Envoyer !">
      </form>        
    </div>
  </div>
<div>
@endsection