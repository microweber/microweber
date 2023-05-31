<div>

    <script>mw.require('admin_package_manager.js');</script>

    <button type="button" class="btn-close" wire:click="$emit('closeModal')" aria-label="Close"></button>

    @if(isset($package['has_update']) && $package['has_update'] && $installVersion == $package['version'])

        <div class="modal-status bg-warning"></div>
        <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                 stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                <path d="M9 12l2 2l4 -4"></path>
            </svg>
            <div>
                <h1>{{$package['description']}}</h1>
                <div class="text-muted">
                    Latest Version: {{$package['version']}}
                </div>

                <a vkey="{{$installVersion}}" href="javascript:;"
                   id="js-install-package-action"
                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                   class="btn btn-outline-warning js-package-install-btn">
                    <i class="mdi mdi-rocket"></i> {{_e('Update to')}} {{$installVersion}}
                </a>
            </div>
        </div>

    @else

    <div class="modal-status bg-success"></div>
    <div class="modal-body text-center py-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24"
             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
             stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
            <path d="M9 12l2 2l4 -4"></path>
        </svg>
        <h3>Template</h3>
        <div class="text-muted">
            Your template is up to date.
        </div>
    </div>

    @endif


</div>
