{{--
type: layout
name: Header 29
position: 29
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 edit"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <div class="mw-layout-container py-4 no-element mw-big-skin-3-background position-relative">
            <div class="container-fluid mw-layout-overlay-container mb-8 {{ $layout_classes ?? '' }}">
                <x-row>
                    <div class="col-12 safe-mode col-xl-10 mx-auto">
                        <x-row class="d-flex align-items-center justify-content-center">
                            <div class="col-12 safe-mode col-xl-7 py-4">
                                <h1 class="header-section-title" style="color: #ffffff; font-size: 62px;">A Podcast To Discuss <br> All Things Related To <br> The Workplace.</h1>
                                <p class="header-section-p my-7" style="color: #ffffff;">We Are must explain to you how all this mistaken idea of
                                    <br> denouncing pleasure and praising pain was born and I will give you <br> a complete account of the system.</p>

                                <div class="d-flex align-items-center justify-content-xl-start justify-content-center cloneable mt-8">
                                    <div class="me-3">
                                        <module type="btn" button_style="btn btn-primary" button_size="btn-md px-5 py-4" text="Start Listening"/>
                                    </div>
                                    <div class="me-1 me-3">
                                        <module type="btn" button_style="btn btn-outline-primary" button_size="btn-md px-5 py-4" text="All Episodes"/>
                                    </div>
                                </div>
                            </div>

                            <div class="safe-mode col-12 col-xl-5">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-header-img.png') }}" alt=""/>
                            </div>

                            <div class="col-xl-12 px-lg-0 d-flex flex-wrap justify-content-center mx-auto py-5 mt-3 position-relative" style="top: 130px;">
                                <div class="col-lg-3 col-md-6 col-12 safe-mode py-lg-0 my-3 text-center cloneable element"><img loading="lazy" height="auto" width="auto" src="{{ asset('templates/big/img/layouts/merry/whitbread-merry-header-1.png') }}" class="" alt=""/></div>
                                <div class="col-lg-3 col-md-6 col-12 safe-mode py-lg-0 my-3 text-center cloneable element"><img loading="lazy" height="auto" width="auto" src="{{ asset('templates/big/img/layouts/merry/whitbread-merry-header-2.png') }}" class="" alt=""/></div>
                                <div class="col-lg-3 col-md-6 col-12 safe-mode py-lg-0 my-3 text-center cloneable element"><img loading="lazy" height="auto" width="auto" src="{{ asset('templates/big/img/layouts/merry/whitbread-merry-header-3.png') }}" class="" alt=""/></div>
                                <div class="col-lg-3 col-md-6 col-12 safe-mode py-lg-0 my-3 text-center cloneable element"><img loading="lazy" height="auto" width="auto" src="{{ asset('templates/big/img/layouts/merry/whitbread-merry-header-4.png') }}" class="" alt=""/></div>
                            </div>
                        </x-row>
                    </div>
                </x-row>
            </div>
        </div>
        <module type="spacer" height="100px"  id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</x-layout-section>
