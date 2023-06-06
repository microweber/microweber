<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item mb-3">
            <div class="card-body px-4">
                <div class="d-flex flex-wrap align-items-center flex-lg-box">

                    <div class="col text-center manage-post-item-col-1 d-flex align-items-center" style="max-width: 40px;">
                        <div class="cursor-move-holder me-2 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                              <span href="javascript:;" class="btn btn-link text-blue-lt">
                                  <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                              </span>
                        </div>

                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action form-check-input" wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>
                    </div>

                    <div class="col manage-post-item-col-2 mx-md-4" style="max-width: 80px; min-width: 80px;">
                    @include('content::admin.content.livewire.components.picture', ['content'=>$content])
                    </div>

                    <div class="col-md col-12 my-md-0 my-3 item-title manage-post-item-col-3 manage-post-main">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])

                    </div>

                    <div class="col-auto d-flex align-items-center justify-content-end flex-wrap display-types-content-icons">
                        <a href="{{$content->editLink()}}" data-bs-toggle="tooltip" aria-label="Live edit" data-bs-original-title="Preview">
                            <svg class="me-3"  xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg>
                        </a>

                        <a href="{{$content->link()}}?editmode=y" data-bs-toggle="tooltip" aria-label="Preview" data-bs-original-title="Customize">
                            <svg  xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px"><path d="M480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-85 32-158t87.5-127q55.5-54 130-84.5T489-880q79 0 150 26.5T763.5-780q53.5 47 85 111.5T880-527q0 108-63 170.5T650-294h-75q-18 0-31 14t-13 31q0 27 14.5 46t14.5 44q0 38-21 58.5T480-80Zm0-400Zm-233 26q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15Zm126-170q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15Zm214 0q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15Zm131 170q20 0 35-15t15-35q0-20-15-35t-35-15q-20 0-35 15t-15 35q0 20 15 35t35 15ZM480-140q11 0 15.5-4.5T500-159q0-14-14.5-26T471-238q0-46 30-81t76-35h73q76 0 123-44.5T820-527q0-132-100-212.5T489-820q-146 0-247.5 98.5T140-480q0 141 99.5 240.5T480-140Z"/></svg>
                        </a>
                    </div>

                    <div class="col-auto ms-3">
                        @if ($content->is_active)
                            <div class="dropdown">
                                <a href="#" class=" dropdown-toggle badge bg-green-lt form-label mb-0 fs-5 text-decoration-none" data-bs-toggle="dropdown">
                                    <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M378-246 154-470l43-43 181 181 384-384 43 43-427 427Z"/></svg>

                                    {{ _e("Published") }}

                                </a>
                                <div class="dropdown-menu">
                                    <button type="button" class="dropdown-item" wire:click="publish({{$content->id}})">
                                        <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M378-246 154-470l43-43 181 181 384-384 43 43-427 427Z"/></svg>
                                        {{ _e("Publish") }}
                                    </button>
                                    <button type="button" class="dropdown-item" wire:click="unpublish({{$content->id}})">
                                        <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M220-80q-24 0-42-18t-18-42v-680q0-24 18-42t42-18h361l219 219v521q0 24-18 42t-42 18H220Zm331-554v-186H220v680h520v-494H551ZM220-820v186-186 680-680Z"/></svg>
                                        {{ _e("Draft") }}
                                    </button>
                                </div>
                            </div>

                        @endif
                    </div>


                    <div class="col-1 text-end item-author manage-post-item-col-4">
{{--                        <span class="text-muted" title="{{$content->authorName()}}">{{$content->authorName()}}</span>--}}

                        <div class="dropdown content-card-blade-dots-menu-wrapper">
                            <a href="#" class=" dropdown-toggle content-card-blade-dots-menu" data-bs-toggle="dropdown"></a>
                            <div class="dropdown-menu">
{{--                                <a  href="javascript:mw.admin.content.quickEditModalFrame('{{$content->editLink()}}')" class=" dropdown-item ps-4"><?php _e("Edit modal") ?></a>--}}
                                <a  href="{{$content->editLink()}}" class="dropdown-item ps-4">
                                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"/></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"/></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"/></g></g></g></svg>
                                        <?php _e("Page Details") ?>

                                </a>
                                <a href="{{$content->editLink()}}" class="dropdown-item ps-4">
                                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg>
                                    <?php _e("Live Edit") ?>
                                </a>

                                <?php if(!$content->is_deleted): ?>
                                    <a href="javascript:mw.admin.content.delete('{{ $content->id }}');" class="dropdown-item ps-4 text-danger js-delete-content-btn-{{ $content->id }}">
                                        <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg>
                                            <?php _e("Delete") ?>

                                    </a>
                                <?php endif; ?>
                                @if ($content->is_active < 1)
                                    <a href="javascript:mw.admin.content.publishContent('{{ $content->id }}');" class="mw-set-content-unpublish dropdown-item badge badge-warning font-weight-normal"><?php _e("Unpublished") ?></a>

                                @endif
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
