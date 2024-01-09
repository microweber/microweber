<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder post-has-image-true manage-post-item mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center flex-lg-box py-3">

                    <div class="col-auto mx-2 my-md-0 my-2 text-center d-flex align-items-center">
                         <span class="cursor-move-holder me-2 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                              <span href="javascript:;" class="btn btn-link text-blue-lt tblr-body-color">
                                  <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                              </span>
                        </span>
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action form-check-input"  wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>

                    </div>

                    <div class="col mx-2 my-md-0 my-2 ms-3">
                        <span class="text-muted">
                            {{'Client'}}: <br />
                        {{ $content->getFullName() }}
                        </span>
                    </div>

                    <div class="col mx-2 my-md-0 my-2">
                        <span class="text-muted">
                            {{'E-mail'}}: <br />
                        {{ $content->getEmail() }}
                        </span>
                    </div>

                    <div class="col mx-2 my-md-0 my-2">
                        <span class="text-muted">
                            {{'Phone'}}: <br />
                        {{ $content->getPhone() }}
                        </span>
                    </div>

                    <div class="col mx-2 my-md-0 my-2">
                        <span class="text-muted">
                            {{'City/Country'}}: <br />
                        {{ $content->cityAndCountry() }}
                        </span>
                    </div>

                    <div class="col-auto">
                        <form action="{{ route('admin.customers.destroy', $content->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('admin.customers.edit', $content->id) }}" class="tblr-body-color me-2 text-decoration-none" data-bs-toggle="tooltip" aria-label="Edit client" data-bs-original-title="Edit client">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M180-180h44l443-443-44-44-443 443v44Zm614-486L666-794l42-42q17-17 42-17t42 17l44 44q17 17 17 42t-17 42l-42 42Zm-42 42L248-120H120v-128l504-504 128 128Zm-107-21-22-22 44 44-22-22Z"></path></svg>
                            </a>
                            <button type="submit" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))" class="text-danger border-0" style="background: none;" data-bs-toggle="tooltip" aria-label="Delete client" data-bs-original-title="Delete client">
                                <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                            </button>
                        </form>
                    </div>



                </div>
            </div>
        </div>

    @endforeach
</div>
