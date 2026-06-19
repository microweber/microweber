{{--
type: layout

name: Content 7

position: 7

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-7"
    container-class="mw-layout-container container safe-mode no-element safe-mode edit"
>
    <x-row class="d-flex justify-content-center justify-content-md-between">
                <div class="col-12 col-sm-10 col-md-6 col-lg-6 mb-4 regular-mode allow-drop allow-select">
                     <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Like anything else, can go from the simple to the very complex.</h3>
                </div>

                <div class="col-12 col-sm-10 col-md-6 col-lg-6 mb-4 regular-mode allow-drop allow-select">
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts.</p>

                    <p data-mwplaceholder="{{ _e('Enter text here') }}">This is certainly true in astronomy both in terms of terms that refer to the cosmos and terms that describe the tools of the trade, the most prevalent being the telescope. So to get us off of first base, let's define some of the key terms that pertain to telescopes to help you be able to talk to them more intelligently.</p>
                </div>
            </x-row>
</x-layout-section>
