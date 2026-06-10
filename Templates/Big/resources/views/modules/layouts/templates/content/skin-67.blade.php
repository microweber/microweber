@php
/*

type: layout

name: Content 67

position: 67

categories: Content

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'fluid-p-t';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'fluid-p-b';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp


<section class="section   {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container mw-layout-container safe-mode no-element   text-center   edit " field="layout-content-skin-content-67-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto allow-drop allow-select" style="min-height:50px">
                <img loading="lazy" class="safe-element" style=" max-width: 500px;" src="{{ asset('templates/big/img/decoration-2.svg') }}" alt=""/>

                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="my-md-5 my-3">Our Best Moments</h2>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" style="text-align-last: center; text-align: justify !important;">If you are about to organize your wedding, you are probably looking for ideas to make your wedding party (depending on the style you choose) fun, unconventional and different. There is no limit to the newlyweds' imagination, especially when they want to organize something special to surprise, entertain and entertain their guests, and there are so many things you can add to your wedding to give it a pinch of uniqueness. In this article you will find 11 original ideas for your Wedding that will surely make you think "I want it too!" Including interesting ideas for wedding decorations, wedding invitations, photos, and the preparation for the wedding itself.</p>
                    <br><br>
                    <module type="btn" button_text="Learn more"/>

            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
