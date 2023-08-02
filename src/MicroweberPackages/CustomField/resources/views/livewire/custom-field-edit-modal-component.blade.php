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

                    {{$state['type']}}

                    <div class="d-flex mt-3">
                        <div class="w-full">
                            <x-microweber-ui::label for="name" value="Name" />
                            <x-microweber-ui::input id="name" class="mt-1 block w-full" wire:model="state.name" />
                        </div>

                        <div class="w-full">
                            <x-microweber-ui::label for="type" value="Type" />
                            @php
                            $customFieldsType = mw()->ui->custom_fields();
                            @endphp
                            <x-microweber-ui::select id="type" :options="$customFieldsType" class="mt-1 block w-full" wire:model="state.type" />
                        </div>
                    </div>


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
                            <x-microweber-ui::checkbox :options="$allowedFormatsForUpload" id="allowed_formats" class="mt-1 block w-full" wire:model="state.options.file_types" />
                        </div>
                        <div class="mt-3">
                            <x-microweber-ui::label for="custom_file_types" value="Custom File Types" />
                            <x-microweber-ui::input id="custom_file_types" placeholder="psd,html,css" class="mt-1 block w-full" wire:model="state.options.custom_file_types" />
                        </div>
                    @endif

                    @if($showValueSettings)
                    <div class="mt-3">
                        <livewire:custom-field-values-edit customFieldId="{{$state['id']}}" />
                    </div>
                    @endif

                    @if($showPlaceholderSettings)
                        <div class="mt-3">
                            <x-microweber-ui::label for="show_placeholder" value="Show Placeholder" />
                            <x-microweber-ui::toggle id="show_placeholder" class="mt-1 block w-full" wire:model="state.options.show_placeholder" />
                        </div>

                        @if(isset($state['options']['show_placeholder']) && $state['options']['show_placeholder'] == 1)
                        <div class="mt-1">
                            <x-microweber-ui::label for="placeholder" value="Placeholder" />
                            <x-microweber-ui::input id="placeholder" class="mt-1 block w-full" wire:model="state.placeholder" />
                        </div>
                        @endif
                    @endif

                    @if($showRequiredSettings)
                    <div class="mt-3">
                        <x-microweber-ui::label for="required" value="Required" />
                        <x-microweber-ui::toggle id="required" class="mt-1 block w-full" wire:model="state.required" />
                    </div>
                    @endif

                    @if($showLabelSettings)
                     <div class="mt-3">
                        <x-microweber-ui::label for="show_label" value="Show Label" />
                        <x-microweber-ui::toggle id="show_label" class="mt-1 block w-full" wire:model="state.show_label" />
                    </div>
                    @endif


                    @if($showErrorTextSettings)
                    <div class="mt-3">
                        <x-microweber-ui::label for="error_text" value="Error Text" />
                        <x-microweber-ui::input id="error_text" class="mt-1 block w-full" wire:model="state.error_text" />
                        <small>
                           {{_e('This error will be shown when fields are required but not filled')}}
                        </small>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
