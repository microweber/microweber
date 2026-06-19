{{--
type: layout

name: Contacts 23

position: 23

categories: Contact Us
--}}

<style>
   .contacts-23 .reservation-img {
        min-height: 500px;
    }

    .contacts-23 .reservation-form-bg {
        background: rgba(55, 55, 63, 0.04);
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section contacts-23"
    field-name="layout-contacts-skin-23"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <div class="section-header">
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Book A Table</p>
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Book <span>Your Stay</span> With Us</h2>
                </div>

                <x-row class="g-0">
                    <div class="col-lg-5 reservation-img background-image-holder" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-6.jpg') }}');" data-aos="zoom-out" data-aos-delay="200"></div>

                    <div class="col-lg-7 reservation-form-bg background-color-element element p-md-5 p-2">
                        <module type="contact_form" template="skin-1"/>
                    </div>

                </x-row>
</x-layout-section>
