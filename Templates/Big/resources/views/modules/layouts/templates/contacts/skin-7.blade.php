@php
/*

type: layout

name: Contacts 7

position: 7

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'pt-9';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-9';
}

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section form-control-outline-dark" data-background-position="center center" data-bg-contain="true">
     <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-contacts-skin-7-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center text-lg-start">

            <div class="mx-auto my-3 col-sm-10 col-md-6 col-lg-7 p-5 cloneable element regular-mode background-color-element">
                <h4 data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-4">Contact us email form</h4>
                <module type="contact_form" template="skin-4"/>
            </div>

            <div class="mx-auto my-3 col-sm-10 col-md-6 col-lg-5 cloneable element regular-mode background-color-element">
                <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-7">Company details</h4>

                <div class="cloneable element regular-mode mb-lg-7 mb-2">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Phone</h6>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">+1 234 567 890</p>
                </div>

                <div class="cloneable element regular-mode mb-lg-7 mb-2">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Address</h6>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">USA 6100 Hackett Plain Suite 705 <br />Palo Alto, CA</p>
                </div>

                <div class="cloneable element regular-mode mb-lg-7 mb-2">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Email</h6>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}"><a href="#">info@company.com</a></p>
                </div>
            </div>

        </div>
    </div>
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
