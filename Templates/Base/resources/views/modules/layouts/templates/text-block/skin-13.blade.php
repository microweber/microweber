{{--
type: layout
name: Text block 13
position: 13
categories: Text block
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mh-100vh d-flex align-items-center"
>
    <div class="mw-layout-container no-element container safe-mode {{ $layout_classes ?? '' }} edit" field="layout-text-block-skin-13-{{ $params['id'] }}" rel="module">
                <x-row class="text-center">
                    <div class="col-12 regular-mode col-lg-10 mx-auto">
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Lorem Ipsum is simply dummy text of the printing.</p>
                        <br>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                    </div>
                </x-row>
            </div>
</x-layout-section>
