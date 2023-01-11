<div>
    <table class="table table-responsive" id="content-results-table">
        <thead>
        <tr>
            <th style="width:10px" scope="col">
                @include('content::admin.content.livewire.table-includes.select-all-checkbox')
            </th>
            @if($showColumns['id'])
                @include('content::admin.content.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['image'])
                <th style="width: 130px" scope="col">Image</th>
            @endif
            @if($showColumns['title'])
                <th scope="col">Title</th>
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
                        <input type="checkbox" value="{{ $content->id }}" id="js-content-checkbox-{{ $content->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                        <label for="js-content-checkbox-{{ $content->id }}" class="custom-control-label"></label>
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
                    <td>
                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])
                    </td>
                @endif
                @if($showColumns['title'])
                    <td>

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])

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
