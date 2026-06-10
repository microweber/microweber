@php
/*
type: layout
name: Content Cards
position: 90
categories: Content
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-content-skin-90-{{ $params['id'] }}" rel="module">
        <div class="text-center mx-auto pb-4" style="max-width: 720px;">
            <h3 class="regular-mode" data-mwplaceholder="Enter title here">Featured Content</h3>
            <p class="regular-mode" data-mwplaceholder="Enter text here">Discover our latest articles, guides and resources.</p>
        </div>

        <x-row class="g-4 safe-mode">
            <x-col size="12" size-md="6" size-lg="4">
                <x-content-card
                    title="Getting Started"
                    description="Learn the basics and start building your website in minutes."
                    date="2026"
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="12" size-md="6" size-lg="4">
                <x-content-card
                    title="Advanced Techniques"
                    description="Take your website to the next level with advanced customization."
                    date="2026"
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="12" size-md="6" size-lg="4">
                <x-content-card
                    title="Best Practices"
                    description="Follow industry best practices for a professional website."
                    date="2026"
                    class="shadow-sm h-100"
                />
            </x-col>
        </x-row>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>