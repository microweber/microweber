<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog" role="document">
            <div class="mw-modal-content">
                <div class="mw-modal-header" style="position: sticky; z-index: 100; padding-bottom:15px; padding-top:20px; top:0px; background-color: var(--tblr-bg-surface)">
                    <h5 class="mw-modal-title">
                        {{_e('Settings')}}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$emit('closeMwTopDialogIframe')" aria-label="Close"></button>
                </div>
                <div class="mw-modal-body" >

                    <div class="d-flex align-items-center">

                        <div class="w-full">
                            <x-microweber-ml::input-text label-text="Name" wire-model-name="name" wire-model-defer="1" />
                        </div>

                        <div class="w-full">
                            <x-microweber-ui::label for="type" value="Type" />
                            @php
                            $customFieldsType = mw()->ui->custom_fields();
                            @endphp
                            <x-microweber-ui::select id="type" disabled="disabled" :options="$customFieldsType" class="mt-1 block w-full" wire:model.defer="state.type" />
                        </div>
                    </div>

                    @if($state and $state['type'] == 'date')

                        @php
                            $dateFormat = [];
                            $getDateFormat = mw()->format->available_date_formats();
                            if ($getDateFormat) {
                                foreach ($getDateFormat as $format) {
                                    $dateFormat[$format['js']] = date($format['php'], time()) . ' - ' . $format['js'];
                                }
                            }
                        @endphp
                        <div class="mt-3">
                            <x-microweber-ui::label for="date_format" value="Date format" />
                            <x-microweber-ui::select :options="$dateFormat" id="date_format" class="mt-1 block w-full" wire:model.defer="state.options.date_format" />
                        </div>

                    @endif

                    @if($state and $state['type'] == 'upload')
                        @php
                        $allowedFormatsForUpload = [
                            'images' => 'Image Files',
                            'documents' => 'Document Files',
                            'archives' => 'Archive Files',
                        ];
                        @endphp
                        <div class="mt-3">
                            <x-microweber-ui::label for="allowed_formats" value="Allowed Formats for upload" />
                            <x-microweber-ui::checkbox :options="$allowedFormatsForUpload" id="allowed_formats" class="mt-1 block w-full" wire:model.defer="state.options.file_types" />
                        </div>
                        <div class="mt-3">
                            <x-microweber-ui::label for="custom_file_types" value="Custom File Types" />
                            <x-microweber-ui::input id="custom_file_types" placeholder="psd,html,css" class="mt-1 block w-full" wire:model.defer="state.options.custom_file_types" />
                        </div>
                    @endif

                    @if($showValueSettings)

                        @include('custom_field::livewire.custom-field-values-edit-forms')

                    @endif

                    @if($showPlaceholderSettings)

                        <div class="mt-3">
                            <x-microweber-ui::label for="show_placeholder" value="Show Placeholder" />
                            <small class="live-edit-label mt-0 mb-3" style="font-size: 8px;">Toggle to turn on the placeholder and write your text below</small>
                            <x-microweber-ui::toggle id="show_placeholder" class="mt-1 block w-full" wire:model="state.options.show_placeholder" />
                        </div>

                        @if($state and isset($state['options']['show_placeholder']) && $state['options']['show_placeholder'] == 1)
                        <div class="mt-1">
                            <x-microweber-ui::label for="placeholder" value="Placeholder" />
                            <x-microweber-ui::input id="placeholder" class="mt-1 block w-full" wire:model.defer="state.placeholder" />
                        </div>
                        @endif

                    @endif

                    @if($showRequiredSettings)
                    <div class="mt-3">
                        <x-microweber-ui::label for="required" value="Required" />
                        <small class="live-edit-label mt-0 mb-3" style="font-size: 8px;">Toggle to make this field required for the user</small>
                        <x-microweber-ui::toggle id="required" class="mt-1 block w-full" wire:model.defer="state.required" />
                    </div>
                    @endif

                    @if($showLabelSettings)
                     <div class="mt-3">
                        <x-microweber-ui::label for="show_label" value="Show Label" />
                         <small class="live-edit-label mt-0 mb-3" style="font-size: 8px;">Toggle to turn on the label and write your text below</small>
                        <x-microweber-ui::toggle id="show_label" class="mt-1 block w-full" wire:model.defer="state.show_label" />
                    </div>
                    @endif


                    @if($showErrorTextSettings)
                    <div class="mt-3">
                        <div class="w-full">
                            <x-microweber-ml::input-text label-text="Error Text" wire-model-name="error_text" wire-model-defer="1" />
                        </div>
                        <small class="form-control-live-edit-label-wrapper">
                           {{_e('This error will be shown when fields are required but not filled')}}
                        </small>
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <div class="w-full">
                            <x-microweber-ui::label for="gridDesktop" value="Grid Desktop" />
                            @php
                                $responsiveDesktopOptions = template_field_size_options();
                            @endphp
                            <x-microweber-ui::select id="gridDesktop" :options="$responsiveDesktopOptions" class="mt-1 block w-full" wire:model.defer="state.options.field_size_desktop" />
                        </div>
                        <div class="w-full">
                            <x-microweber-ui::label for="gridTablet" value="Grid Tablet" />
                            @php
                                $responsiveTabletOptions = template_field_size_options();
                            @endphp
                            <x-microweber-ui::select id="gridTablet" :options="$responsiveTabletOptions" class="mt-1 block w-full" wire:model.defer="state.options.field_size_tablet" />
                        </div>
                        <div class="w-full">
                            <x-microweber-ui::label for="gridMobile" value="Grid Mobile" />
                            @php
                                $responsiveMobileOptions = template_field_size_options();
                            @endphp
                            <x-microweber-ui::select id="gridMobile" :options="$responsiveMobileOptions" class="mt-1 block w-full" wire:model.defer="state.options.field_size_mobile" />
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end"  style="position: sticky; z-index: 100; padding-bottom:15px; padding-top:20px; bottom:0px; background-color: var(--tblr-bg-surface)">
                        <x-microweber-ui::button wire:click="save" id="js-save-custom-field">
                            <span wire:loading wire:target="save">
                                <span id="js-save-custom-field-loading" class="spinner-border spinner-border-sm text-white" role="status"></span>
                            </span>
                            {{_e('Save')}}
                        </x-microweber-ui::button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div wire:ignore>
        <script>
            window.addEventListener('customFieldUpdated', event => {
                mw.notification.success('Custom field saved!');
                if (mw && mw.top && typeof mw.top === 'function' && mw.top().app) {
                    alert('prateno');
                    mw.top().app.dispatch('customFieldUpdatedGlobal', {});
                }
            });
        </script>
    </div>
</div>
