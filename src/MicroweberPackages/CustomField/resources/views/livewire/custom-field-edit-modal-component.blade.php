<div xmlns:x-microweber-ui="http://www.w3.org/1999/html">
    <div class="mw-modal">
        <div class="mw-modal-dialog" role="document">
            <div class="mw-modal-content">
                <div class="mw-modal-header">
                    <h5 class="mw-modal-title">
                        {{_e('Settings')}}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$emit('closeModal')"
                            aria-label="Close"></button>
                </div>
                <div class="mw-modal-body">

                    <div class="d-flex mt-3">
                        <div class="w-full">
                            <x-microweber-ui::label for="name" value="Name" />
                            <x-microweber-ui::input id="name" class="mt-1 block w-full" wire:model.defer="state.name" />
                        </div>

                        <div class="w-full">
                            <x-microweber-ui::label for="type" value="Type" />
                            @php
                            $customFieldsType = mw()->ui->custom_fields();
                            @endphp
                            <x-microweber-ui::select id="type" disabled="disabled" :options="$customFieldsType" class="mt-1 block w-full" wire:model.defer="state.type" />
                        </div>
                    </div>

                    @if($state['type'] == 'date')

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

                    @if($state['type'] == 'upload')
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
                            <x-microweber-ui::toggle id="show_placeholder" class="mt-1 block w-full" wire:model="state.options.show_placeholder" />
                        </div>

                        @if(isset($state['options']['show_placeholder']) && $state['options']['show_placeholder'] == 1)
                        <div class="mt-1">
                            <x-microweber-ui::label for="placeholder" value="Placeholder" />
                            <x-microweber-ui::input id="placeholder" class="mt-1 block w-full" wire:model.defer="state.placeholder" />
                        </div>
                        @endif

                    @endif

                    @if($showRequiredSettings)
                    <div class="mt-3">
                        <x-microweber-ui::label for="required" value="Required" />
                        <x-microweber-ui::toggle id="required" class="mt-1 block w-full" wire:model.defer="state.required" />
                    </div>
                    @endif

                    @if($showLabelSettings)
                     <div class="mt-3">
                        <x-microweber-ui::label for="show_label" value="Show Label" />
                        <x-microweber-ui::toggle id="show_label" class="mt-1 block w-full" wire:model.defer="state.show_label" />
                    </div>
                    @endif


                    @if($showErrorTextSettings)
                    <div class="mt-3">
                        <x-microweber-ui::label for="error_text" value="Error Text" />
                        <x-microweber-ui::input id="error_text" class="mt-1 block w-full" wire:model.defer="state.error_text" />
                        <small>
                           {{_e('This error will be shown when fields are required but not filled')}}
                        </small>
                    </div>
                    @endif


                    <div class="mt-3 d-flex justify-content-end">
                        <x-microweber-ui::button wire:click="save">
                            {{_e('Save')}}
                        </x-microweber-ui::button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
