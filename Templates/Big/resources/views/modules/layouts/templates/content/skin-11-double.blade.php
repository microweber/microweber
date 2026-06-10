@php
/*

type: layout

name: Content 11 - Double Parallax

position: 12

categories: Content

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'pt-10';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-10';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
   .content-11-double .background-image-holder {
       background-position: center center;
       background-attachment: fixed !important;
    }
</style>

<section class="section content-11-double py-0 mw-layout-parallax  d-flex align-items-end justify-content-center">

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container safe-mode no-element edit  mh-700 " field="layout-content-skin-11-{{ $params['id'] }}" rel="module">

       <div class="row">
           <div class="col-md-6 background-image-holder d-flex align-items-center justify-content-center mh-700" style="background-image: url({{ asset('templates/big/img/layouts/gallery-1-15.jpg') }})">

               <div class="container-fluid col-sm-12 mx-auto mx-lg-0 allow-select allow-drop" style="min-height: 60px">
                   <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-normal" style="color: #ffffff;">First off, you will need to set a budget<br/>
                       for your new purchase before deciding whether to shop for notebook or desktop computers. Many offices use.</h6>

               </div>
           </div>

           <div class="col-md-6 background-image-holder d-flex align-items-center justify-content-center mh-700" style="background-image: url({{ asset('templates/big/img/layouts/gallery-1-13.jpg') }})">

               <div class="container-fluid col-sm-12 mx-auto mx-lg-0   allow-select allow-drop" style="min-height: 60px">
                   <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-normal" style="color: #ffffff;">First off, you will need to set a budget<br/>
                       for your new purchase before deciding whether to shop for notebook or desktop computers. Many offices use.</h6>


               </div>
           </div>
       </div>
    </div>


    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
