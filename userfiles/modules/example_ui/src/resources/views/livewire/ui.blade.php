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
        @php
            $radioModernOptions = [
                '1' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgMlYyMEg0TDQgMkgyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMTYgMTJIOS4zMjgzOEwxMi4zMjg0IDE1SDkuNDk5OTVMNS41IDExTDkuNTAwMDUgN0wxMi4zMjg1IDdMOS4zMjg0NyAxMEwxNiAxMFYxMloiIGZpbGw9IiMwRTBFMEUiLz4KPC9zdmc+Cg==" style="width: 22px; height: 22px;">',
                '2' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEwIDIwTDEwIDJIMTJMMTIgMjBIMTBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik0xNy4zMjg0IDEySDIyVjEwTDE3LjMyODUgMTBMMjAuMzI4NSA3TDE3LjUgN0wxMy41IDExTDE3LjUgMTVIMjAuMzI4NEwxNy4zMjg0IDEyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMCAxMkg0LjY3MTYyTDEuNjcxNjIgMTVINC41MDAwNUw4LjUgMTFMNC40OTk5NSA3TDEuNjcxNTMgN0w0LjY3MTUzIDEwSDBWMTJaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
                '3' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDIwTDIwIDJIMThMMTggMjBIMjBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik02IDEwSDEyLjY3MTZMOS42NzE2MiA3TDEyLjUgN0wxNi41IDExTDEyLjUgMTVIOS42NzE1M0wxMi42NzE1IDEySDZWMTBaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
            ];
        @endphp
        <x-microweber-ui::radio-modern :options="$radioModernOptions" />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Radio Modern</label>
        @php
            $radioModernOptions = [
                '1' => 'XS',
                '2' => 'S',
                '3' => 'M',
                '4' => 'L',
                '5' => 'XL',
            ];
        @endphp
        <x-microweber-ui::radio-modern :options="$radioModernOptions" />
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
