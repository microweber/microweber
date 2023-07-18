<div x-data="{}">
    <div>
        <label class="live-edit-label">Text Input</label>
        <x-microweber-ui::input />
    </div>

    <div>
        <label class="live-edit-label">Textarea</label>
        <x-microweber-ui::textarea />
    </div>

    <div>
        <label class="live-edit-label">Link Picker</label>
        <x-microweber-ui::link-picker />
    </div>

<!--    <div class="mt-4 mb-3">
        <label class="live-edit-label">Icon Picker</label>
        <x-microweber-ui::icon-picker />
    </div>-->

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Alignment</label>
        <x-microweber-ui::alignment/>
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Range Slider</label>
        <x-microweber-ui::range-slider />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Select</label>
        @php
            $selectOptions = [
                '1' => 'Option 1',
                '2' => 'Option 2',
                '3' => 'Option 3',
            ];
        @endphp
        <x-microweber-ui::select :options="$selectOptions" />
    </div>


    <div class="mt-4 mb-3">
        <label class="live-edit-label">Radio</label>
        @php
            $radioOptions = [
                '1' => 'Radio 1',
                '2' => 'Radio 2',
                '3' => 'Radio 3',
            ];
        @endphp
        <x-microweber-ui::radio :options="$radioOptions" />
    </div>



    <div class="mt-4 mb-3">
        <label class="live-edit-label">Checkbox</label>
        @php
            $checkboxOptions = [
                '1' => 'Checkbox 1',
                '2' => 'Checkbox 2',
                '3' => 'Checkbox 3',
            ];
        @endphp
        <x-microweber-ui::checkbox :options="$checkboxOptions" />
    </div>

    <div class="mt-4">
        <x-microweber-ui::action-message on="showActionMessage">
            <?php _e('This is an action message!');?>
        </x-microweber-ui::action-message>
        <x-microweber-ui::button wire:click="showActionMessage()">
            Show Action Message
        </x-microweber-ui::button>
    </div>

    <div class="mt-4">
        <x-microweber-ui::modal wire:model="showModal">

            <div style="background:#fff;color:#000;padding:50px 150px">

                Here is a cleared modal

                <br />
                <br />
                <br />

                <x-microweber-ui::button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                    Close Modal
                </x-microweber-ui::button>
            </div>

        </x-microweber-ui::modal>
        <x-microweber-ui::button wire:click="$toggle('showModal')" >
            Show Modal
        </x-microweber-ui::button>
    </div>

    <div class="mt-4">
        <x-microweber-ui::dialog-modal wire:model="showDialogModal">
            <x-slot name="title">
                This is the dialog modal
            </x-slot>

            <x-slot name="content">
                Here is the content of dialog modal
            </x-slot>

            <x-slot name="footer">
                <x-microweber-ui::button wire:click="$toggle('showDialogModal')" wire:loading.attr="disabled">
                    Close Dialog Modal
                </x-microweber-ui::button>
            </x-slot>
        </x-microweber-ui::dialog-modal>
        <x-microweber-ui::button wire:click="$toggle('showDialogModal')" >
            Show Dialog Modal
        </x-microweber-ui::button>
    </div>

    <div class="mt-4">
        <x-microweber-ui::button>
            Main Button
        </x-microweber-ui::button>
    </div>

    <div class="mt-4">
        <x-microweber-ui::primary-button>
            Primary Button
        </x-microweber-ui::primary-button>
    </div>

    <div class="mt-4">
        <x-microweber-ui::secondary-button>
            Secondary Button
        </x-microweber-ui::secondary-button>
    </div>

    <div class="mt-4">
        <x-microweber-ui::danger-button>
            Danger Button
        </x-microweber-ui::danger-button>
    </div>
</div>
