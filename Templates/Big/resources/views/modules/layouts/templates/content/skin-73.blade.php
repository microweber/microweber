{{--
type: layout

name: Content 73

position: 73

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section section-content-73"
    field-name="layout-content-skin-73"
    container-class="mw-layout-container container-fluid safe-mode no-element edit"
>
    <x-row class="nodrop no-select">
                <div class="col-12 col-lg-6 mx-auto text-center text-lg-start d-flex align-items-center position-relative cloneable " style="z-index: 1;">
                   <x-row>
                       <div class="  mb-3 safe-mode">

                           <div class="d-inline regular-mode allow-drop allow-select">
                               <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Book Our Course & Improve Your Skill.</h2>
                               <br>
                           </div>

                           <div class="regular-mode allow-drop allow-select">
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="py-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br> Tincidunt amet sit placerat diam praesent pharetra at.
                                <br> Gravida ornare mauris pretium tortor, ac in nulla eleifend.</p>
                            <br/>
                            <module type="btn" text="Contact Us" button_style="content-73-btn"/>

                           </div>
                       </div>

                       <div class="col-12 d-flex mt-7">
                           <div class="col-lg-6 px-3 mx-1 cloneable element allow-select" style="border-left: 1px solid #58585D;">
                               <div class="d-flex align-items-center safe-mode allow-drop allow-select">
                                   <h3 data-mwplaceholder="{{ _e('Enter title here') }}">586K+</h3>
                                   <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="ms-2">Student</h6>
                               </div>

                               <div class="regular-mode allow-drop allow-select">
                               <p>Lorem upsum dolor sit amet, consectetur adipiscing elit.</p>

                           </div>
                           </div>
                           <div class="col-lg-6 px-3 mx-1 cloneable element allow-select" style="border-left: 1px solid #58585D;">
                               <div class="d-flex align-items-center safe-mode allow-drop allow-select">
                                   <h3 data-mwplaceholder="{{ _e('Enter title here') }}">250+</h3>
                                   <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="ms-2">Course</h6>
                               </div>

                               <div class="regular-mode allow-drop allow-select">
                               <p data-mwplaceholder="{{ _e('Enter text here') }}">Lorem upsum dolor sit amet, consectetur adipiscing elit.</p>
                           </div>
                           </div>
                       </div>
                   </x-row>
                </div>

                <div class="col-12 col-lg-6 mx-auto cloneable element">
                    <div class="text-center text-lg-center pb-5   position-relative allow-drop allow-select">
                       <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}" class="content-73-image" alt=""/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
