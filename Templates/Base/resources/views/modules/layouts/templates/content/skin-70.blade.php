{{--
type: layout

name: Content 70 - Video Background

position: 70

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background"
    background-attrs='data-background-video="{{ template_url() }}video/layouts/content-video-1.mp4"'
    default-padding-top="p-t-100"
    default-padding-bottom="p-b-100"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-video="{{ template_url() }}video/layouts/content-video-1.mp4" id="background-layout--{{ $params['id'] }}" />
    <div class="container mw-layout-container safe-mode mh-100vh d-flex flex-column align-items-center justify-content-center no-element {{ $layout_classes ?? '' }}   edit " field="layout-content-skin-70-{{ $params['id'] }}" rel="module">

           <div class="regular-mode allow-drop ">
               <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title fx-deactivate">Your Awesome Title Here</h2>
               <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p fx-deactivate">Leave application now and get -20% discount <br />for your first repair</p>
               <br/><br/><br/>

               <module type="btn" button_text="Get a Discount" class="fx-particles-1"/>
           </div>
        </div>
</x-layout-section>
