@php
/*

type: layout

name: Misc 13

position: 13

categories: Misc

*/
@endphp

@if (!isset($classes['padding_top']))
    @php $classes['padding_top'] = ''; @endphp
@endif
@if (!isset($classes['padding_bottom']))
    @php $classes['padding_bottom'] = ''; @endphp
@endif

@php
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp
<section class="misc-13 d-flex {{ $layout_classes }} ">
            <module type="background" id="background-layout--{{ $params['id'] }}" />
            <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
        <div class="mw-layout-container no-element container edit" field="layout-skin-13-{{ $params['id'] }}" rel="module">

            <h2 data-mwplaceholder="{{ _e('Enter title here') }}">How to create a website for free.</h2>
            <p class="text-center" data-mwplaceholder="{{ _e('Enter text here') }}">Follow these 6 simple steps to create a website today.</p>

            <div>
                <p>1. <span class="font-weight-bold">Sign up for a free website builder.</span> Choose what kind of website you want to create.</p>
                <p>2. <span class="font-weight-bold">Customize a template or get a website made for you.</span> Choose your starting point.</p>
                <p>3. <span class="font-weight-bold">Drag and drop 1000s of design features.</span> Add text, galleries, videos, vector art and more.</p>
                <p>4. <span class="font-weight-bold">Get ready for business.</span> Add an online store, booking system, members area and blog.</p>
                <p>5. <span class="font-weight-bold">Publish your website and go live.</span> Start building your professional online presence.</p>
                <p>6. <span class="font-weight-bold">Drive traffic to your site.</span> Use advanced SEO tools and integrated marketing solutions.</p>
            </div>
            <div class="d-flex align-items-center justify-content-center text-center gap-3 mt-4">
                <module type="btn" text="Get Started" button_style="btn-dark" />
                <module type="btn" text="Learn More" button_style="btn-link" />
            </div>
        </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
