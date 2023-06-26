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


                    <div class="col">
                        <span class="text-muted">

                        {{ $content->first_name }} {{ $content->last_name }}

                        </span>
                    </div>



                </div>
            </div>
        </div>

    @endforeach
</div>
