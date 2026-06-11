{{--
type: layout
name: Footers 30
position: 30
categories: Footers
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-skin-30 footer-background py-0"
    container-class="mw-layout-container no-element"
>
    <!-- Footer -->
        <div class="mw-layout-container no-element container">
            <x-row>
                <div class="d-md-flex text-center">
                    <div class="col-sm-6 text-md-start text-center edit safe-mode copyright-text" field="footer-reserved-skin-25-{{ $params['id'] }}" rel="module">
                        <small>
                            © All Rights Reserved.
                        </small>
                    </div>
                    <div class="col-sm-6 mb-0 noedit text-md-end text-center">
                        <small>
                            {!! powered_by_link() !!}
                        </small>
                    </div>
                </div>
            </x-row>
        </div>
</x-layout-section>
