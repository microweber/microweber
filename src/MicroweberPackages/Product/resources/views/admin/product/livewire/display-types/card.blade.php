<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center flex-lg-box">

                    <div class="col-auto text-center d-flex align-items-center" style="max-width: 40px;">
                         <span class="cursor-move-holder me-2 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                              <span href="javascript:;" class="btn btn-link text-blue-lt">
                                  <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                              </span>
                        </span>
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action form-check-input"  wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>

                    </div>

                    @if($showColumns['image'])
                    <div class="col-auto mx-4" style="min-width: 120px; max-width: 120px;">
                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])
                    </div>
                    @endif

                    @if($showColumns['title'])
                    <div class="col-sm col-12 my-md-0 my-3">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])

                    </div>
                    @endif

                    <div class="col-auto d-flex flex-wrap my-xl-0 my-3">
                        <div class="col-auto d-flex align-items-center justify-content-end flex-wrap display-types-content-icons">
                            <a class="tblr-body-color" href="{{$content->editLink()}}" data-bs-toggle="tooltip" aria-label="Live edit" data-bs-original-title="Edit">
                                <svg class="me-3" fill="currentColor" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"></path></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"></path></g></g></g></svg>
                            </a>

                            <a class="tblr-body-color" href="{{$content->link()}}?editmode=y" data-bs-toggle="tooltip" aria-label="Preview" data-bs-original-title="Customize">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px"><path d="M480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-85 32-158t87.5-127q55.5-54 130-84.5T489-880q79 0 150 26.5T763.5-780q53.5 47 85 111.5T880-527q0 108-63 170.5T650-294h-75q-18 0-31 14t-13 31q0 27 14.5 46t14.5 44q0 38-21 58.5T480-80Zm0-400Zm-233 26q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15Zm126-170q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15Zm214 0q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15Zm131 170q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15ZM480-140q11 0 15.5-4.5T500-159q0-14-14.5-26T471-238q0-46 30-81t76-35h73q76 0 123-44.5T820-527q0-132-100-212.5T489-820q-146 0-247.5 98.5T140-480q0 141 99.5 240.5T480-140Z"/></svg>
                            </a>
                        </div>

                    </div>

                    @if($showColumns['author'])
                    <div class="col">
                        <span class="text-muted" title="{{$content->authorName()}}">{{$content->authorName()}}</span>
                    </div>
                    @endif

                    <div class="col-md-1 col-auto text-end item-author manage-post-item-col-4">
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
