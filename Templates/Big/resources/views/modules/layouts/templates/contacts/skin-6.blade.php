@php
/*

type: layout

name: Contacts 6

position: 6

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'pt-0';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-0';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} ">
     <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container-fluid position-relative edit safe-mode" field="layout-contacts-skin-6-{{ $params['id'] ?? '' }}" rel="module">

        <div class="container safe-mode regular-mode">
            <div class="row">
                <div class="row align-items-center col-sm-10 col-lg-6 regular-mode">

                    <div class="d-flex align-items-center flex-wrap element background-color-element regular-mode cloneable p-md-5 my-auto">
                        <div class="col-md-6">
                            <div class="cloneable element safe-mode background-color-element">
                                <p data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Address</p>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">6100 Your address here <br/>Palo Alto, CA</p>
                            </div>

                            <div class="cloneable element safe-mode background-color-element">
                                <p data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Email</p>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}"><a href="#">info@company.com</a></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cloneable element safe-mode background-color-element">
                                <p data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Phone</p>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">001 234 567 890</p>
                            </div>

                            <div class="cloneable element safe-mode background-color-element">
                                <p class="font-weight-bold">Social Networks</p>
                                <module type="social_links"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ms-auto my-3 col-md-6 col-lg-6 px-md-5 px-lg-7 px-lg-9 cloneable element background-color-element regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-4">When you enter into any new area of science you almost always find yourself.</p>
                    <module type="contact_form" template="skin-3"/>
                </div>
            </div>
        </div>
    </div>
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
