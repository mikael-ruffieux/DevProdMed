@extends('template_contact')

@section('contenu')
<br>
<div class="col-sm-offset-3 col-sm-6">
    <div class="panel panel-info">
        <div class="panel-heading">Contactez-moi</div>
        <div class="panel-body">
            <form method="POST" action="{{ url('contact') }}" accept-charset="UTF-8">
                @csrf
                <div class="form-group ">
                    <input class="form-control" placeholder="Votre nom" name="nom" type="text" value="{{old('nom')}}">
                    {!! $errors->first('nom', '<small class="help-block">:message</small>') !!}
                </div>
                <div class="form-group ">
                    <input class="form-control" placeholder="Votre email" name="email" type="email" value="{{old('email')}}">
                    {!! $errors->first('email', '<small class="help-block">:message</small>') !!}
                </div>
                <div class="form-group ">
                    <textarea class="form-control" placeholder="Votre message" name="texte" cols="50" rows="10">{{old('texte')}}</textarea>
                    {!! $errors->first('texte', '<small class="help-block">:message</small>') !!}
                </div>
                <input class="btn btn-info pull-right" type="submit" value="Envoyer !">
            </form>
        </div>
    </div>
</div>                 
@endsection