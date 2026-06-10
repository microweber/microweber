@php
/*

type: layout

name: Content 75

position: 75

categories: Content

*/
@endphp

<style>
    .tony-template-content-2-boxes {
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        border: 1px solid #DCE2E7;
        padding: 20px;
        margin: 20px 10px;
        cursor: pointer;
        border-radius: 3px;
        background-color: #ffffff;
    }

    .tony-template-content-2-boxes:hover {
        border-color: transparent;
        box-shadow: unset;
    }


    .home-1-content-span-label {
        background-color: #1E2432;
        padding: 8px 20px;
        color: white;
        font-size: 12px;
        font-weight: 300;
    }

    .home-1-content-span-label-2 {
        background-color: #F5F7FD;
        padding: 8px 30px 8px 10px;
        color: #1E2432;
        font-size: 14px;
        font-weight: 400;
    }
</style>


<section class="section {{ $layout_classes ?? '' }} section-content-80 pb-0 ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container-fluid mw-layout-container safe-mode no-element   edit" field="layout-content-skin-75-{{ $params['id'] }}" rel="module">
        <div class="row nodrop no-select">
            <div class="col-12 col-lg-6 mx-auto text-center text-lg-start cloneable element background-color-element safe-mode d-flex flex-column align-items-start allow-select">

                    <div class="safe-mode allow-drop ">
                        <div class="d-inline regular-mode">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Design Agency Network</h3>
                            <br>
                        </div>
                        <div class="regular-mode">
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">We are a specialist agency in manufacturing design for <br> personal and corporate brands</p>
                        <br/>
                        <module type="btn" button_style="btn-primary"  text="Learn More"/>

                        </div>
                    </div>

                    <div class="col-12 d-flex flex-wrap mx-auto justify-content-center safe-mode nodrop no-select">
                        <div class="col-md-4 col-12  mx-xl-0 mx-3 safe-mode cloneable element allow-drop allow-select" >
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">2K+</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Podcasts</p>
                        </div>
                        <div class="col-md-4 col-12  mx-xl-0 mx-3 safe-mode cloneable element allow-drop allow-select">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">10K+</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Active Users</p>
                        </div>

                        <div class="col-md-4 col-12  mx-xl-0 mx-3 safe-mode cloneable element allow-drop allow-select">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">190K+</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Podcasts</p>
                        </div>


                    </div>
                    <div class="  mt-auto">
                        <module type="btn"  button_style="btn-primary" text="Read More"/>
                    </div>
            </div>


            <div class="col-12 col-lg-6 mx-auto cloneable element background-color-element safe-mode allow-select allow-drop">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt=""/>
            </div>

        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
