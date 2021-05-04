@extends('template')

@section('contenu')
<br>
<div class="col-sm-offset-3 col-sm-6">
    <div class="panel panel-info">
        <div class="panel-heading">Ajout d'un véhicule</div>
        <div class="panel-body">
            <form method="POST" action="{{route('cars.store')}}" accept-charset="UTF-8">
            @csrf
            @if(Auth::user()->admin)
            <div class="form-group {!! $errors->has('user_id') ? 'has-error' : '' !!}">
                <select name="user_id" id="user_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                    
                </select>
                {!! $errors->first('brand', '<small class="help-block">:message</small>') !!}
            </div>
            @endif
            <div class="form-group {!! $errors->has('brand') ? 'has-error' : '' !!}">
                <input class="form-control" placeholder="Marque" name="brand" type="text">
                {!! $errors->first('brand', '<small class="help-block">:message</small>') !!}
            </div>
            <div class="form-group {!! $errors->has('model') ? 'has-error' : '' !!}">
                <input type="text" class="form-control" placeholder="Modèle" name="model">
                {!! $errors->first('contenu', '<small class="help-block">:message</small>') !!}
            </div>
            <input class="btn btn-info pull-right" type="submit" value="Envoyer">
            </form>
        </div>
    </div>
    <a href="javascript:history.back()" class="btn btn-primary"><span class="glyphicon glyphicon-circle-arrow-left"></span>Retour</a>
</div>
@endsection