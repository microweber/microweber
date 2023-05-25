@extends('admin::layouts.app')

@section('content')

    <div class="row px-5">

        <h3 class="main-pages-title">{{ _e("Create Category") }}</h3>

        <div class="d-flex justify-content-center row-cards">

            <div class="col-md-4 pe-5 pb-3">
                <a href="{{route('admin.category.create')}}?parent=blog" class="card card-link card-link-pop">

                    <div class="card-body" style="padding: 13px; height: calc(50vh - 80px);">
                        <div class="d-flex flex-column text-center justify-content-center align-items-center h-100">
                            <div>
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 96 960 960" width="64"><path d="M480 976q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm-40-82v-78q-33 0-56.5-23.5T360 736v-40L168 504q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440 894Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600 280v16q0 33-23.5 56.5T520 376h-80v80q0 17-11.5 28.5T400 496h-80v80h240q17 0 28.5 11.5T600 616v120h40q26 0 47 15.5t29 40.5Z"></path></svg>
                            </div>
                            <h3 class="font-weight-bold mb-0 mt-2">
                                {{_e("Create category")}}
                                <br />
                                {{_e("in Website")}}
                            </h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 pe-5 pb-3">
                <a href="{{route('admin.category.create')}}?parent=shop" class="card card-link card-link-pop">

                    <div class="card-body" style="padding: 13px; height: calc(50vh - 80px);">
                        <div class="d-flex flex-column text-center justify-content-center align-items-center h-100">
                            <div>
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 96 960 960" width="64"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"></path></svg>
                            </div>
                            <h3 class="font-weight-bold mb-0 mt-2">
                                {{_e("Create category")}}
                                <br />
                                {{_e("in Shop")}}
                            </h3>
                        </div>
                    </div>

                </a>
            </div>

        </div>

    </div>

@endsection