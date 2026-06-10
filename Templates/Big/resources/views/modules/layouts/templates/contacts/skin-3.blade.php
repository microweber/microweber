@php
/*

type: layout

name: Contacts 3

position: 3

categories: Contact Us

*/
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-3"
>
    <x-row class="text-center text-lg-start">
        <x-col size="12" class="mx-auto">
            <x-row>
                <div class="mx-auto my-3 col-sm-10 col-md-6 col-lg-7 d-flex flex-column cloneable element safe-mode overflow-hidden background-color-element regular-mode">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="col-md-6 safe-mode">
                            <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Address</h6>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Sofia. Bulgaria <br/>Your address here</p>

                            <x-social-links>
                                <module type="social_links" template="skin-1"/>
                            </x-social-links>
                        </div>

                        <div class="col-md-6 safe-mode">
                            <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Email</h6>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}"><a href="#">info@company.com</a></p>

                            <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Phone</h6>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">+1 234 567 890</p>
                        </div>
                    </div>
                    <module type="google_maps" class="safe-element" data-height="550"/>
                </div>

                <div class="mx-auto my-3 col-sm-10 col-md-6 col-lg-5 cloneable element safe-mode background-color-element regular-mode">
                    <div class="ps-lg-5">
                        <x-section-heading tag="h3" align="start" class="mb-4">Contact Us</x-section-heading>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-4">When you enter into any new area of science, you almost always find yourself with a baffling.</p>
                        <module type="contact_form" template="skin-3"/>
                    </div>
                </div>
            </x-row>
        </x-col>
    </x-row>
</x-layout-section>
