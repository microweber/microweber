<div class="table-responsive">
    <table class="table table table-vcenter card-table" id="content-results-table">
        <thead>
        <tr>
            <th style="width:10px" scope="col">
                @include('content::admin.content.livewire.table-includes.select-all-checkbox')
            </th>
            @if($showColumns['id'])
                @include('content::admin.content.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['image'])
                <th style="width: 130px" scope="col">{{'Image'}}</th>
            @endif
            @if($showColumns['title'])
                <th scope="col">{{'Title'}}</th>
            @endif
            @if($showColumns['author'])
                <th scope="col">{{'Author'}}</th>
            @endif

            @if($showColumns['id'])
                <th scope="col" class="text-end">{{'Actions'}}</th>
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
                <td style="vertical-align: middle;">
                    <div class="d-flex align-items-center">
                        <span class="cursor-move-holder me-2 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                              <span href="javascript:;" class="btn btn-link text-blue-lt">
                                  <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                              </span>
                        </span>
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" value="{{ $content->id }}" id="js-content-checkbox-{{ $content->id }}"  class="js-select-posts-for-action form-check-input"  wire:model="checked">
                            <label for="js-content-checkbox-{{ $content->id }}" class="custom-control-label"></label>
                        </div>
                    </div>

                </td>
                @if($showColumns['id'])
                    <td style="vertical-align: middle;">
                        {{ $content->id }}
                    </td>
                @endif
                @if($showColumns['image'])
                    <td style="vertical-align: middle; min-width: 150px;">
                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])
                    </td>
                @endif
                @if($showColumns['title'])
                    <td style="vertical-align: middle;">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])

                    </td>
                @endif

                @if($showColumns['author'])
                    <td style="vertical-align: middle;">
                        {{$content->authorName()}}
                    </td>
                @endif





                @if($showColumns['id'])
                    <td style="vertical-align: middle; width: 30px;">
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])

                        @if($content->is_deleted == 1)
                            <div class="row">
                                @include('content::admin.content.livewire.components.trash-buttons-dropdown', ['content'=>$content])
                            </div>

                        @endif
                    </td>
                @endif


            </tr>
        @endforeach
        </tbody>
    </table>

</div>
