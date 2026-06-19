{{--
type: layout

name: Content 59

position: 59

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-59"
    container-class="mw-layout-container container safe-mode no-element edit safe-mode"
>
    <x-row class="text-center">
                <div class="col-12  ">
                    <div class="regular-mode allow-drop allow-select">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}">In The Desert</h3>
                        <p data-mwplaceholder="{{ _e('Enter title here') }}">In addition to the 2.0.3 install, you should be aware that some bugs have already been found, and that a plugin will need
                        <br> to be installed to repair those bugs. If you modify any of the files</p>
                    </div>
                    <x-row class="text-center mt-5">
                       <div class="col-md-6 cloneable element safe-mode allow-drop allow-select">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-9.jpg') }}" alt="">

                           <div class="mt-3 regular-mode">
                               <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Shooting Stars</h4>
                               <p data-mwplaceholder="{{ _e('Enter title here') }}">There is something about parenthood that gives us a sense of history and a deeply rooted desire.</p>
                                <module type="btn" button_style="btn-primary"  text="Explore"/>

                           </div>
                       </div>

                        <div class="col-md-6 cloneable element safe-mode allow-drop allow-select">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-12.jpg') }}" alt="">

                            <div class="mt-3 regular-mode">

                                <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Moon Fever</h4>
                                <p data-mwplaceholder="{{ _e('Enter title here') }}">Something about parenthood that gives us a sense of history and a deeply rooted desire.</p>
                                <module type="btn" button_style="btn-primary"  text="Explore"/>

                            </div>
                        </div>
                    </x-row>
                </div>
            </x-row>
</x-layout-section>
