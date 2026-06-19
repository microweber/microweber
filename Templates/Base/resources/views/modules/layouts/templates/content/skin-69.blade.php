{{--
type: layout

name: Content 69

position: 69

categories: Content
--}}

<style>
    #layout-content-skin-content-69-{{ $params['id'] }}-container{
        display:contents !important;
    }
    #layout-content-skin-content-69-{{ $params['id'] }}-container > .row.cloneable:nth-of-type(even) {
        flex-direction: row-reverse;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-content-69"
    default-padding-top="pt-0"
    default-padding-bottom="pb-0"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <div id="layout-content-skin-content-69-{{ $params['id'] }}-container">
                <x-row class="cloneable element background-color-element safe-mode px-0 no-drag nodrop">
                    <div class="col-12 col-lg-6  element allow-drop" style="min-height:50px">
                        <img loading="lazy" class="w-100" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt=""/>
                    </div>
                    <div class="col-12 col-lg-6 px-6 px-xl-8 py-md-2 py-5 align-self-center    element regular-mode allow-drop"  style="min-height:50px">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">In The Desert</h3>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Two trees are brought to you, but on the top - a toothy goal, windy. With you in joy, with sore wounds, we go through life fearfully. Why weren't we in the Garden of Eden? Or in a park among flower beds? </p>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">So we will freeze completely, until Amina, in this unfortunate, ridiculous fate. And we are proud of our letters. We are a shady roof in the summer for everyone. From our hands - branches - radiant, we will bear fruit for ever. We are proud, eloquent, united. Sharp tooth - life is harsh.  Two old-fashioned trees. </p>
                    </div>
                </x-row>
                <x-row class="cloneable element background-color-element safe-mode px-0 allow-select">
                    <div class="col-12 col-lg-6  element allow-drop" style="min-height:50px">
                        <img loading="lazy" class="w-100" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt=""/>
                    </div>
                    <div class="col-12 col-lg-6 px-6 px-xl-8 py-md-2 py-5 align-self-center order-2 order-lg-1   element regular-mode allow-drop" style="min-height:50px">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Beautiful Nature</h3>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Two trees are brought to you, but on the top - a toothy goal, windy. With you in joy, with sore wounds, we go through life fearfully. Why weren't we in the Garden of Eden? Or in a park among flower beds? So we will freeze completely, until Amina, in this unfortunate, ridiculous fate. And we are proud of our letters. </p>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">We are a shady roof in the summer for everyone. From our hands - branches - radiant, we will bear fruit for ever. We are proud, eloquent, united. Sharp tooth - life is harsh. Two old-fashioned trees.</p>
                    </div>

                </x-row>
            </div>
</x-layout-section>
