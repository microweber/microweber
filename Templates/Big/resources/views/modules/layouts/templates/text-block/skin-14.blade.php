{{--
type: layout
name: Text block 14
position: 14
categories: Text block
--}}

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 mw-layout-dark-background"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/text-blocks-1.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <div class="mh-100vh d-flex align-items-center">
            <div class="mw-layout-container no-element container safe-mode {{ $layout_classes ?? '' }} edit" field="layout-text-block-skin-14-{{ $params['id'] }}" rel="module">
                <x-row class="text-center">
                    <div class="col-12 regular-mode col-lg-10 mx-auto">
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Lorem Ipsum is simply dummy text of the printing.</p>
                        <br>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                    </div>
                </x-row>
            </div>
        </div>
</x-layout-section>
