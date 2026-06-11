{{--
type: layout

name: Contacts 12

position: 12

categories: Contact Us
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-12"
    default-padding-top="pt-7"
    default-padding-bottom="pb-7"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="justify-content-center text-center">
                <div class="cloneable element regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Contact Us</h3>
                </div>

                <div class="mx-auto cloneable element regular-mode">
                    <h6 data-mwplaceholder="{{ _e('Enter text here') }}">001 234 567 890</h6>
                    <h6 data-mwplaceholder="{{ _e('Enter text here') }}"><a href="mailto:info@company.com">info@company.com</a></h6>
                </div>

                <div class="mx-auto cloneable element regular-mode">
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Follow us on social media</p>
                    <module type="social_links" template="skin-1"/>
                </div>
            </x-row>
</x-layout-section>
