{{--
type: layout

name: Contacts 6

position: 6

categories: Contact Us
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-6"
    default-padding-top="pt-0"
    default-padding-bottom="pb-0"
    container-class="mw-layout-container no-element container-fluid position-relative edit safe-mode"
>
    <div class="container safe-mode regular-mode">
                <x-row>
                    <x-row class="align-items-center col-sm-10 col-lg-6 regular-mode">

                        <div class="d-flex align-items-center flex-wrap element background-color-element regular-mode cloneable p-md-5 my-auto">
                            <div class="col-md-6">
                                <div class="cloneable element safe-mode background-color-element">
                                    <p data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Address</p>
                                    <p data-mwplaceholder="{{ _e('Enter text here') }}">6100 Your address here <br/>Palo Alto, CA</p>
                                </div>

                                <div class="cloneable element safe-mode background-color-element">
                                    <p data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Email</p>
                                    <p data-mwplaceholder="{{ _e('Enter text here') }}"><a href="#">info@company.com</a></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="cloneable element safe-mode background-color-element">
                                    <p data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Phone</p>
                                    <p data-mwplaceholder="{{ _e('Enter text here') }}">001 234 567 890</p>
                                </div>

                                <div class="cloneable element safe-mode background-color-element">
                                    <p class="font-weight-bold">Social Networks</p>
                                    <module type="social_links"/>
                                </div>
                            </div>
                        </div>
                    </x-row>

                    <div class="ms-auto my-3 col-md-6 col-lg-6 px-md-5 px-lg-7 px-lg-9 cloneable element background-color-element regular-mode">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-4">When you enter into any new area of science you almost always find yourself.</p>
                        <module type="contact_form" template="skin-3"/>
                    </div>
                </x-row>
            </div>
</x-layout-section>
