@extends('admin::layouts.app')

@section('content')

    <style>
        .preview_frame_container.preview-in-self {
            height: calc(80vh - 80px);
        }
        .preview_frame_container {
            position: relative;
            overflow: hidden;
        }
        .preview_frame_container.preview-in-self iframe {
            height: calc(160vh - 160px) !important;
        }
        .preview_frame_container iframe {
            width: 200%;
            transform: scale(.5);
            top: 0;
            position: absolute;
            left: 0;
            transform-origin: 0 0;
            border: 1px solid silver;
            transition: .3s;
        }
    </style>
<div class="col-xxl-10 col-11 mx-auto">
    <h3 class="main-pages-title"><?php _e("Design") ?>
        <svg style="opacity: .6; margin-bottom: 5px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M484-247q16 0 27-11t11-27q0-16-11-27t-27-11q-16 0-27 11t-11 27q0 16 11 27t27 11Zm-35-146h59q0-26 6.5-47.5T555-490q31-26 44-51t13-55q0-53-34.5-85T486-713q-49 0-86.5 24.5T345-621l53 20q11-28 33-43.5t52-15.5q34 0 55 18.5t21 47.5q0 22-13 41.5T508-512q-30 26-44.5 51.5T449-393Zm31 313q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-156t86-127Q252-817 325-848.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82-31.5 155T763-197.5q-54 54.5-127 86T480-80Zm0-60q142 0 241-99.5T820-480q0-142-99-241t-241-99q-141 0-240.5 99T140-480q0 141 99.5 240.5T480-140Zm0-340Z"/></svg>
    </h3>

    <div class="card" x-data="{previewUrl: '{{site_url()}}'}">
        <div class="card-body">
            <div class="row">
                <div class="d-flex align-items-center flex-wrap justify-content-between mb-4">
                    <div class="col-lg-6 col-12 mb-xl-0 mb-2">

                       <div class="mb-4">
                           <h2 class="mb-2">
                               {{$templateName}}
                           </h2>
                           <p>
                               {{$templateVersion}}
                           </p>
                       </div>

                        <div class="d-flex align-items-center gap-3">
                            <a class="tblr-body-color font-weight-bold text-decoration-none" href="{{admin_url('settings?group=general')}}">

                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M180-180h44l443-443-44-44-443 443v44Zm614-486L666-794l42-42q17-17 42-17t42 17l44 44q17 17 17 42t-17 42l-42 42Zm-42 42L248-120H120v-128l504-504 128 128Zm-107-21-22-22 44 44-22-22Z"/></svg>
                                <?php _e("Site Details") ?>
                            </a>
                            <a class="tblr-body-color font-weight-bold text-decoration-none" href="{{admin_url('marketplace?keyword=&category=microweber-template')}}">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M840-519v339q0 24-18 42t-42 18H179q-24 0-42-18t-18-42v-339q-28-24-37-59t2-70l43-135q8-27 28-42t46-15h553q28 0 49 15.5t29 41.5l44 135q11 35 1.5 70T840-519Zm-270-31q29 0 49-19t16-46l-25-165H510v165q0 26 17 45.5t43 19.5Zm-187 0q28 0 47.5-19t19.5-46v-165H350l-25 165q-4 26 14 45.5t44 19.5Zm-182 0q24 0 41.5-16.5T263-607l26-173H189l-46 146q-10 31 8 57.5t50 26.5Zm557 0q32 0 50.5-26t8.5-58l-46-146H671l26 173q3 24 20.5 40.5T758-550ZM179-180h601v-311q1 1-6.5 1H758q-25 0-47.5-10.5T666-533q-16 20-40 31.5T573-490q-30 0-51.5-8.5T480-527q-15 18-38 27.5t-52 9.5q-31 0-55-11t-41-32q-24 21-47 32t-46 11h-13.5q-6.5 0-8.5-1v311Zm601 0H179h601Z"/></svg>
                                <?php _e("Template Store") ?>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 text-end">
                        <a href="" class="btn btn-outline-primary btn-sm"><?php _e("Preview") ?></a>
                        <a href="" class="btn btn-primary btn-sm"><?php _e("Customize") ?></a>
                    </div>
                </div>

                <div class="card mb-4">

                   <div class="card-body shadow-sm p-0">
                       <div class="row">
                           <div class="card-header d-flex flex-wrap align-items-center justify-content-between shadow-sm p-3">
                              <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                                  <select x-model="previewUrl" class="form-select border-0">
                                      @foreach($allLayouts as $layout)
                                          <option value="{{$layout['layout_file_preview_url']}}">{{$layout['name']}}</option>
                                      @endforeach
                                  </select>
                              </div>

                               <div class="col-xl-9 text-end">
                                   <button type="button" @click="" class="btn btn-link me-2"><?php _e("Next Layout") ?></button>
                                   <button type="button" class="btn btn-link"><?php _e("Previous Layout") ?></button>
                               </div>
                           </div>
                           <div class="mt-3 tblr-body-bg p-xxl-7 p-xl-4 p-2">

                               {{--                        <div x-html="previewUrl"></div>--}}
                               <div class="preview_frame_container preview-in-self">
                                   <iframe class="preview_frame_small" :src="previewUrl"></iframe>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
