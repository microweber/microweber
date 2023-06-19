@extends('admin::layouts.app')


@section('topbar2-links-left')

   <div class="d-flex align-items-center">
       <div class="mw-toolbar-back-button-wrapper">
           <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 d-flex align-items-center" id="mw-modules-toolbar" style="">

               <div>
                   <a href="{{route('admin.product.index')}}">
                       <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                   </a>
               </div>


           </div>


       </div>

       <div class="ms-3 mb-3 d-flex align-items-center">
           <h3 class="mb-0">{{ "Edit Product" }}
               @if($content_id > 0)
                   / {{ content_title($content_id) }}
               @endif
           </h3>

           <a href="{{route('admin.product.create')}}" class="btn btn-link font-weight-bold tblr-body-color text-decoration-none ms-3">
               <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>
               {{ "New Product" }}
           </a>
       </div>
   </div>

@endsection

@section('content')

@if(isset($recommended_content_id) and isset($recommended_category_id) and $content_id == 0)
    <module type="content/edit" content_id="{{$content_id}}" content_type="product"
            parent="{{$recommended_content_id}}" id="main-content-edit-admin"
            category="{{$recommended_category_id}}"   />
@else
    <module type="content/edit" content_id="{{$content_id}}" content_type="product"  id="main-content-edit-admin" />
@endif


@endsection
