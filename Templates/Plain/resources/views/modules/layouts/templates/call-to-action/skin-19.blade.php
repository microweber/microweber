{{--
type: layout
name: Call to action 19
position: 19
hidden: true
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-19"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <div class="col-12 mx-auto text-center regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">The Amazing Hubble</h3>
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Have you ever finally just gave in to the temptation and read your horoscope in the newspaper on Sunday morning? Sure, we all have.</p>
                </div>
            </x-row>

            <div><br /></div>

            <x-row>
                <div class="col-12 mx-auto text-center">
                    <module type="contact_form" template="subscribe-6"/>
                </div>
            </x-row>
</x-layout-section>
