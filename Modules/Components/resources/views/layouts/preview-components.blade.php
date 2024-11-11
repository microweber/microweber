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

    <x-section title="Test Title" some-attr="Test" another-attr="Test2" class="custom-class">Section Content goes here</x-section>

    <x-container>
        <h2>Column Examples</h2>
        <x-row>
            <x-col size="4">
                <x-card>
                    <x-slot name="image">{{ asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png') }}</x-slot>
                    <x-slot name="title">Microweber Card</x-slot>
                    <x-slot name="content">
                        <p>
                            Some quick example text to build on the card title and make up the bulk of the card's content.
                        </p>
                    </x-slot>
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

    <x-container>
        <h2>Alert Example</h2>
        <x-alert type="success" dismissible>Your changes have been saved successfully!</x-alert>
        <x-alert type="danger" dismissible>There was an error processing your request.</x-alert>

        <h2>Button Example</h2>
        <x-button type="primary">Primary Button</x-button>
        <x-button type="secondary" outline>Secondary Button</x-button>

        <h2>Modal Example</h2>
        <x-modal id="exampleModal" title="Modal Title">
            <x-slot name="body">
                This is the modal body content.
            </x-slot>
            <x-slot name="footer">
                <x-button type="secondary" data-bs-dismiss="modal">Close</x-button>
                <x-button type="primary">Save changes</x-button>
            </x-slot>
        </x-modal>

        <h2>Select Example</h2>
        <x-select name="country" label="Select Country" :options="['USA', 'Canada', 'Mexico']"/>

        <h2>Input Example</h2>
        <x-input name="email" label="Email Address" type="email" placeholder="Enter email" required/>

        <h2>Progress Bar Example</h2>
        <x-progress-bar value="75" type="success" striped animated/>

        <h2>Tabs Example</h2>
        <x-tabs>
            <x-tab-pane title="Home" active>
                Home content
            </x-tab-pane>
            <x-tab-pane title="Profile">
                Profile content
            </x-tab-pane>
        </x-tabs>

        <h2>Pagination Example</h2>
        @php
        $posts = new \Illuminate\Pagination\LengthAwarePaginator(range(1, 100), 100, 10);
        @endphp
        <x-pagination :items="$posts"/>
    </x-container>
</div>

</body>
</html>
