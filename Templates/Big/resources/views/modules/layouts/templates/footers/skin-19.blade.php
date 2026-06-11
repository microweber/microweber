{{--
type: layout
name: Footers 19
position: 19
categories: Footers
--}}

<style>
    .footer-19-menu ul li a:first-child{
        padding-left: 0;
    }

    .footer-19-menu ul{
        display: flex;
        flex-wrap: wrap;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-background py-0"
    field-name="layout-footer-skin-19"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-md-start text-center">
        <div class="col-lg-5 col text-md-start text-md-left">
            <div class="edit" field="layout-footer-skin-19-p-{{ $params['id'] }}" rel="module">
                <p class="font-weight-bold">Website Builder and CMS </p>
                <br>
                <small>This is a website builder and content management system of new generation.</small>
                <br>
            </div>
            <module type="menu" class="footer-19-menu d-flex justify-content-lg-start justify-content-center ps-0 mt-3" template="simple" name="footer_menu"/>
        </div>
        <div class="col-lg-4">
            <div class="edit" field="layout-footer-skin-19-phone{{ $params['id'] }}" rel="module">
                <small> Phone </small>
                <p class="mt-2">123-456-7890</p>
            </div>
            <div class="edit" field="layout-footer-skin-19-email{{ $params['id'] }}" rel="module">
                <small> Email </small>
                <p class="mt-2"><a href="">mail@yourcompany.com</a></p>
            </div>
            <div class="edit" field="layout-footer-skin-19-social{{ $params['id'] }}" rel="module">
                <p> Social </p>
                <x-social-links />
            </div>
        </div>
        <div class="col-lg-3 edit" field="layout-footer-skin-19-country{{ $params['id'] }}" rel="module">
            <small> California </small>
            <p class="mt-2"> 21 Lebsack Harbor Apt. 276 Palo Alto, CA </p>
            <small> New York </small>
            <p class="mt-2"> 74 Howell Islands Suite 834 Rochester, NY </p>
        </div>
    </x-row>
</x-layout-section>

<x-footer-section copyright-field="footer-reserved-skin-19" :section-id="$params['id']" />
