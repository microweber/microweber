<div class="mt-3 mb-3">

    @php
        $alignOptions = [
            'left' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgMlYyMEg0TDQgMkgyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMTYgMTJIOS4zMjgzOEwxMi4zMjg0IDE1SDkuNDk5OTVMNS41IDExTDkuNTAwMDUgN0wxMi4zMjg1IDdMOS4zMjg0NyAxMEwxNiAxMFYxMloiIGZpbGw9IiMwRTBFMEUiLz4KPC9zdmc+Cg==" style="width: 22px; height: 22px;">',
            'center' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEwIDIwTDEwIDJIMTJMMTIgMjBIMTBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik0xNy4zMjg0IDEySDIyVjEwTDE3LjMyODUgMTBMMjAuMzI4NSA3TDE3LjUgN0wxMy41IDExTDE3LjUgMTVIMjAuMzI4NEwxNy4zMjg0IDEyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMCAxMkg0LjY3MTYyTDEuNjcxNjIgMTVINC41MDAwNUw4LjUgMTFMNC40OTk5NSA3TDEuNjcxNTMgN0w0LjY3MTUzIDEwSDBWMTJaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
            'right' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDIwTDIwIDJIMThMMTggMjBIMjBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik02IDEwSDEyLjY3MTZMOS42NzE2MiA3TDEyLjUgN0wxNi41IDExTDEyLjUgMTVIOS42NzE1M0wxMi42NzE1IDEySDZWMTBaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
        ];
    @endphp

    <div>
        <label class="live-edit-label">{{__('Alignment')}} </label>
        <livewire:microweber-option::radio-modern :options="$alignOptions" optionKey="align" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <livewire:microweber-option::range-slider min="1" max="6" label="Max Columns"
            optionKey="max_columns" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

</div>
