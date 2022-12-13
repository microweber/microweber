<div>
    <table class="table table-responsive" id="content-results-table">
        <thead>
        <tr>
            <th scope="col">
                <div class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" wire:model="selectAll" id="select-all-products" class="custom-control-input">
                    <label for="select-all-products" class="custom-control-label"></label>
                </div>
            </th>
            @if($showColumns['id'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['image'])
                <th scope="col">Image</th>
            @endif
            @if($showColumns['title'])
                <th scope="col" style="width:100%">Title</th>
            @endif
            @if($showColumns['author'])
                <th scope="col">Author</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if (count($posts)==0)
            <tr>
                <td colspan="{{count($showColumns)}}">
                    {{_e('No items found')}}
                </td>
            </tr>
        @endif
        @foreach ($posts as $post)
            <tr class="manage-post-item manage-post-item-{{ $post->id }}">
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="{{ $post->id }}" id="products-{{ $post->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                        <label for="products-{{ $post->id }}" class="custom-control-label"></label>
                    </div>

                    <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                    <i class="mdi mdi-cursor-move"></i>
                </span>
                </td>
                @if($showColumns['id'])
                    <td>
                        {{ $post->id }}
                    </td>
                @endif
                @if($showColumns['image'])
                    <td>
                        @if($post->media()->first())
                            <img src="{{$post->thumbnail(200,200)}}" class="rounded-full">
                        @else
                            <div class="img-circle-holder border-radius-0 border-0">
                                <i class="mdi mdi-shopping mdi-48px text-muted text-opacity-5"></i>
                            </div>
                        @endif
                    </td>
                @endif
                @if($showColumns['title'])
                    <td>

                        <div class="manage-item-main-top">

                            <a target="_self" href="{{route('admin.page.edit', $post->id)}}" class="btn btn-link p-0">
                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                                    {{$post->title}}
                                </h5>
                            </a>
                            @if($post->categories->count() > 0)
                                <span class="manage-post-item-cats-inline-list">
                                @foreach($post->categories as $category)
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
                               href="{{$post->link()}}">
                                <small class="text-muted">{{$post->link()}}</small>
                            </a>
                        </div>

                        <div class="manage-post-item-links mt-3">
                            <a href="{{route('admin.page.edit', $post->id)}}" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="{{route('admin.page.edit', $post->id)}}" class="btn btn-outline-success btn-sm">Live Edit</a>
                            <?php if(!$post->is_deleted): ?>
                            <a href="javascript:mw.admin.content.delete('{{ $post->id }}');" class="btn btn-outline-danger btn-sm js-delete-content-btn-{{ $post->id }}">Delete</a>
                            <?php endif; ?>
                            @if ($post->is_active < 1)
                                <a href="javascript:mw.admin.content.publishContent('{{ $post->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal">Unpublished</a>

                            @endif
                        </div>

                    </td>
                @endif

                @if($showColumns['author'])
                    <td>
                        {{$post->authorName()}}
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
