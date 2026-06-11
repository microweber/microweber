{{--
type: layout

name: Contacts 9

position: 9

categories: Contact Us
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-contacts-skin-9"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="text-center">
                <div class="col-12 col-lg-8 col-lg-6 mx-auto regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Feedback</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire to send on into the next generation the great.</p>
                    <br/>
                </div>

                <div class="col-12 col-lg-9 col-lg-7 mx-auto regular-mode">
                    <module type="contact_form" template="skin-5"/>
                </div>

                <div class="col-12 col-lg-8 col-lg-6 mx-auto regular-mode mt-2">
                    <a href="mailto:info@company.com">info@company.com</a>
                    <br/>
                    <br/>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-0">6100 Your address here <br/>Palo Alto, CA</p>
                    <module type="social_links" template="skin-5" />
                </div>
            </x-row>
</x-layout-section>
