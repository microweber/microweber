<div>

    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div class="col-md-2 col-12">
            <div class="form-group">
                <label class="form-label d-block mb-2">
                    {{_e('Your form lists')}}
                </label>
                <select wire:model="filter.formListId" class="form-select form-control">
                    <option>{{_e('All lists')}}</option>
                    @foreach($formsLists as $formsList)
                        <option value="{{$formsList->id}}">{{$formsList->title}} ({{$formsList->formsData->count()}})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4 col-12 ms-lg-auto mt-2">
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
            <button type="button" onclick="Livewire.emit('openModal', 'contact-form.settings-modal')" class="btn btn-outline-primary mb-3 btn-sm">
                <i class="mdi mdi-cogs"></i> {{_e('Settings')}}
            </button>
            &nbsp; &nbsp;
            <button  type="button" onclick="Livewire.emit('openModal', 'contact-form.integrations-modal')" class="btn btn-outline-primary mb-3 btn-sm">
                <i class="mdi mdi-cogs"></i> {{_e('Integrations')}}
            </button>
        </div>
    </div>

    <div class="mt-4">
        <small>{{_e('Entries for list')}}: <span>{{_e('All lists')}}</span></small>
    </div>

    <div>
        @foreach($formsData as $formData)
            <div class="card shadow-sm mb-4 bg-silver">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-start gap-5">
                        <div class="col-auto d-flex align-items-center gap-1" data-bs-toggle="tooltip" aria-label="#{{$formData->id}}" data-bs-original-title="#{{$formData->id}}">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm340-302L140-685v465h680v-465L480-462Zm0-60 336-218H145l335 218ZM140-685v-55 520-465Z"/></svg>

                            <div class="dropdown">
                                <a href="#" class=" dropdown-toggle form-label mb-0 text-decoration-none" data-bs-toggle="dropdown">

                                </a>
                                <div class="dropdown-menu">
                                    <a  href="" class="dropdown-item">
                                        <svg class="me-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m480-920 371 222q17 9 23 24.5t6 30.5v463q0 24-18 42t-42 18H140q-24 0-42-18t-18-42v-463q0-15 6.5-30.5T109-698l371-222Zm0 466 336-197-336-202-336 202 336 197Zm0 67L140-587v407h680v-407L480-387Zm0 207h340-680 340Z"/></svg>
                                        {{ _e("Mark as Read") }}
                                    </a>

                                    <a  href="" class="dropdown-item">
                                        <svg class="me-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm340-302L140-685v465h680v-465L480-462Zm0-60 336-218H145l335 218ZM140-685v-55 520-465Z"/></svg>

                                        {{ _e("Mark as Unread") }}
                                    </a>

                                </div>
                            </div>

                        </div>

                        <div class="col-xl-5">
                            <h4 class="mb-0">
                                 {{$formData->getSubject()}}
                            </h4>
                        </div>

                        <div class="col">
                            <p class="mb-0">
                                {{$formData->getFullName()}}
                            </p>
                            <span class="text-muted">{{$formData->getEmail()}}</span>
                        </div>
                        <div class="col-auto d-flex align-items-center justify-content-end gap-4">
                            <div>
                               <small data-bs-toggle="tooltip" aria-label="{{$formData->created_at->diffForHumans()}}" data-bs-original-title="{{$formData->created_at->diffForHumans()}}">
                                    {{$formData->created_at->format('M d, Y')}}
                                  <br/>
                                   <small> {{$formData->created_at->format('h:i A')}}</small>
                               </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-link"
                               onclick="Livewire.emit('openModal', 'admin-marketplace-item-modal', {{ json_encode(['formDataId'=>$formData->id]) }})">
                                {{_e('View')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($formsData->count() == 0)
            <div class="mt-2">
                <div class="alert alert-info">
                    {{_e('No data found')}}
                </div>
            </div>
        @endif

    </div>

    <div class="row mt-4">
        <div class="col-sm-10 text-center">
            <div class="pagination justify-content-center">
                <div class="pagination-holder">
                    {{$formsData->links()}}
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
    <div class="row mt-1">
        <div class="col-sm-6">
            <div class="export-label" wire:click="exportDataExcel">
                <span>{{_e('Export data')}}</span>
                <span class="btn btn-outline-primary btn-sm">{{_e('Excel')}}</span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="text-end text-right">
                <span>{{$formsData->count()}} {{_e('items on page. Total')}}: {{$formsData->total()}} </span>
            </div>
        </div>
    </div>

</div>
