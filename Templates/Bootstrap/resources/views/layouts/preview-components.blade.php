<!DOCTYPE html>
<html {!! lang_attributes() !!}>
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
        <x-simple-text align="right">
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
        </x-simple-text>

        <x-row>
            <x-col col="4" col-lg="4">
                <x-card>
                    <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>
                    <x-slot name="title">Microweber Card</x-slot>
                    <x-slot name="content">
                        <p>
                            Some quick example text to build on the card title and make up the bulk of the card's
                            content.
                        </p>
                    </x-slot>
                    <x-slot name="footer">
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </x-slot>
                </x-card>
            </x-col>

            <x-col col="4">
                <x-card theme="success">
                    <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>
                    <x-slot name="title">CloudVision Cart</x-slot>
                    <x-slot name="content">
                        <p>
                            Some quick example text to build on the card title and make up the bulk of the card's
                            content.
                        </p>
                    </x-slot>
                    <x-slot name="footer">
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </x-slot>
                </x-card>
            </x-col>

            <x-col col="4">
                <x-card theme="danger">
                    <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>
                    <x-slot name="title">CHAT GPT Card</x-slot>
                    <x-slot name="content">
                        <p>
                            Some quick example text to build on the card title and make up the bulk of the card's
                            content.
                        </p>
                    </x-slot>
                    <x-slot name="footer">
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </x-slot>
                </x-card>
            </x-col>
        </x-row>

        <x-alert type="success" message="This is a success alert!"></x-alert>
        <x-button type="primary">Click Me!</x-button>
    </x-container>

    <x-container>
        <h2>Button Examples</h2>
        <x-row>
            <x-col col="4">
                <x-button type="primary">Primary Button</x-button>
            </x-col>
            <x-col col="4">
                <x-button type="secondary">Secondary Button</x-button>
            </x-col>
            <x-col col="4">
                <x-button type="success">Success Button</x-button>
            </x-col>
        </x-row>
        <x-row>
            <x-col col="4">
                <x-button type="danger">Danger Button</x-button>
            </x-col>
            <x-col col="4">
                <x-button type="warning">Warning Button</x-button>
            </x-col>
            <x-col col="4">
                <x-button type="info">Info Button</x-button>
            </x-col>
        </x-row>
        <x-row>
            <x-col col="4">
                <x-button type="light">Light Button</x-button>
            </x-col>
            <x-col col="4">
                <x-button type="dark">Dark Button</x-button>
            </x-col>
            <x-col col="4">
                <x-button url="example" type="link">Link Button</x-button>
            </x-col>
        </x-row>
    </x-container>
</div>

</body>
</html>
