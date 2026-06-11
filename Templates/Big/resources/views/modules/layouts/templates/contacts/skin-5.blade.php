{{--
type: layout

name: Contacts 5

position: 5

categories: Contact Us
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-5"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="text-center">
                <div class="mx-auto col-sm-6 col-md-4 cloneable element background-color-element allow-select regular-mode">
                    <i class="mw-micon-Map-Marker2 safe-element" style="font-size: 40px;"></i>
                    <p class="mt-3" data-mwplaceholder="{{ _e('Enter text here') }}">6100 Hackett Plain Suite 705 <br/>Palo Alto, CA</p>
                </div>

                <div class="mx-auto col-sm-6 col-md-4 cloneable element background-color-element allow-select regular-mode">
                    <i class="mw-micon-Email safe-element" style="font-size: 40px;"></i>
                    <p class="mt-3 d-block safe-mode element">info@company.com</p>
                </div>

                <div class="mx-auto col-sm-6 col-md-4 cloneable element background-color-element allow-select regular-mode">
                    <i class="mw-micon-Smartphone-3 safe-element" style="font-size: 40px;"></i>
                    <p class="mt-3" data-mwplaceholder="{{ _e('Enter text here') }}">+1 234 567 890</p>
                </div>
            </x-row>

            <br>

            <div class="col-12 col-lg-10 col-lg-8 mx-auto text-center allow-select regular-mode">
                <h5 data-mwplaceholder="{{ _e('Enter title here') }}">There is a moment in the life of any aspiring astronomer that it is time to buy that first telescope.
                    <br> It's exciting to think about setting up your own
                </h5>
                <br/><br/>
                <module type="social_links"/>
            </div>
</x-layout-section>
