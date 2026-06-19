{{--
type: layout

    name: Content 81

    position: 81

    categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section section-content-80 pb-0"
    field-name="layout-content-skin-81"
    container-class="mw-layout-container container-fluid safe-mode no-element edit"
>
    <x-row>
                <div class="col-12 col-lg-6 mx-auto text-center text-lg-start cloneable element background-color-element safe-mode d-flex flex-column align-items-start">
                    <div class="safe-mode">
                        <div class="d-inline regular-mode">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Register To Listen To You <br> Favorite Podcast.</h3>
                            <br>
                        </div>
                        <div class="regular-mode">
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="py-4" style="color: #58585D;">Sed Ut Perspiciatis unde omnis iste natus error sit voluptatem
                                <br> we denounce with righteous indignation and dislike men who <br> are so beguiled and demoralized
                            </p>
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-wrap mx-auto justify-content-center safe-mode">
                        <div class="col-md-4 col-12 mx-xl-0 mx-3 safe-mode cloneable element">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">2K+</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Podcasts</p>
                        </div>
                        <div class="col-md-4 col-12 mx-xl-0 mx-3 safe-mode cloneable element">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">10K+</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Active Users</p>
                        </div>
                        <div class="col-md-4 col-12 mx-xl-0 mx-3 safe-mode cloneable element">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">190K+</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Podcasts</p>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <module type="btn" button_style="btn-primary" text="Read More"/>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mx-auto cloneable element background-color-element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt=""/>
                </div>
            </x-row>
</x-layout-section>
