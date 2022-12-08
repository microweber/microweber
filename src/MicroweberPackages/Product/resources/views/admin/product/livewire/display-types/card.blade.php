<div>
    @foreach ($products as $product)

        <div class="card card-product-holder mb-2 post-has-image-true">
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

                        @if($product->media()->first())
                            <img src="{{$product->thumbnail(200,200)}}" class="rounded-full">
                        @else
                            <div class="img-circle-holder border-radius-0 border-0">
                                <i class="mdi mdi-shopping mdi-48px text-muted text-opacity-5"></i>
                            </div>
                        @endif

                    </div>

                    <div class="col item-title manage-post-item-col-3 manage-post-main">

                        <div class="manage-item-main-top">

                            <a target="_self" href="{{route('admin.product.edit', $product->id)}}" class="btn btn-link p-0">
                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                                    {{$product->title}}
                                </h5>
                            </a>
                            @if($product->categories->count() > 0)
                                <span class="manage-post-item-cats-inline-list">
                                @foreach($product->categories as $category)
                                        @if($category->parent)

                                            <a onclick="livewire.emit('selectCategoryFromTableList', {{$category->parent->id}});return false;" href="?filters[category]={{$category->parent->id}}&showFilters[category]=1"
                                               class="btn btn-link p-0 text-muted">
                                        {{$category->parent->title}}
                                    </a>

                                        @endif
                                    @endforeach
                             </span>
                            @endif
                            <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self"
                               href="{{$product->link()}}">
                                <small class="text-muted">{{$product->link()}}</small>
                            </a>
                        </div>


                        <div class="manage-post-item-links mt-3">
                            <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-success btn-sm">Live Edit</a>
                            <?php if(!$product->is_deleted): ?>
                            <a href="javascript:mw.admin.content.delete('{{ $product->id }}');" class="btn btn-outline-danger btn-sm js-delete-content-btn-{{ $product->id }}">Delete</a>
                            <?php endif; ?>
                            @if ($product->is_active < 1)
                                <a href="javascript:mw.admin.content.publishContent('{{ $product->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal">Unpublished</a>

                            @endif
                        </div>

                        <?php
                        if ($product->is_deleted) {
                            $data = $product->toArray();
                            include(modules_path() . 'content/views/content_delete_btns.php');
                        }
                        ?>

                    </div>

                    <div class="col item-author manage-post-item-col-4 d-xl-block d-none">
                        <span class="text-muted" title="{{$product->authorName()}}">{{$product->authorName()}}</span>
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
