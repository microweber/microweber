@php
/*

type: layout

name: Content 3

position: 3

categories: Content

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp


<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container-fluid mw-layout-container safe-mode no-element   edit " field="layout-content-skin-3-{{ $params['id'] }}" rel="module">
        <div class="row nodrop no-select">
            <div class="col-12 col-lg-6   text-center safe-mode allow-select allow-drop" style="min-height: 50px">
                <img loading="lazy" src="   {{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
            </div>

            <div class="col-12 col-lg-6 px-md-5 cloneable element background-color-element  safe-mode regular-mode allow-select allow-drop" style="min-height: 50px">

                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Story Should Evolve <br> Over Time</h3>
                    <br><br>

                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Incorporate visuals like images, videos, and graphics that complement your story. Visuals can enhance understanding and emotional impact. Platform Relevance: Adapt your story to the platform you're using. What works on a blog might not work on Instagram or Twitter. Tailor your content accordingly. Value Proposition: Clearly communicate the value your idea, business, or project brings to your audience.</p>

                    <br><br>
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Remember, your story is a dynamic tool that can evolve and adapt as your venture progresses. The way you tell your story online can indeed make a significant difference in building connections, generating interest, and achieving your goals.</p>

                    <br><br>
                    <module type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>

            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
