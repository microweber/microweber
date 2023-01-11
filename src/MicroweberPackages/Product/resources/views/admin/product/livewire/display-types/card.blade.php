<div id="content-results-table">
    @foreach ($products as $product)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item">
            <div class="card-body">
                <div class="row align-items-center flex-lg-box">

                    <div class="col text-center manage-post-item-col-1" style="max-width: 40px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="{{ $product->id }}" id="products-{{ $product->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                            <label for="products-{{ $product->id }}" class="custom-control-label"></label>
                        </div>
                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                            <i class="mdi mdi-cursor-move"></i>
                        </span>
                    </div>

                    <div class="col manage-post-item-col-2" style="max-width: 120px;">

                        <div class="mw-admin-product-item-icon text-muted">
                            <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title=""></i>
                        </div>

                        @include('content::admin.content.livewire.components.picture', ['content'=>$product])


                    </div>

                    <div class="col item-title manage-post-item-col-3 manage-post-main">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$product])
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$product])

                    </div>

                    <div class="col item-author manage-post-item-col-4 d-xl-block d-none">
                        <span class="text-muted" title="{{$product->authorName()}}">{{$product->authorName()}}</span>
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
