{{--
 type: layout
 name: Feature 40
 position: 40
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'mw-p-t-100';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'mw-p-b-70';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section feature-40 {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-skin-40-{{ $params['id'] }}" rel="module">
        <h3 class="text-center pb-md-5 regular-mode" data-mwplaceholder="{{ _e('Enter title here') }}">How it works?</h3>
        <div class="timeline mt-md-5">
            <div class="timeline__item cloneable cloneable element safe-mode">
                <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="timeline__title mw-100">1. Contact our team</h6>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="timeline__description mw-100">Contact us and describe your problem <br/>We are ready to help</p>
            </div>

            <div class="timeline__item cloneable cloneable element safe-mode">
                <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="timeline__title mw-100">2. Free diagnostics from our masters</h6>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="timeline__description mw-100">We will make a free diagnostic first</p>
            </div>

            <div class="timeline__item cloneable cloneable element safe-mode">
                <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="timeline__title mw-100">3. We start to repair it</h6>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="timeline__description mw-100">We will let you know about the problem and the solution</p>
            </div>

            <div class="timeline__item cloneable cloneable element safe-mode">
                <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="timeline__title mw-100">4. We start to repair it</h6>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="timeline__description mw-100">We will let you know about the problem and the solution</p>
            </div>

            <div class="timeline__item cloneable cloneable element safe-mode">
                <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="timeline__title mw-100">5. We start to repair it</h6>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="timeline__description mw-100">We will let you know about the problem and the solution</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
