<div class="">
    <script>mw.require('admin_package_manager.js');</script>

    @if(isset($package['has_update']) && $package['has_update'] && $installVersion == $package['version'])
        <div class="modal-header">
            <button type="button" class="btn-close" wire:click="$emit('closeModal')" aria-label="Close"></button>
        </div>

        <div class="modal-body py-4">

            <h1>{{$package['description']}}</h1>
            <div class="text-muted">
                {{_e('You are not using the latest version - version')}} : {{$package['version']}}
            </div>

            <div class="mt-4 text-end">
                <a class="btn btn-dark me-2" href="" wire:click="$emit('closeModal')" > {{_e('Cancel')}}</a>

                <a vkey="{{$installVersion}}" href="javascript:;"
                   id="js-install-package-action"
                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                   class="btn btn-outline-dark js-package-install-btn">
                    {{_e('Update now')}} {{$installVersion}}
                </a>
            </div>
        </div>

    @else

    <div class="modal-body py-4">
        <h2>
            {{ 'Your template is up to date' }}
        </h2>

        <label class="form-label">
            {{ 'Great! Your template is up to date' }}!
        </label>

        <div class="mt-4 text-end">
            <a class="btn btn-dark me-2" href="" wire:click="$emit('closeModal')" > {{_e('Close')}}</a>
        </div>
    </div>

    @endif


</div>
