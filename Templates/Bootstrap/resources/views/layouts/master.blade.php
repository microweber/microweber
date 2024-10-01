<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    {{-- Vite CSS --}}
    {{-- {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app.scss') }} --}}
</head>

<body>
    @yield('content')

    {{-- Vite JS --}}
    {{-- {{ template_vite('templates/bootstrap/dist', 'resources/assets/js/app.js') }} --}}

    {!! meta_tags_footer() !!}
</body>
