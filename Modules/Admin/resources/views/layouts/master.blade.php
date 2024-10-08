<!DOCTYPE html>
<html {!! lang_attributes() !!}>

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    {{-- Vite CSS --}}
    {{-- {{ module_vite('modules/admin/dist', 'resources/assets/sass/app.scss') }} --}}
</head>

<body class="{!! helper_body_classes() !!}">
    @yield('content')

    {{-- Vite JS --}}
    {{-- {{ module_vite('modules/admin/dist', 'resources/assets/js/app.js') }} --}}
</body>
