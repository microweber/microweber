<div class="mt-3 mb-3" x-data="{showSettings: 'main'}">


    <div x-show="showSettings != 'main'">
        <button class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" type="button"
                x-on:click="showSettings = 'main'">
            <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
            <div class="ms-1 font-weight-bold">{{__('Back')}}</div>
        </button>
    </div>

    <style>
        .more-settings {
            width: 100%;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
            margin-left: 7px;
            padding-right: 7px;
            padding-bottom: 7px;
            margin-top: 5px;
            display:flex;
            justify-content: space-between;
            gap: 3px;
        }
    </style>

    <div x-show="showSettings == 'title'" x-transition:enter="tab-pane-slide-right-active">
        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Title')}}</label>
            <livewire:microweber-option::text optionKey="title" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Description')}}</label>
            <livewire:microweber-option::textarea optionKey="description" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>
    </div>

    <div x-show="showSettings == 'button'" x-transition:enter="tab-pane-slide-right-active">
        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Button Text')}}</label>
            <livewire:microweber-option::text optionKey="buttonText" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Button Link')}}</label>
            <livewire:microweber-option::text optionKey="buttonLink" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>
    </div>

    <div x-show="showSettings == 'main'" x-transition:enter="tab-pane-slide-right-active">

        <div>
            <label class="live-edit-label">{{__('Section')}}</label>
            <button x-on:click="showSettings = 'title'" class="mw-liveedit-button-actions-component more-settings">
                {{__('Title')}}
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M530-481 332-679l43-43 241 241-241 241-43-43 198-198Z"></path></svg>
            </button>
            <button x-on:click="showSettings = 'button'" class="mw-liveedit-button-actions-component more-settings">
                {{__('Button')}}
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M530-481 332-679l43-43 241 241-241 241-43-43 198-198Z"></path></svg>
            </button>
        </div>

        @php
            $alignOptions = [
                'left' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgMlYyMEg0TDQgMkgyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMTYgMTJIOS4zMjgzOEwxMi4zMjg0IDE1SDkuNDk5OTVMNS41IDExTDkuNTAwMDUgN0wxMi4zMjg1IDdMOS4zMjg0NyAxMEwxNiAxMFYxMloiIGZpbGw9IiMwRTBFMEUiLz4KPC9zdmc+Cg==" style="width: 22px; height: 22px;">',
                'center' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEwIDIwTDEwIDJIMTJMMTIgMjBIMTBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik0xNy4zMjg0IDEySDIyVjEwTDE3LjMyODUgMTBMMjAuMzI4NSA3TDE3LjUgN0wxMy41IDExTDE3LjUgMTVIMjAuMzI4NEwxNy4zMjg0IDEyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMCAxMkg0LjY3MTYyTDEuNjcxNjIgMTVINC41MDAwNUw4LjUgMTFMNC40OTk5NSA3TDEuNjcxNTMgN0w0LjY3MTUzIDEwSDBWMTJaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
                'right' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDIwTDIwIDJIMThMMTggMjBIMjBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik02IDEwSDEyLjY3MTZMOS42NzE2MiA3TDEyLjUgN0wxNi41IDExTDEyLjUgMTVIOS42NzE1M0wxMi42NzE1IDEySDZWMTBaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
            ];
        @endphp
        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Alignment')}} </label>
            <livewire:microweber-option::radio-modern :options="$alignOptions" optionKey="align" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-4 mb-3">
            <livewire:microweber-option::range-slider min="1" max="6" label="Max Columns"
                optionKey="maxColumns" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>
    </div>

</div>
