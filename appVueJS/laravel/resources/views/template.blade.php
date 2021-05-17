<!doctype html>
<html lang='fr'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>
            Mon blog
        </title>
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <!-- Scripts -->
        <script src="{{ asset('js/vue.js') }}" defer></script>

        <style> textarea {resize:none} </style>
    </head>
    <body>
        <header class="jumbotron">
            <div class="container">
                <h1 class="page-header"><a href="{{route('articles.index')}}">Mon blog</a></h1>
                @yield('header')
            </div>
        </header>
        <div class="container">
            @yield('contenu')
        </div>
    </body>
</html>