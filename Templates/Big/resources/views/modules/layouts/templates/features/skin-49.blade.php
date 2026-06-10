{{--
 type: layout
 name: Feature 49
 position: 49
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .features-49-boxes:hover {
        transition: 0.3s;
        transform: scale(1.1);
    }

    .features-49-boxes {
        transition: 0.3s;
        min-height: 100%;
    }

    .mw-live-edit {
        .features-49-boxes:hover {
            transform: none !important;
            transition: none !important;
        }
    }

    .features-49-boxes {
        img, i {
            margin: 10px auto;
        }
    }

    .feature-49 .row {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
    }
</style>

<section class="section feature-49 {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container no-element container edit" field="layout-feature-skin-49-{{ $params['id'] }}" rel="module">
        <div class="row d-flex">
            <div class="mb-4 text-center">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Our solutions</h2>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Before we discuss all of the things that could be affecting your.</p>
            </div>

            <div class="col-sm-10 col-md-6 col-xl-3 mb-4 cloneable element safe-mode">
                <a class="d-flex flex-column text-center shadow features-49-boxes background-color-element element p-4 safe-mode">
                    <i class="mb-4 d-inline-block safe-element no-typing mw-micon-Address-Book" style="font-size: 40px;" data-mw-live-edithover="true"></i>
                    <div class="safe-mode">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Feature Title</h5>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Before we discuss all of the things that could be affecting your.</p>
                    </div>
                </a>
            </div>

            <div class="col-sm-10 col-md-6 col-xl-3 mb-4 cloneable element safe-mode">
                <a class="d-flex flex-column text-center shadow features-49-boxes background-color-element element p-4 safe-mode">
                    <i class="mb-4 d-inline-block safe-element no-typing mw-micon-Address-Book" style="font-size: 40px;" data-mw-live-edithover="true"></i>
                    <div class="safe-mode">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Feature Title</h5>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Before we discuss all of the things that could be affecting your.</p>
                    </div>
                </a>
            </div>

            <div class="col-sm-10 col-md-6 col-xl-3 mb-4 cloneable element safe-mode">
                <a class="d-flex flex-column text-center shadow features-49-boxes background-color-element element p-4 safe-mode">
                    <i class="mb-4 d-inline-block safe-element no-typing mw-micon-Address-Book" style="font-size: 40px;" data-mw-live-edithover="true"></i>
                    <div class="safe-mode">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Feature Title</h5>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Before we discuss all of the things that could be affecting your.</p>
                    </div>
                </a>
            </div>

            <div class="col-sm-10 col-md-6 col-xl-3 mb-4 cloneable element safe-mode">
                <a class="d-flex flex-column text-center shadow features-49-boxes background-color-element element p-4 safe-mode">
                    <i class="mb-4 d-inline-block safe-element no-typing mw-micon-Address-Book" style="font-size: 40px;" data-mw-live-edithover="true"></i>
                    <div class="safe-mode">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Feature Title</h5>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Before we discuss all of the things that could be affecting your.</p>
                    </div>
                </a>
            </div>

            <module type="btn" button_style="btn btn-primary" button_text="See all features" style="text-align: center;" />
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
