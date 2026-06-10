@php
/*

type: layout

name: Contacts 3

position: 3

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

<section class="section {{ $layout_classes }} ">
     <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-contacts-skin-3-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center text-lg-start">
            <div class="col-12 col-lg-12 col-lg-12 mx-auto">
                <div class="row">
                    <div class="mx-auto my-3 col-sm-10 col-md-6 col-lg-7 d-flex flex-column cloneable element safe-mode overflow-hidden background-color-element regular-mode">
                      <div class="d-flex align-items-center flex-wrap">
                          <div class="col-md-6 safe-mode">
                              <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Address</h6>
                              <p data-mwplaceholder="{{ _e('Enter text here') }}">Sofia. Bulgaria <br/>Your address here</p>

                              <module type="social_links" template="skin-1"/>
                          </div>

                          <div class="col-md-6 safe-mode">
                              <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Email</h6>
                              <p data-mwplaceholder="{{ _e('Enter text here') }}"><a href="#">info@company.com</a></p>

                              <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Phone</h6>
                              <p data-mwplaceholder="{{ _e('Enter text here') }}">+1 234 567 890</p>
                          </div>

                      </div>
                       <module type="google_maps" class="safe-element" data-height="550"/>

                    </div>

                    <div class="mx-auto my-3 col-sm-10 col-md-6 col-lg-5 cloneable element safe-mode background-color-element regular-mode">
                        <div class="ps-lg-5">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-4">When you enter into any new area of science, you almost always find yourself with a baffling.</p>
                            <module type="contact_form" template="skin-3"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
