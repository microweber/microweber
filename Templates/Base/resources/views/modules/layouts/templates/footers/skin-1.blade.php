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
        <x-row class="col-md-10 row px-md-10">
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
        </x-row>
    </x-row>
</x-layout-section>

<x-footer-section copyright-field="footer-reserved-skin-1" :section-id="$params['id']" />
