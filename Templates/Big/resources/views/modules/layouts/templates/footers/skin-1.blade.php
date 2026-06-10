{{--
type: layout
name: Footers 1
position: 1
categories: Footers
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-background py-0"
    field-name="layout-footer-skin-1"
    :has-spacers="true"
    container-class="mw-layout-container no-element text-md-start text-center"
>
    <x-row>
        <x-col size-md="2" class="me-4">
            <div class="pb-7">
                <module type="logo" id="footer-logo-{{ $params['id'] }}" />
            </div>

            <p class="font-weight-bold">Website Builder and CMS </p>
            <br>
            <small>CMS is a website builder and content management system of new generation.</small>
            <br>
            <x-social-links />
        </x-col>
        <div class="row col-md-10 row px-md-10">
            <div class="col">
                <p class="font-weight-bold ms-3"> Footer Menu  </p>
                <module type="menu" template="simple" class="pb-lg-4" name="footer_menu"/>
            </div>

            <div class="col">
                <p class="font-weight-bold ms-3"> Footer Menu 1 </p>
                <module type="menu" template="simple" name="footer_menu"/>
            </div>

            <div class="col">
                <p class="font-weight-bold ms-3"> Footer Menu 2 </p>
                <module type="menu" template="simple" name="footer_menu"/>
            </div>
        </div>
    </x-row>
</x-layout-section>

<div class="mw-layout-container no-element container-fluid py-2">
    <div class="row">
        <div class="col-12 d-sm-flex text-center">
            <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-1-{{ $params['id'] }}" rel="module">
                <small>
                    &copy; All Rights Reserved.
                </small>
            </div>
            <div class="col-sm-6 mb-0 noedit text-md-end text-center">
                <small>
                    {!! powered_by_link() !!}
                </small>
            </div>
        </div>
    </div>
</div>
