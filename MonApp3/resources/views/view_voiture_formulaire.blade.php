@extends('template_newsletter')

@section('titre')
Formulaire de saisie de voiture
@endsection

@section('contenu')
<div class="col-sm-offset-4 col-sm-4">
  <div class="panel panel-info">
    <div class="panel-heading">Enregistrement de votre voiture</div>
    <div class="panel-body">
      <form method="POST" action="{{ url('voiture') }}" 
              accept-charset="UTF-8">
        @csrf
        <div class="form-group {!! $errors->has('marque') ? 'has-error' : '' !!}">
          <input class="form-control" 
                 placeholder="Entrez la marque de votre voiture" name="marque" type="text" value="{{old('marque')}}">
          {!! $errors->first('marque', '<small class="help-block">:message</small>') !!}
        </div>
        <div class="form-group {!! $errors->has('type') ? 'has-error' : '' !!}">
          <input class="form-control" 
                 placeholder="Entrez le type de votre voiture" name="type" type="text" value="{{old('type')}}">
          {!! $errors->first('type', '<small class="help-block">:message</small>') !!}
        </div>
        <div class="form-group {!! $errors->has('couleur') ? 'has-error' : '' !!}">
          <input class="form-control" 
                 placeholder="Entrez la couleur de votre voiture" name="couleur" type="text" value="{{old('couleur')}}">
          {!! $errors->first('couleur', '<small class="help-block">:message</small>') !!}
        </div>
        <div class="form-group {!! $errors->has('cylindree') ? 'has-error' : '' !!}">
          <input class="form-control" 
                 placeholder="Entrez la cylindree (en litre) de votre voiture" name="cylindree" type="number" step="0.1" value="{{old('cylindree')}}">
          {!! $errors->first('cylindree', '<small class="help-block">:message</small>') !!}
        </div>
        <input class="btn btn-info pull-right" type="submit" value="Envoyer">
      </form>        
    </div>
  </div>
<div>
@endsection