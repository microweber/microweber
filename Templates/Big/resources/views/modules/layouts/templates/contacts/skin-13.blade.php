@php
/*

type: layout

name: Contacts 13

position: 13

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

<section class="section {{ $layout_classes }}">
     <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-contacts-skin-13-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-center mb-5 regular-mode">Our Contacts</h3>

            <div class="col-md-6 mx-auto mt-5 regular-mode">
                    <h6 data-mwplaceholder="{{ _e('Enter text here') }}">Phone: +1-123-456-78</h6>
                    <h6 data-mwplaceholder="{{ _e('Enter text here') }}">Email: <a href="mailto:info@company.com">info@company.com</a></h6>
                <br/>
                <br/>
                <br/>

                <p data-mwplaceholder="{{ _e('Enter text here') }}">Add your company address here</p>
                <module type="social_links" template="skin-1" />
            </div>

            <div class="col-md-6 mx-auto">
                <module type="contact_form" template="skin-5"/>
            </div>
        </div>
    </div>
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
