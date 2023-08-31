<div>
<div
    x-data="{openProjects:false}"
    x-on:click.away="openProjects = false"
>

    <div class="w-full px-2">
        <button type="button" class="form-select form-control-live-edit-input" x-on:click="openProjects = !openProjects">
            {{$state['settings'][$this->optionKey]}}
        </button>
    </div>

    <div x-show="openProjects" class="form-control-live-edit-label-wrapper">

        <div>
            <x-microweber-ui::input wire:model="search" placeholder="Search projects..." />
        </div>

        <div class="dropdown-menu form-control-live-edit-input ps-0" style="max-height:300px;overflow-y: scroll" :class="[openProjects ? 'show':'']">

            <button type="button" wire:click="addNewProject()" class="dropdown-item">
                Add new project
            </button>

            @if(!empty($projects))
                @foreach($projects as $projectName)
                    <div class="d-flex gap-2 mt-2">
                    <button type="button"
                            wire:click="selectProject('{{$projectName}}')"
                            x-on:click="openProjects = false"
                            class="dropdown-item tblr-body-color">
                        {!! $projectName !!}
                    </button>
                    <button type="button" wire:click="deleteProject('{{$projectName}}')" class="mw-liveedit-button-actions-component">
                        <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                    </button>
                    </div>
                @endforeach
            @endif

        </div>

    </div>
</div>


    <x-microweber-ui::dialog-modal wire:key="addNewProjectModal" wire:model="addNewProjectModal">
        <x-slot name="title">
            Add new project
        </x-slot>

        <x-slot name="content">
            <label class="live-edit-label">Project Name</label>
            <x-microweber-ui::input type="text" wire:model="newProjectName" />
        </x-slot>

        <x-slot name="footer">
            <x-microweber-ui::secondary-button wire:click="closeProjectModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-microweber-ui::secondary-button>

            <x-microweber-ui::primary-button class="ms-2" wire:click="saveNewProject" wire:loading.attr="disabled">
                <div wire:loading wire:target="confirmPassword" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                {{ __('Save') }}
            </x-microweber-ui::primary-button>
        </x-slot>
    </x-microweber-ui::dialog-modal>



</div>
