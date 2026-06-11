{{--
type: layout

name: Contacts 13

position: 13

categories: Contact Us
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-13"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-center mb-5 regular-mode">Our Contacts</h3>

                <div class="col-md-6 mx-auto mt-5 regular-mode">
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">Phone: +1-123-456-78</h6>
                        <h6 data-mwplaceholder="{{ _e('Enter text here') }}">Email: <a href="mailto:info@company.com">info@company.com</a></h6>
                    <br/>
                    <br/>
                    <br/>

                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Add your company address here</p>
                    <module type="social_links" template="skin-1" />
                </div>

                <div class="col-md-6 mx-auto">
                    <module type="contact_form" template="skin-5"/>
                </div>
            </x-row>
</x-layout-section>
