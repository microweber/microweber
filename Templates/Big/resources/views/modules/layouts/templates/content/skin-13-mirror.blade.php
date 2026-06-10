@php
/*

type: layout

name: Content 13 Mirror

position: 13 mirror

categories: Content

*/
@endphp

<style>
    .content-13-images {
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }

    .content-13-images img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    @media (max-width: 1250px) {
        #{{ $params['id'] }} .container > .row > .col-12{
            width: 100% !important;
        }
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-13-mirror"
    container-class="mw-layout-container safe-mode no-element"
>
    <x-row>
        <x-col size="12" size-sm="10" size-lg="6" class="mx-auto py-5 order-1 order-lg-2">
            <div class="d-flex flex-column ps-md-5 h-100 py-5 regular-mode">
                <x-section-heading tag="h3" align="start">Your Awesome Title</x-section-heading>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Having used discount toner cartridges for twenty years, there have been a lot of changes in the toner cartridge market. The market today is approximately a twenty billion.</p>
                <module type="btn" text="Lean more" button_style="btn-primary"/>
            </div>
        </x-col>

        <x-col size="12" size-sm="10" size-lg="6" class="mx-auto order-2 order-lg-1 safe-mode">
            <div class="col-md-12 text-end py-5">
                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <div class="m-4 d-flex d-sm-block allow-select">
                        <div class="content-13-images w-250 h-250 cloneable element ms-auto mb-6 me-3 me-sm-0">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                        </div>
                        <div class="content-13-images w-150 h-150 cloneable element ms-auto">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                        </div>
                    </div>
                    <div class="m-4 allow-select">
                        <div class="content-13-images w-350 h-350 cloneable element">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
