{{--
type: layout

name: Contacts 15

position: 15

categories: Contact Us
--}}

<style>
    @media screen and (min-width: 575px) {
        #{{ $params['id'] ?? '' }} .bg-body {
            max-width: 70% !important;
        }
    }

    @media screen and (min-width: 768px) {
        #{{ $params['id'] ?? '' }} .module-google-maps,
        #{{ $params['id'] ?? '' }} .module-google-maps .relative {
            height: 100% !important;
        }

        #{{ $params['id'] ?? '' }} .bg-body {
            max-width: 50% !important;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-15"
    container-class="mw-layout-container no-element container-fluid edit safe-mode px-0"
>
    <x-row class="text-center text-lg-start">
                <div class="col-sm-12 col-md-6 ps-md-5 element">
                    <div class="ps-xl-5 ms-xl-5 my-5 regular-mode">

                        <h2 class="my-5">Contact us:</h2>
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">+12 345 6789</h6>
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}"><a href="mailto:email@yourwebsite.com">email@yourwebsite.com</a></h6>
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">www.yourwebsite.com</h6>

                        <br>

                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">Address: Sofia, Bulgaria Tzar Asen 2, floor 2, office 3</h6>
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">We love to place promotions on our social networks.</h6>
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">To follow them and know about them follow us.</h6>

                        <module type="social_links" template="skin-1">

                    </div>
                </div>

                <div class="col-sm-12 col-md-6 px-0 overflow-hidden">
                    <div class="d-flex flex-column h-100">
                        <module type="google_maps" class="w-100"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
