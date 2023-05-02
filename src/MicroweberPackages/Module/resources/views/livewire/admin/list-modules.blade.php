<div>

    <div class="d-flex justify-content-between mb-4 mt-6">
        <h1 class="mb-0">
            <svg fill="currentColor" style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m390 976-68-120H190l-90-160 68-120-68-120 90-160h132l68-120h180l68 120h132l90 160-68 120 68 120-90 160H638l-68 120H390Zm248-440h86l44-80-44-80h-86l-45 80 45 80ZM438 656h84l45-80-45-80h-84l-45 80 45 80Zm0-240h84l46-81-45-79h-86l-45 79 46 81ZM237 536h85l45-80-45-80h-85l-45 80 45 80Zm0 240h85l45-80-45-80h-86l-44 80 45 80Zm200 120h86l45-79-46-81h-84l-46 81 45 79Zm201-120h85l45-80-45-80h-85l-45 80 45 80Z"></path></svg>
            <strong>Modules</strong>
        </h1>
        <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0">
            <div class="input-icon">
              <span class="input-icon-addon">
                     <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg></span>
                <input type="text" class="form-control" placeholder="Search..." wire:keydown.enter="filter" wire:model.lazy="keyword" />
                <div wire:loading wire:target="keyword" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Searching...</span>
                </div>
            </div>
        </div>
        <div>

            <button type="button" class="btn btn-outline-primary" wire:click="reloadModules">
                <div wire:loading wire:target="reloadModules" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <i class="mdi mdi-refresh icon-left"></i>  {{ _e("Reload modules") }}
            </button>

        </div>
    </div>

    <div>
        <div class="bg-white shadow-sm rounded p-4 mb-4">
            <div class="row d-flex justify-content-between">
                <div class="col-md-6">
                    <div>
                        <label class="d-block mb-2">{{  _e("Type")}}</label>
                        <select class="form-select" wire:model="type" data-width="100%">
                            <option value="live_edit">{{  _e("Live edit modules")}}</option>
                            <option value="admin" selected>{{  _e("Admin modules")}}</option>
                            <option value="advanced">{{  _e("All modules")}}</option>
                            <option value="elements">{{  _e("Elements")}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <label class="d-block mb-2">{{  _e("Status")}}</label>
                        <select class="form-select" wire:model="installed" data-width="100%">
                            <option value="1">{{  _e("Installed")}}</option>
                            <option value="0">{{  _e("Uninstalled")}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards">

        @foreach($modules as $module)
            <div class="col-md-3">
                <div class="card card-stacked" style="min-height:170px">
                    <div class="card-body text-center d-flex align-items-center justify-content-center flex-column">
                        <a href="{{module_admin_url($module->module)}}">
                            <img src="{{$module->icon()}}" style="width:64px" />
                            <h3 class="card-title pt-2 text-muted">
                                {{str_limit(_e($module->name, true), 30)}}
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="d-flex justify-content-center mt-4">
        {!! $modules->links('livewire-tables::specific.bootstrap-4.pagination') !!}
    </div>

</div>
