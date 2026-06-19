{{--
type: layout
name: Header 24 - Parallax
position: 24
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 mw-layout-parallax d-flex align-items-center justify-content-center"
    :has-spacers="false"
    default-padding-top="fluid-p-t"
    default-padding-bottom="fluid-p-b"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/hero.jpg') }}"/>
        <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-24-{{ $params['id'] ?? '' }}" rel="module">
            <x-row class="text-center">
                <div class="col-12 safe-mode d-flex flex-column justify-content-center align-items-center">
                    <div class="allow-select allow-drop">
                        <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title display-1 mb-lg-3" style="font-weight: 800; letter-spacing: 8px;">CATHERINE<br class="d-lg-none"><span class="text-dark"> & </span><br class="d-lg-none">OLIVER</h1>
                        <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p -2" style="font-weight: 400;">12th of July. Downtown Brooklyn, New York.</p>
                    </div>
                </div>
            </x-row>
        </div>
</x-layout-section>
