Corrigé ExerciceJour4 (Manifestation) :
==========
## Config Mail (`.env`)

```none
MAIL_MAILER=smtp
MAIL_HOST=smtp.heig-vd.ch
MAIL_PORT=587
MAIL_USERNAME=user_name_de_l'école
MAIL_PASSWORD=password_de_l'école 
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=adresse_mail_de_l'école
MAIL_FROM_NAME="${APP_NAME}"
```

## Routes (`web.php`) : 

```php
use App\Http\Controllers\ManifController;
//...
Route::get('manif', [ManifController::class,'rendFormManif']);
Route::post('manif', [ManifController::class,'traiteFormManif']);
```

## Contrôleur (`ManifController.php`) :

```
php artisan make:controller ManifController
```



```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManifRequest;
use App\Rules\ManifRule;
use Mail;

class ManifController extends Controller {

    public function rendFormManif() {
        return view('view_manif_form');
    }

    public function traiteFormManif(ManifRequest $request) {
        $request->validate(['fin' => new ManifRule($request->debut)]);
        
        Mail::send('view_manif_mail', $request->all(), function($message){
            $message->to('jean-pierre.hess@heig-vd.ch')->subject('Laravel (Contact)');
        });
        
        return view('view_manif_confirm');
    }
}
```

## Validateur de formulaire (`ManifRequest.php`) :

```
php artisan make:request ManifRequest
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManifRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'debut' => 'required|after_or_equal:tomorrow',
            'fin' => 'required|after:debut',
            'lieu' => 'required|min:3|regex:[^[A-Z].*]'
        ];
    }
}
```

## Validateur de champs personnalisé (`ManifRule.php`)

```
php artisan make:rule ManifRule
```

```php
<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ManifRule implements Rule {

    protected $debut;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($debut) {
        $this->debut = $debut;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $dateAuMinimum = date('Y-m-d', strtotime($this->debut . ' + 3 days'));
        $dateAuMaximum = date('Y-m-d', strtotime($this->debut . ' + 5 days'));
        return $value >= $dateAuMinimum && $value <= $dateAuMaximum;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        // va chercher le message correspondant à `wongperiod`
        // dans le fichier \resources\lang\en\validation2.php
        // ou              \resources\lang\fr\validation2.php
        // en fonction de la langue configurée dans \config\app.php
        return __('validation2.wrongperiod'); // https://laravel.com/docs/8.x/localization
    }
}
```

## Messages customisés

`\resources\lang\en\validation2.php`

```php
<?php

return [
    'wrongperiod' => 'The event must last at least 3 days and at most 5 days.',
];
```

`\resources\lang\fr\validation2.php`

```php
<?php

return [
    'wrongperiod' => 'La manifestation doit durer au moins 3 jours et au maximum 5 jours.',
];
```

## Template + Vues

`template.blade.php`

```html
<!doctype html>
<html lang='fr'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>
            Mon joli formulaire
        </title>
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <style> textarea {resize:none} </style>
    </head>
    <body>
        @yield('contenu')
    </body>
</html>
```

`view_manif_form.blade.php`

```php+HTML
@extends('template')

@section('contenu')
<br>
<div class="col-sm-offset-3 col-sm-6">
    <div class="panel panel-info">
        <div class="panel-heading">Quand et où aura lieu la prochaine manifestation : </div>
        <div class="panel-body">
            <form method="POST" action="{{ url('manif') }}" accept-charset="UTF-8">
                @csrf
                <div class="form-group {!! $errors->has('debut') ? 'has-error' : '' !!}">
                    <label for="lbDebut">Date du d&eacute;but </label>
                    <input class="form-control" name="debut" type="date" value="{{old('debut')}}>
                    {!! $errors->first('debut', '<small class="help-block">:message</small>') !!}
                </div>
                <div class="form-group {!! $errors->has('fin') ? 'has-error' : '' !!}">
                    <label for="lbFin">Date de fin </label>
                    <input class="form-control" name="fin" type="date" value="{{old('fin')}}">
                    {!! $errors->first('fin', '<small class="help-block">:message</small>') !!}
                </div>
                <div class="form-group {!! $errors->has('lieu') ? 'has-error' : '' !!}">
                    <label for="lbLieu">Lieu </label>
                    <input class="form-control" name="lieu" type="text" value="{{old('lieu')}}">
                    {!! $errors->first('lieu', '<small class="help-block">:message</small>') !!}
                </div>
                <input class="btn btn-info pull-right" type="submit" value="Envoyer !">
            </form>
        </div>
    </div>
</div>
@endsection
```

`view_manif_mail.blade.php`

```html
<!doctype html>
<html lang='fr'>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h2>Manifestation</h2>
        <p>La prochaine manifestation aura lieu du {{date('d.m.Y', strtotime($debut))}} au {{date('d.m.Y', strtotime($fin))}} à {{$lieu}}.</p>
        <p>Avec nos meilleures salutations.</p>
        <p>Le comité</p>
    </body>
</html>

```

`view_manif_confirm.blade.php`

```php+HTML
@extends('template')

@section('contenu')
<br>
<div class="col-sm-offset-3 col-sm-6">
    <div class="panel panel-info">
        <div class="panel-heading">Information</div>
        <div class="panel-body"> Merci. Votre message concernant la manifestation a été tansmis à l'admin</div>
    </div>
</div>
@endsection
```

