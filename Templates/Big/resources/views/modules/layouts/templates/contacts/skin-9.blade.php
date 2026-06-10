@php
/*

type: layout

name: Contacts 9

position: 9

categories: Contact Us

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

<section class="section {{ $layout_classes }} form-control-outline-dark">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-contacts-skin-9-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center">
            <div class="col-12 col-lg-8 col-lg-6 mx-auto regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Feedback</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire to send on into the next generation the great.</p>
                <br/>
            </div>

            <div class="col-12 col-lg-9 col-lg-7 mx-auto regular-mode">
                <module type="contact_form" template="skin-5"/>
            </div>

            <div class="col-12 col-lg-8 col-lg-6 mx-auto regular-mode mt-2">
                <a href="mailto:info@company.com">info@company.com</a>
                <br/>
                <br/>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-0">6100 Your address here <br/>Palo Alto, CA</p>
                <module type="social_links" template="skin-5" />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
