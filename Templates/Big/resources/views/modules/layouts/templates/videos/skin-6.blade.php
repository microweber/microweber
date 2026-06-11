{{--
type: layout

name: Videos 6

position: 6

categories: Videos
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-video-skin-6"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row class="text-center">
                    <div class="col-md-6 text-left pt-4 mx-auto element regular-mode">
                        <h1 data-mwplaceholder="{{ __('Enter title here') }}">Share it.</h1>
                        <p data-mwplaceholder="{{ __('Enter text here') }}">
                            Clearly define your idea, business, hobby, or project. What is its purpose? What problem does it solve? Having a clear vision will help you articulate your story more effectively.
                            <br><br><br>
                            Understand your target audience. Who are they? What are their interests, needs, and pain points? Tailor your story to resonate with them.
                        </p>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" />
                    </div>
                </x-row>
</x-layout-section>
