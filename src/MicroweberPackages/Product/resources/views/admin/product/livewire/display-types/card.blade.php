<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item mb-3">
            <div class="card-body py-4 row align-items-center flex-lg-box">
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


                @if($showColumns['price'])
                    <div class="col-auto  mw-product-price-column">

                        @php
                            if ($content->hasSpecialPrice()) {
                                $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($content->price).'</span>';
                                $price .= '<br /><span class="h5">'.currency_format($content->specialPrice).'</span>';
                            } else {
                                $price = '<span class="h5">'.currency_format($content->price).'</span>';
                            }
                        @endphp

                        {!! $price !!}

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

                <div class="col-auto ms-3">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle badge @if($content->is_active == 1) bg-green-lt @else bg-red-lt @endif form-label mb-0 fs-5 text-decoration-none" data-bs-toggle="dropdown">
                            @if($content->is_active == 1)
                                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M378-246 154-470l43-43 181 181 384-384 43 43-427 427Z"/></svg>
                                {{ _e("Published") }}
                            @else
                                <svg class="mx-1 text-danger" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M833-41 718-156q-50 36-110 56T480-80q-85 0-158-30.5T195-195q-54-54-84.5-127T80-480q0-68 20-128t56-110L26-848l43-43L876-84l-43 43Zm-353-99q55 0 104-15.5t91-43.5L498-376l-77 78-165-166 45-45 120 120 32-32-254-254q-28 42-43.5 91T140-480q0 145 97.5 242.5T480-140Zm324-102-43-43q28-42 43.5-91T820-480q0-145-97.5-242.5T480-820q-55 0-104 15.5T285-761l-43-43q50-36 110-56t128-20q84 0 157 31t127 85q54 54 85 127t31 157q0 68-20 128t-56 110ZM585-462l-46-45 119-119 46 45-119 119Zm-62-61Zm-86 86Z"/></svg>

                                {{ _e("Unpublished") }}
                            @endif
                        </a>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item" wire:click="publish({{$content->id}})">
                                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M378-246 154-470l43-43 181 181 384-384 43 43-427 427Z"/></svg>
                                {{ _e("Publish") }}
                            </button>
                            <button type="button" class="dropdown-item" wire:click="unpublish({{$content->id}})">
                                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M833-41 718-156q-50 36-110 56T480-80q-85 0-158-30.5T195-195q-54-54-84.5-127T80-480q0-68 20-128t56-110L26-848l43-43L876-84l-43 43Zm-353-99q55 0 104-15.5t91-43.5L498-376l-77 78-165-166 45-45 120 120 32-32-254-254q-28 42-43.5 91T140-480q0 145 97.5 242.5T480-140Zm324-102-43-43q28-42 43.5-91T820-480q0-145-97.5-242.5T480-820q-55 0-104 15.5T285-761l-43-43q50-36 110-56t128-20q84 0 157 31t127 85q54 54 85 127t31 157q0 68-20 128t-56 110ZM585-462l-46-45 119-119 46 45-119 119Zm-62-61Zm-86 86Z"/></svg>
                                {{ _e("Unpublish") }}
                            </button>
                        </div>
                    </div>
                </div>

                @if($content->is_deleted == 1)
                    <div class="col-auto ms-3">
                        @include('content::admin.content.livewire.components.trash-buttons-dropdown', ['content'=>$content])
                    </div>

                @endif

                <div class="col-md-1 col-auto text-end item-author manage-post-item-col-4">
                    @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])
                </div>

            </div>
        </div>

    @endforeach
</div>
