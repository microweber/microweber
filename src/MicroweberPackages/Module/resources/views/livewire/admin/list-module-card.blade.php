<div class="col-lg-3 col-md-6 col-12 p-3" wire:key="{{$module->id}}-{{md5($module->module)}}">
    <div class="card modules-card" style="min-height:170px">
        <div class="card-body text-center d-flex align-items-center justify-content-center flex-column position-relative">
            <a href="{{module_admin_url($module->module)}}">
                <div class="mx-auto mb-2" style="width: 40px; height: 40px">
                    {!! $module->getIconInline() !!}
                </div>
                <h3 class="card-title pt-2 mb-0 text-muted">
                    {{str_limit(_e($module->name, true), 30)}}
                </h3>
            </a>

            @if($module->installed == 1)
                <span wire:click="$emit('openModal', 'admin-ask-for-module-uninstall-modal', {{ json_encode(['moduleId' => $module->id]) }})" type="button" class="mdi mdi-close text-danger modules-uninstall-button"></span>
            @endif

            @if($module->installed == 0)
                <button wire:click="install('{{$module->id}}')" wire:target="install('{{$module->id}}')" wire:loading.attr="disabled" type="button" class="btn btn-sm btn-outline-success">
                    <div wire:loading wire:target="install('{{$module->id}}')" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Installing...</span>
                    </div>
                    Install
                </button>
            @endif

        </div>
    </div>
</div>
