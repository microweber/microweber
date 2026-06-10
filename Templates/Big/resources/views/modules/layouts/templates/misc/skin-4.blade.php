@php
/*
 
type: layout
 
name: Misc 4
 
position: 4
 
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

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-misc-skin-4-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 d-flex flex-wrap">
                <div class="col-sm-6">
                    <div class="cloneable element background-color-element p-3">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Do I have to pay to keep my projects published?</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire to send on into the next generation the great things we have discovered about life. And part of that is the desire to install in our children the love of science, of learning.</p>
                    </div>
                    <div class="cloneable element background-color-element p-3">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Are there any discounts?</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire to send on into the next generation the great things we have discovered about life. And part of that is the desire to install in our children the love of science, of learning.</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="cloneable element background-color-element p-3">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">What happens if I downgrade?</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire to send on into the next generation the great things we have discovered about life. And part of that is the desire to install in our children the love of science, of learning.</p>
                    </div>
                    <div class="cloneable element background-color-element p-3">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">How can I switch between plans?</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire to send on into the next generation the great things we have discovered about life. And part of that is the desire to install in our children the love of science, of learning.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
