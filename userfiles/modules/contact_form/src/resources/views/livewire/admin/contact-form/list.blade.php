<div>

    <div class="d-flex gap-4">
        <div>
            <div class="form-group">
                <label class="form-label d-block mb-2">
                    {{_e('Your form lists')}}
                </label>
                <select wire:model="filter.formListId" class="form-select">
                    <option>{{_e('All lists')}}</option>
                    @foreach($formsLists as $formsList)
                        <option value="{{$formsList->id}}">{{$formsList->title}} ({{$formsList->formsData->count()}})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-5">
            <div class="input-icon">
                  <span class="input-icon-addon">
                      <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960"
                           width="24"><path
                              d="M796 935 533 672q-30 26-69.959 40.5T378 727q-108.162 0-183.081-75Q120 577 120 471t75-181q75-75 181.5-75t181 75Q632 365 632 471.15 632 514 618 554q-14 40-42 75l264 262-44 44ZM377 667q81.25 0 138.125-57.5T572 471q0-81-56.875-138.5T377 275q-82.083 0-139.542 57.5Q180 390 180 471t57.458 138.5Q294.917 667 377 667Z"/></svg>
                  </span>
                <input type="text" class="form-control" placeholder="Search..."
                       wire:model="filter.keyword" />
                <div wire:loading wire:target="filter.keyword" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">{{  _e("Searching")}}...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <small>Entries for list: <span>All lists</span></small>
    </div>

    <div>
        @foreach($formsData as $formData)
            <div class="card shadow-sm mb-4 bg-silver">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-start gap-5">
                        <div class="d-flex align-items-center gap-1">
                            <div><i class="mdi mdi-email text-primary mdi-24px"></i></div>
                            <div><span class="text-primary">#{{$formData->id}}</span></div>
                        </div>
                        <div>
                            <small>
                                {{$formData->getFullName()}}
                            </small> <br/>
                            <span class="text-muted">{{$formData->getEmail()}}</span>
                        </div>
                        <div class="col">
                    <span class="text-muted">
                         {{$formData->getSubject()}}
                    </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <div>
                       <span class="text-muted">
                            {{$formData->created_at->format('M d, Y')}}
                          <br/>
                           <small> {{$formData->created_at->format('h:i A')}}</small>
                       </span>
                                <div class="text-muted">
                                    {{$formData->created_at->diffForHumans()}}
                                </div>
                            </div>
                        </div>
                        <div>
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
                    <small>Show items per page</small>
                    <select class="form-select" data-size="5" data-width="100px" data-style="btn-sm"
                            onchange="this.form.submit()">
                        <option value="">Select</option>
                        <option value="10">10</option>
                        <option value="30" selected="">30</option>
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
            <div class="export-label">
                <span>Export data</span>
                <span class="btn btn-outline-primary btn-sm">Excel</span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="text-end text-right">
                <span>{{$formsData->count()}} items on page. Total: {{$formsData->total()}} </span>
            </div>
        </div>
    </div>

</div>
