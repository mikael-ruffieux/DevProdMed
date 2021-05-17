<!doctype html>
<html lang='fr'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>
            Mon application
        </title>
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <style> textarea {resize:none} </style>
    </head>
    <body>
        <header class="jumbotron">
            <div class="container">
                <h1 class="page-header"><a href="{{route('cars.index')}}">Parc automobile</a></h1>
                @if(Auth::check() and Auth::user()->admin)
                <span class="badge">Administrateur</span>
                @endif
                @yield('header')
            </div>
        </header>
        <div class="container">
            @yield('contenu')
        </div>
    </body>
</html>