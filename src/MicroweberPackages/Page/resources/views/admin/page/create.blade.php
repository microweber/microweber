@extends('admin::layouts.app')

@section('content')


    @if(isset($layout) && $layout)
        @include('page::admin.page.edit', ['layout' => $layout])
    @else
    <div class="row px-5 mx-5">

        <h3 class="main-pages-title">{{ _e("Types of pages") }}</h3>
        <small class="text-muted">{{ _e("Select the type of page to create. You can change the content as well as add new layouts to further develop the functionality") }}</small>

        <div class="row row-cards px-3">

            <div class="col-xxl-4 col-lg-6 col-12 pe-lg-6 ps-lg-0 pt-3 mb-4">

                <a href="{{route('admin.page.create')}}?layout=clean" class="card card-link card-link-pop mw-create-page-admin-wrapper">
                    <div id="mw-create-page-card-overlay"></div>

                    <div class="card-body" style="padding: 13px; height: calc(50vh - 80px);">
                        <div class="create-page-clean d-flex justify-content-center align-items-center h-100">
                            <h4 class="font-weight-bold mb-0">{{_e("Empty Page")}}</h4>
                        </div>
                    </div>

                    <span class="btn btn-primary mw-create-page-admin-create-btn">
                        {{ _e("Create") }}
                    </span>

                </a>
                <h2 class="mt-3 mb-2 font-weight-bold">{{_e("Clean Page")}} </h2>
                    <p>{{ _e("Start clean with a blank page and create a page layout of your own design.") }}</p>


            </div>

            @foreach($allLayouts as $layout)

                @php
                    if($layout['layout_file'] == 'clean.php') {
                        continue;
                    }


                  $layout_details_for_new_page = app()->layouts_manager->get_layout_details([
                        'layout_file' => $layout['layout_file'],
                        'active_site_template' => ACTIVE_SITE_TEMPLATE
                    ]);

                    $isDynamic = false;
                    $isShop = false;

                    if(isset($layout_details_for_new_page['content_type']) and $layout_details_for_new_page['content_type'] == 'dynamic'){
                        $isDynamic = true;
                         $isShop = false;
                    }
                    if(isset($layout_details_for_new_page['is_shop']) and $layout_details_for_new_page['is_shop'] != 0){
                        $isDynamic = false;
                        $isShop = true;
                    }





                @endphp

                <div class="col-xxl-4 col-lg-6 col-12 pe-lg-6 ps-lg-0 pt-3 mb-4 ">
                    <div class="card mw-create-page-admin-wrapper">
                        <div id="mw-create-page-card-overlay"></div>
                        @php
                                $iframe_start = site_url('new-content-preview-'. uniqid());
                            @endphp
                            @include('page::admin.page.iframe', [
                             'url'=>site_url('new-content-preview-'. uniqid() . '?content_id=0&no_editmode=true&preview_layout=' . $layout['layout_file_preview']
                        )])
                        <div class="p-2 text-center mw-create-page-admin-create-btn">
                            <a href="{{route('admin.page.create')}}?layout={{$layout['layout_file_preview']}}" class="btn btn-primary">
                                {{ _e("Create") }}

                            </a>
                        </div>
                    </div>

                    <h2 class="mt-3 mb-2 font-weight-bold">{{$layout['name']}}
                        @if($isDynamic)
                            <span class="live-edit-label  d-inline-block"><?php _e('Dynamic page') ?></span>
                        @endif
                        @if($isShop)
                            <span class="live-edit-label  d-inline-block"><?php _e('Shop page') ?></span>
                        @endif

                    </h2>
                    <p>  {{ _e("Start clean with a blank page and create a page layout of your own design.") }}</p>


                </div>

            @endforeach

        </div>
    </div>
    @endif

@endsection
