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
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"></path></svg>
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
