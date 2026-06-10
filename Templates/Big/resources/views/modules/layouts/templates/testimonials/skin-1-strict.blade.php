{{-- 
  type: layout
  name: Testimonial 1
  position: 1
  categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section mw-layout-container {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" height="120px" />
    
    <div class="mw-layout-container no-element no-drop container edit noedit" field="layout-testimonials-skin-1-{{ $params['id'] ?? '' }}" rel="module">
        <div>
            <module type="testimonials" template="skin-2" project_name="Testimonials 1"/>
        </div>
    </div>
    
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" height="120px" />
</section>