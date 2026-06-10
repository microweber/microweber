@php
/*

type: layout

name: Contacts 12

position: 12

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'pt-7';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-7';
}

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section">
     <module type="background" data-background-color="#f0f0f0" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-contacts-skin-12-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row justify-content-center text-center">
            <div class="cloneable element regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Contact Us</h3>
            </div>

            <div class="mx-auto cloneable element regular-mode">
                <h6 data-mwplaceholder="{{ _e('Enter text here') }}">001 234 567 890</h6>
                <h6 data-mwplaceholder="{{ _e('Enter text here') }}"><a href="mailto:info@company.com">info@company.com</a></h6>
            </div>

            <div class="mx-auto cloneable element regular-mode">
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Follow us on social media</p>
                <module type="social_links" template="skin-1"/>
            </div>
        </div>
    </div>
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
