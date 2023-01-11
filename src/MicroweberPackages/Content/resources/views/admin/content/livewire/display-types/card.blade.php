<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item">
            <div class="card-body">
                <div class="row align-items-center flex-lg-box">

                    <div class="col text-center manage-post-item-col-1" style="max-width: 40px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action custom-control-input" wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>
                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                            <i class="mdi mdi-cursor-move"></i>
                        </span>
                    </div>

                    <div class="col manage-post-item-col-2" style="max-width: 120px;">

                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])

                    </div>

                    <div class="col item-title manage-post-item-col-3 manage-post-main">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])

                    </div>

                    <div class="col item-author manage-post-item-col-4 d-xl-block d-none">
                        <span class="text-muted" title="{{$content->authorName()}}">{{$content->authorName()}}</span>
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
