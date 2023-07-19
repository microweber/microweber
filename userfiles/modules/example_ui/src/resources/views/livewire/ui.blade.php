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
        <label class="live-edit-label">Select Modern</label>
        @php
            $selectModernOptions = [
                '1' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	  height="24px" viewBox="0 339.515 612 115.645" enable-background="new 0 339.515 612 115.645"
	 xml:space="preserve">
<path id="XMLID_110_" fill="#0086db" d="M115.056,427.62L115.056,427.62c-1.02,1.836-2.244,3.672-3.468,5.304l0,0
	c-2.652,3.061-5.916,5.304-9.996,6.528L61.608,452.1c-1.836,0.612-3.672,0.204-5.304-0.815c-1.632-1.021-2.652-2.652-2.652-4.488
	l-7.344-78.744l1.632,0.204l3.672,0.204l7.141,0.612c2.244,0.204,4.08,1.836,4.284,3.672l7.344,57.936l15.3-3.468l-8.771-56.916
	l1.836,0.204l2.651,0.204l4.896,0.408c1.632,0.204,3.264,1.632,3.468,3.264l8.976,49.98l11.425-2.448l-9.996-49.776
	c-1.225-6.12-6.937-11.628-13.057-12.444l-53.651-7.548c-1.836-0.204-3.672,0.408-5.101,1.632c-1.428,1.224-2.04,3.06-2.04,4.896
	l5.101,89.352l0.204,5.101l0.204,2.04c-15.708-3.672-27.133-15.912-27.133-32.437L4.08,364.788c0-7.14,2.651-12.852,7.344-17.136
	c4.08-3.876,9.588-6.528,15.912-7.548c5.1-0.816,10.607-0.816,16.116,0.204l41.615,8.16c11.832,2.244,22.236,12.852,24.889,24.48
	l7.548,34.272C119.136,414.156,118.116,421.5,115.056,427.62z"/>
<g id="XMLID_57_">
	<g id="XMLID_213_">
		<path id="XMLID_237_" fill="#0086db" d="M163.608,364.176h17.34l4.488,41.412h0.204l22.032-41.412h17.544l-12.036,57.12h-11.832
			l10.404-45.492h-0.204l-24.276,45.492h-9.588l-5.508-45.492h-0.204l-8.568,45.492h-11.832L163.608,364.176z"/>
		<path id="XMLID_234_" fill="#0086db" d="M232.152,379.884h11.425l-8.772,41.412H223.38L232.152,379.884z M245.004,373.56H233.58
			l2.04-9.384h11.424L245.004,373.56z"/>
		<path id="XMLID_232_" fill="#0086db" d="M276.42,394.164c0-4.896-2.652-6.936-7.548-6.936c-8.568,0-11.628,10.404-11.628,17.543
			c0,4.896,2.04,8.977,7.752,8.977c5.1,0,7.956-3.672,9.18-7.752H285.6c-3.468,10.404-10.607,16.32-21.42,16.32
			c-11.424,0-18.36-5.713-18.36-17.748c0-14.076,8.568-25.908,23.257-25.908c9.995,0,18.359,4.488,18.563,15.504H276.42z"/>
		<path id="XMLID_230_" fill="#0086db" d="M297.228,379.884h10.812l-1.632,7.344l0.204,0.204c2.652-5.712,8.16-8.772,14.28-8.772
			c1.224,0,2.244,0,3.468,0.204l-2.244,11.016c-1.632-0.408-3.264-0.816-4.896-0.816c-8.772,0-11.832,6.528-13.26,13.668
			l-3.876,18.359H288.66L297.228,379.884z"/>
		<path id="XMLID_227_" fill="#0086db" d="M345.168,378.864c11.424,0,19.176,5.508,19.176,17.544
			c0,14.484-8.977,26.111-24.072,26.111c-11.424,0-18.972-5.916-18.972-17.748C321.504,390.492,330.48,378.864,345.168,378.864z
			 M340.884,413.748c8.567,0,12.239-10.404,12.239-17.34c0-5.508-2.447-9.18-8.363-9.18c-8.364,0-12.036,10.2-12.036,17.136
			C332.724,409.464,335.579,413.748,340.884,413.748z"/>
		<path id="XMLID_225_" fill="#0086db" d="M413.1,421.296h-11.832l-1.428-29.172l0,0l-12.853,28.968h-11.832l-4.487-41.412h11.628
			l1.632,29.171h0.204l12.647-29.171h11.424l1.225,28.968h0.204l13.26-28.968h12.036L413.1,421.296z"/>
		<path id="XMLID_222_" fill="#0086db" d="M444.924,403.344c0,1.021,0,1.836,0,2.448c0,4.896,3.06,8.16,9.18,8.16
			c4.488,0,6.732-3.06,8.568-5.712h11.424c-3.672,9.18-9.588,14.279-21.42,14.279c-11.017,0-18.36-6.324-18.36-18.155
			c0-13.26,8.772-25.5,22.848-25.5c11.425,0,19.177,6.12,19.177,18.155c0,2.244-0.204,4.488-0.612,6.528h-30.804V403.344z
			 M465.119,396.204c0-4.284-1.02-8.772-7.752-8.772c-6.323,0-9.588,4.08-11.22,8.772H465.119z"/>
		<path id="XMLID_219_" fill="#0086db" d="M490.212,364.176h11.424l-4.284,20.196h0.204c3.468-3.876,6.731-5.508,12.24-5.508
			c10.403,0,14.892,7.956,14.892,17.544c0,12.852-7.344,25.908-21.42,25.908c-5.712,0-11.016-1.836-13.056-7.549h-0.204
			l-1.429,6.528h-10.403L490.212,364.176z M492.864,405.18c0,5.1,3.061,8.772,8.16,8.772c8.568,0,12.24-10.2,12.24-17.341
			c0-5.099-2.244-9.179-8.16-9.179C496.331,387.432,492.864,397.836,492.864,405.18z"/>
		<path id="XMLID_216_" fill="#0086db" d="M539.783,403.344c0,1.021,0,1.836,0,2.448c0,4.896,3.061,8.16,9.181,8.16
			c4.487,0,6.731-3.06,8.567-5.712h11.424c-3.672,9.18-9.588,14.279-21.42,14.279c-11.016,0-18.359-6.324-18.359-18.155
			c0-13.26,8.771-25.5,22.848-25.5c11.424,0,19.176,6.12,19.176,18.155c0,2.244-0.204,4.488-0.611,6.528h-30.805V403.344z
			 M559.98,396.204c0-4.284-1.021-8.772-7.752-8.772c-6.324,0-9.588,4.08-11.22,8.772H559.98z"/>
		<path id="XMLID_214_" fill="#0086db" d="M581.604,379.884h10.812l-1.632,7.344l0.204,0.204c2.652-5.712,8.16-8.772,14.28-8.772
			c1.224,0,2.244,0,3.468,0.204l-2.244,11.016c-1.632-0.408-3.264-0.816-4.896-0.816c-8.772,0-11.832,6.528-13.26,13.668
			l-3.876,18.359h-11.425L581.604,379.884z"/>
	</g>
</g>
</svg>',
                '2' => 'Modern Option 2',
                '3' => '<b>You can add <span class="text-danger">html</span></b>',
            ];
        @endphp
        <x-microweber-ui::select-modern :options="$selectModernOptions" />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Menu with Dropdowns</label>
        <ul class="nav navbar-light">
                <x-microweber-ui::dropdown id="js-dropdown-id">
                    <x-slot name="trigger">
                        <x-microweber-ui::button type="button">
                            My Cool Dropdown
                        </x-microweber-ui::button>
                    </x-slot>
                    <x-slot name="content">
                        <x-microweber-ui::dropdown-link href="">
                            Dropdown link 1
                        </x-microweber-ui::dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <x-microweber-ui::dropdown-link href="">
                            Dropdown link 2
                        </x-microweber-ui::dropdown-link>
                    </x-slot>

                </x-microweber-ui::dropdown>
            </ul>
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
