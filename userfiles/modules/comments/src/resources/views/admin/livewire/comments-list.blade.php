<div>

    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div class="col-md-4 col-12 mt-2">
            <div class="input-icon">
                  <span class="input-icon-addon">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960"
                           width="24"><path
                              d="M796 935 533 672q-30 26-69.959 40.5T378 727q-108.162 0-183.081-75Q120 577 120 471t75-181q75-75 181.5-75t181 75Q632 365 632 471.15 632 514 618 554q-14 40-42 75l264 262-44 44ZM377 667q81.25 0 138.125-57.5T572 471q0-81-56.875-138.5T377 275q-82.083 0-139.542 57.5Q180 390 180 471t57.458 138.5Q294.917 667 377 667Z"/></svg>
                  </span>
                <input type="text" class="form-control form-control-sm" placeholder="Search..."
                       wire:model="filter.keyword" />
                <div wire:loading wire:target="filter.keyword" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">{{  _e("Searching")}}...</span>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 text-xl-end mt-4">
            <button onclick="Livewire.emit('openModal', 'contact-form.settings-modal')" class="btn btn-link tblr-body-color font-weight-bold text-decoration-none me-3 fs-3 mw-admin-action-links" href="{{admin_url('settings?group=general')}}">
                <svg fill="currentColor" class="me-1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m388-80-20-126q-19-7-40-19t-37-25l-118 54-93-164 108-79q-2-9-2.5-20.5T185-480q0-9 .5-20.5T188-521L80-600l93-164 118 54q16-13 37-25t40-18l20-127h184l20 126q19 7 40.5 18.5T669-710l118-54 93 164-108 77q2 10 2.5 21.5t.5 21.5q0 10-.5 21t-2.5 21l108 78-93 164-118-54q-16 13-36.5 25.5T592-206L572-80H388Zm92-270q54 0 92-38t38-92q0-54-38-92t-92-38q-54 0-92 38t-38 92q0 54 38 92t92 38Zm0-60q-29 0-49.5-20.5T410-480q0-29 20.5-49.5T480-550q29 0 49.5 20.5T550-480q0 29-20.5 49.5T480-410Zm0-70Zm-44 340h88l14-112q33-8 62.5-25t53.5-41l106 46 40-72-94-69q4-17 6.5-33.5T715-480q0-17-2-33.5t-7-33.5l94-69-40-72-106 46q-23-26-52-43.5T538-708l-14-112h-88l-14 112q-34 7-63.5 24T306-642l-106-46-40 72 94 69q-4 17-6.5 33.5T245-480q0 17 2.5 33.5T254-413l-94 69 40 72 106-46q24 24 53.5 41t62.5 25l14 112Z"/></svg>
                {{_e('Settings')}}
            </button>
        </div>
    </div>

    <div class="mb-3 mt-4">
        <div class="btn-group w-100" role="group">
            <input type="radio" wire:model="filter.status" value="all" class="btn-check" name="btn-radio-basic" id="btn-radio-basic-1" autocomplete="off">
            <label for="btn-radio-basic-1" type="button" class="btn">{{_e('All')}} ({{$countAll}})</label>

            <input type="radio" wire:model="filter.status" value="mine" class="btn-check" name="btn-radio-basic" id="btn-radio-basic-2" autocomplete="off">
            <label for="btn-radio-basic-2" type="button" class="btn">{{_e('Mine')}} ({{$countMine}})</label>

            <input type="radio" wire:model="filter.status" value="pending" class="btn-check" name="btn-radio-basic" id="btn-radio-basic-3" autocomplete="off">
            <label for="btn-radio-basic-3" type="button" class="btn">{{_e('Pending')}} ({{$countPending}})</label>

            <input type="radio" wire:model="filter.status" value="approved" class="btn-check" name="btn-radio-basic" id="btn-radio-basic-4" autocomplete="off">
            <label for="btn-radio-basic-4" type="button" class="btn">{{_e('Approved')}} ({{$countApproved}})</label>

            <input type="radio" wire:model="filter.status" value="spam" class="btn-check" name="btn-radio-basic" id="btn-radio-basic-5" autocomplete="off">
            <label for="btn-radio-basic-5" type="button" class="btn">{{_e('Spam')}} ({{$countSpam}})</label>

            <input type="radio" wire:model="filter.status" value="trash" class="btn-check" name="btn-radio-basic" id="btn-radio-basic-6" autocomplete="off">
            <label for="btn-radio-basic-6" type="button" class="btn">{{_e('Trash')}} ({{$countTrashed}})</label>
        </div>
    </div>

    <div>
        @foreach($comments as $comment)
            <div class="card shadow-sm mb-4 bg-silver comments-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-start gap-5">
                        <div class="col-auto d-flex align-items-center gap-1" data-bs-toggle="tooltip" aria-label="#{{$comment->comment_name}}" data-bs-original-title="#{{$comment->comment_name}}">

                            <div>
                                @if($comment->created_by > 0)
                                    <img class="rounded-circle shadow-1-strong me-3"
                                         src="{{user_picture($comment->created_by)}}" alt="avatar" width="65"
                                         height="65" />
                                @else
                                    <div class="shadow-1-strong me-3">
                                        <i class="fa fa-user-circle-o" style="font-size:42px"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="dropdown content-card-blade-dots-menu-wrapper">
                                <a href="#" class=" dropdown-toggle form-label mb-0 text-decoration-none content-card-blade-dots-menu dots-menu-2" data-bs-toggle="dropdown"></a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" wire:click="markAsRead('{{$comment->id}}')">
                                        <svg class="me-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m480-920 371 222q17 9 23 24.5t6 30.5v463q0 24-18 42t-42 18H140q-24 0-42-18t-18-42v-463q0-15 6.5-30.5T109-698l371-222Zm0 466 336-197-336-202-336 202 336 197Zm0 67L140-587v407h680v-407L480-387Zm0 207h340-680 340Z"/></svg>
                                        {{ _e("Mark as Read") }}
                                    </button>
                                    <button class="dropdown-item" wire:click="markAsUnread('{{$comment->id}}')">
                                        <svg class="me-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm340-302L140-685v465h680v-465L480-462Zm0-60 336-218H145l335 218ZM140-685v-55 520-465Z"/></svg>
                                        {{ _e("Mark as Unread") }}
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="col-xl-5">
                            <div class="cursor-pointer" wire:click="preview({{$comment->id}})">
                                @if($comment->is_read==1)
                                    <span class="mb-0 text-muted">
                                      {{$comment->comment_body}}
                                    </span>
                                @else
                                    <h4 class="mb-0">
                                        {{$comment->comment_body}}
                                    </h4>
                                @endif
                            </div>
                        </div>

                        <div class="col">
                            <p class="mb-0">
                                {{$comment->comment_name}}
                            </p>
                            <span class="text-muted">
                             {{$comment->comment_email}}
                            </span>
                        </div>
                        <div class="col-auto d-flex align-items-center justify-content-end gap-4">
                            <div>
                                <small data-bs-toggle="tooltip" aria-label="{{$comment->created_at->diffForHumans()}}" data-bs-original-title="{{$comment->created_at->diffForHumans()}}">
                                    {{$comment->created_at->format('M d, Y')}}
                                    <br/>
                                    <small> {{$comment->created_at->format('h:i A')}}</small>
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-link tblr-body-color" data-bs-toggle="tooltip" aria-label="{{'View'}}" data-bs-original-title="{{'View'}}"
                                    wire:click="preview({{$comment->id}})">
                                <svg class="me-3" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($comments->count() == 0)
            <div class="mt-2">
                <div class="alert alert-info">
                    {{_e('No data found')}}
                </div>
            </div>
        @endif

    </div>

    <div class="row p-0">

        <div class="col-3">
            <div class="mb-2">
                <span>{{$comments->count()}} {{_e('items on page. Total')}}: {{$comments->total()}} </span>
            </div>
        </div>

        <div class="col-sm-7 text-center">
            <div class="pagination justify-content-center">
                <div class="pagination-holder">
                    {{$comments->links()}}
                </div>
            </div>
        </div>

        <div class="col-sm-2 text-center text-sm-right">
            <div class="form-group">
                <form method="get">
                    <small>{{_e('Show items per page')}}</small>
                    <select class="form-select" wire:model="itemsPerPage">
                        <option value="">Select</option>
                        <option value="10">10</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

</div>
