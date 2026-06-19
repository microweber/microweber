{{--
type: layout

name: Contacts 20 - Parallax

position: 20

categories: Contact Us
--}}

<style>
    .contacts-20-wrapper {
        background-color: #fff;
        position: relative;
        overflow: hidden;
        border-radius: 20px;
    }

    .contacts-20-title-wrapper {
        background-color: var(--mw-primary-color);
        color: #fff;
        padding: 10px 10px 10px 50px;
        margin: 0;
        border-radius: 20px 20px 0 0;
    }
</style>

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-parallax mw-layout-dark-background"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/trees.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />

        <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

            <div class="mw-layout-container no-element container edit safe-mode " field="layout-contacts-skin-20-{{ $params['id'] ?? '' }}" rel="module">
               <div class="contacts-20-wrapper background-color-element element col-lg-8 col-12 mx-auto allow-select p-md-5 p-3">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="contacts-20-title-wrapper background-color-element element text-start regular-mode">Please Say Hi</h3>

                   <module type="contact_form" class="pt-5" template="skin-5"/>
               </div>
            </div>

        <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</x-layout-section>
