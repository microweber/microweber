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
        @if (count($contents)==0)
            <tr>
                <td colspan="{{count($showColumns)}}">
                    {{_e('No items found')}}
                </td>
            </tr>
        @endif
        @foreach ($contents as $content)
            <tr class="manage-post-item manage-post-item-{{ $content->id }}">
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                        <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                    </div>

                    <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                    <i class="mdi mdi-cursor-move"></i>
                </span>
                </td>
                @if($showColumns['id'])
                    <td>
                        {{ $content->id }}
                    </td>
                @endif
                @if($showColumns['image'])
                    <td style="width:160px;">
                        @if($content->media()->first())
                            <img src="{{$content->thumbnail(200,200)}}" class="rounded-full">
                        @else
                            @include('content::admin.content.livewire.components.icon', ['content'=>$content])
                        @endif
                    </td>
                @endif
                @if($showColumns['title'])
                    <td>

                        <div class="manage-item-main-top">

                            <a target="_self" href="{{route('admin.page.edit', $content->id)}}" class="btn btn-link p-0">
                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                                    {{$content->title}}
                                </h5>
                            </a>

                            @php
                                $getParentsAsLinks = app()->content_manager->get_parents_as_links($content->id, [
                                    'class'=>'btn btn-link p-0 text-muted mw-products-breadcrumb',
                                    'implode_symbol'=>' / ',
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
                                               class="btn btn-link btn-sm p-0">
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

                    </td>
                @endif

                @if($showColumns['author'])
                    <td>
                        {{$content->authorName()}}
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
