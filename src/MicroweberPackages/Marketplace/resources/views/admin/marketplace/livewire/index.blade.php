<div>
    <script>mw.require('admin_package_manager.js');</script>





    <div class="card px-5 col-xxl-10 mx-auto">


        <div class="card-body mb-3">
           <div class="row">
               <div class="card-header d-flex align-items-center justify-content-between px-2 flex-wrap mb-4">
                   <div class="col-xl-4 col-md-8 col-12 px-1">
                       <h1><?php _e("Marketplace"); ?></h1>
                       <small class="text-muted"><?php _e("Welcome to the marketplace Here you will find new modules, templates and updates"); ?></small>
                   </div>


                   <div class="col-xl-4 col-md-4 col-12 my-2 my-md-0 flex-grow-1  flex-md-grow-0 px-1 my-xl-0 my-3">


                       <div class="input-icon">
                          <span class="input-icon-addon">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M796 935 533 672q-30 26-69.959 40.5T378 727q-108.162 0-183.081-75Q120 577 120 471t75-181q75-75 181.5-75t181 75Q632 365 632 471.15 632 514 618 554q-14 40-42 75l264 262-44 44ZM377 667q81.25 0 138.125-57.5T572 471q0-81-56.875-138.5T377 275q-82.083 0-139.542 57.5Q180 390 180 471t57.458 138.5Q294.917 667 377 667Z"/></svg>                          </span>
                           <input type="text" class="form-control" placeholder="Search..." wire:keydown.enter="filter" wire:model.lazy="keyword" />
                           <div wire:loading wire:target="keyword" class="spinner-border spinner-border-sm" role="status">
                               <span class="visually-hidden"><?php _e("Searching"); ?>...</span>
                           </div>
                       </div>
                   </div>
                   <div class="col-xl-3 col-12 px-1 text-xl-end">
                       <button type="button" class="btn btn-outline-primary" wire:click="reloadPackages">
                           <div wire:loading wire:target="reloadPackages" class="spinner-border spinner-border-sm" role="status">
                               <span class="visually-hidden"><?php _e("Loading"); ?>...</span>
                           </div>
                           <?php _e("Reload packages"); ?>
                       </button>
                       <button type="button" class="btn btn-outline-success" onclick="mw.admin.admin_package_manager.show_licenses_modal();"><?php _e("Licenses"); ?></button>
                   </div>

               </div>




{{--               <div class="d-flex">--}}
{{--                   <ul class="nav nav-tabs card-header-tabs nav-fill" role="tablist">--}}
{{--                       <li class="nav-link" wire:click="filterCategory('all')" role="presentation">--}}
{{--                           <a wire:target="filterCategory('all')" class="nav-link  @if($category == 'all') active @endif " role="tabs">--}}
{{--                               <?php _e("All"); ?>--}}
{{--                           </a>--}}
{{--                       </li>--}}
{{--                       <li class="nav-link" wire:click="filterCategory('microweber-template')" role="presentation">--}}
{{--                           <a wire:target="filterCategory('microweber-template')" class="nav-link @if($category == 'microweber-template') active @endif" role="tabs">--}}
{{--                               <?php _e("Templates"); ?>--}}
{{--                           </a>--}}

{{--                       </li>--}}
{{--                       <li class="nav-link" wire:click="filterCategory('microweber-module')" role="presentation">--}}
{{--                           <a wire:target="filterCategory('microweber-module')" class=" nav-link @if($category == 'microweber-module') active @endif" role="tabs">--}}
{{--                               <?php _e("Modules"); ?>--}}
{{--                           </a>--}}

{{--                       </li>--}}
{{--                   </ul>--}}
{{--               </div>--}}


               <div class="col-12">
                   <div class="btn-group d-flex">
                       <button type="button" class="btn @if($category == 'all') btn-primary @else btn-outline-primary @endif" wire:click="filterCategory('all')">
                           <div wire:loading wire:target="filterCategory('all')" class="spinner-border spinner-border-sm" role="status">
                               <span class="visually-hidden"><?php _e("Loading"); ?>...</span>
                           </div> <?php _e("All"); ?>
                       </button>
                       <button type="button" class="btn @if($category == 'microweber-template') btn-primary @else btn-outline-primary @endif" wire:click="filterCategory('microweber-template')">
                           <div wire:loading wire:target="filterCategory('microweber-template')" class="spinner-border spinner-border-sm" role="status">
                               <span class="visually-hidden"><?php _e("Loading"); ?>...</span>
                           </div>
                           <?php _e("Templates"); ?>
                       </button>
                       <button type="button" class="btn @if($category == 'microweber-module') btn-primary @else btn-outline-primary @endif" wire:click="filterCategory('microweber-module')">
                           <div wire:loading wire:target="filterCategory('microweber-module')" class="spinner-border spinner-border-sm" role="status">
                               <span class="visually-hidden"><?php _e("Loading"); ?>...</span>
                           </div>
                           <?php _e("Modules"); ?>
                       </button>
                   </div>
               </div>

               <div class="col-12">
                   <div class="row row-cards px-0">

                       @if(!empty($marketplacePagination))
                           @foreach($marketplacePagination as $marketItem)
                               <div class="col-sm-6 col-lg-4">
                                   <div class="card my-1 mx-1 card-sm card-link card-stacked">

                                       @if(isset($marketItem['extra']['_meta']['screenshot']))
                                           <button type="button" class="border-0 d-block" onclick="Livewire.emit('openModal', 'admin-marketplace-item-modal', {{ json_encode(['name'=>$marketItem['name']]) }})">
                                               @if($marketItem['type'] == 'microweber-module')
                                                   <div style="background-image:url({{$marketItem['extra']['_meta']['screenshot']}});width: 100%;height: 180px;background-repeat:no-repeat;background-size: contain;background-position: center;" class="card-img-top">
                                                   </div>
                                               @else
                                                   <div style="background-image:url({{$marketItem['extra']['_meta']['screenshot']}});width: 100%;height: 180px;background-size: cover;background-position: top;" class="card-img-top">
                                                   </div>
                                               @endif
                                           </button>
                                       @else
                                           <button type="button" class="border-0 d-block" onclick="Livewire.emit('openModal', 'admin-marketplace-item-modal', {{ json_encode(['name'=>$marketItem['name']]) }})">
                                               <div class="card-img-top text-center">
                                                   <i class="mdi mdi-view-grid-plus text-muted"
                                                      style="opacity:0.5;font-size:126px;margin-left: 15px;"></i>
                                               </div>
                                           </button>
                                       @endif

                                       <div class="card-body">
                                           <div class="d-flex align-items-center">
                                               <div>
                                                   <b>
                                                       {{$marketItem['description']}}
                                                   </b>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           @endforeach
                           <div class="d-flex justify-content-center mt-4">
                               {!! $marketplacePagination->links('livewire-tables::specific.bootstrap-4.pagination') !!}
                           </div>
                       @else
                           <div class="col-12">
                                   <?php _e("no items"); ?>
                           </div>
                       @endif

                   </div>
               </div>
           </div>
        </div>
    </div>
</div>
