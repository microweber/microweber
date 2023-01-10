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

                        <div class="manage-item-main-top">

                            <a target="_self" href="{{route('admin.product.edit', $content->id)}}" class="btn btn-link p-0">
                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                                    {{$content->title}}
                                </h5>
                            </a>

                            @php
                                $getParentsAsLinks = app()->content_manager->get_parents_as_links($content->id, [
                                    'class'=>'btn btn-link p-0'
                                ])
                            @endphp

                            @if ($getParentsAsLinks)
                                <div class="text-muted">
                                    {!! $getParentsAsLinks !!}
                                </div>
                            @endif

                            @if($content->categories->count() > 0)
                                <span class="manage-post-item-cats-inline-list">
                                @php
                                $iCategory = 0;
                                @endphp
                                @foreach($content->categories as $category)
                                   @if($category->parent)

                                        <a onclick="livewire.emit('selectCategoryFromTableList', {{$category->parent->id}});return false;" href="?filters[category]={{$category->parent->id}}&showFilters[category]=1"
                                           class="btn btn-link p-0 text-muted">
                                    {{$category->parent->title}}
                                     </a>@php
                                            $iCategory++;
                                            if ($content->categories->count() !== $iCategory) {
                                                echo ", ";
                                            }
                                        @endphp

                                    @endif
                                @endforeach
                             </span>
                            @endif
                            <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self"
                               href="{{$content->link()}}">
                                <small class="text-muted">{{$content->link()}}</small>
                            </a>
                        </div>


                        <div class="manage-post-item-links mt-3">

                            <a href="{{$content->editLink()}}" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="{{$content->editLink()}}" class="btn btn-outline-success btn-sm">Live Edit</a>

                            <?php if(!$content->is_deleted): ?>
                            <a href="javascript:mw.admin.content.delete('{{ $content->id }}');" class="btn btn-outline-danger btn-sm js-delete-content-btn-{{ $content->id }}">Delete</a>
                            <?php endif; ?>
                            @if ($content->is_active < 1)
                                <a href="javascript:mw.admin.content.publishContent('{{ $content->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal">Unpublished</a>

                            @endif
                        </div>

                        <?php
                        if ($content->is_deleted) {
                            $data = $content->toArray();
                            include(modules_path() . 'content/views/content_delete_btns.php');
                        }
                        ?>

                    </div>

                    <div class="col item-author manage-post-item-col-4 d-xl-block d-none">
                        <span class="text-muted" title="{{$content->authorName()}}">{{$content->authorName()}}</span>
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
