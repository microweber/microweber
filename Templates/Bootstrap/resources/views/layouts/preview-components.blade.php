<!DOCTYPE html {!! lang_attributes() !!}>
<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    {{-- Vite CSS --}}

    @if(!lang_is_rtl())
        {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app.scss') }}
    @else
        {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app-rtl.scss') }}
    @endif


</head>

<body class="{!! helper_body_classes() !!}">

<div class="main">
    <x-container>
        <x-navbar brand="My App" brandUrl="/">
            <x-nav-item href="/" active>Home</x-nav-item>
            <x-nav-item href="/about">About</x-nav-item>
            <x-nav-item href="/contact">Contact</x-nav-item>
        </x-navbar>

        <x-hero>
            <x-slot name="image">{{asset('templates/bootstrap/img/heros/illustration-2.png')}}</x-slot>
            <x-slot name="title">
                <h1>Welcome to Microweber</h1>
            </x-slot>
            <x-slot name="content">
                <p>
                    Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use,
                    and it's a great tool for building websites, online shops, blogs, and more. It's based on the
                    Laravel PHP framework and the Bootstrap front-end framework.
                </p>
            </x-slot>
            <x-slot name="actions">
                <a href="#" class="btn btn-primary">Get Started</a>
                <a href="#" class="btn btn-secondary">Learn More</a>
            </x-slot>
        </x-hero>
    </x-container>

    <x-container>
        <h2>Column Examples</h2>
        <x-row>
            <x-col size="4">
                <x-card>
                    <x-slot name="title">Column 1</x-slot>
                    <x-slot name="content">Content for column 1</x-slot>
                </x-card>
            </x-col>
            <x-col size="4">
                <x-card>
                    <x-slot name="title">Column 2</x-slot>
                    <x-slot name="content">Content for column 2</x-slot>
                </x-card>
            </x-col>
            <x-col size="4">
                <x-card>
                    <x-slot name="title">Column 3</x-slot>
                    <x-slot name="content">Content for column 3</x-slot>
                </x-card>
            </x-col>
        </x-row>
    </x-container>
</div>

</body>
</html>
