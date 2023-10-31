@php
$randId = time() . rand(111,999);

@endphp

<div x-data="{
    selectedFont: 'Inter', openOptions:false,
    availableFonts: {{json_encode(app()->template->getFonts())}},
}"
     x-init="
      () => {

     setTimeout(function() {
            inputFontElement = document.getElementById('input-font-{{$randId}}');
            inputFontElement.dispatchEvent(new Event('loaded'));
            inputFontElement.addEventListener('input', function () {
                selectedFont = inputFontElement.value;
            });
        }, 300);

      if (mw.top().app && mw.top().app.fontManager) {

            mw.top().app.fontManager.subscribe((fonts) => {
              availableFonts = fonts;
            });

            mw.top().app.fontManager.subscribeToSelectedFont((selectedFontEvent) => {

                if (selectedFontEvent.applyToSelectedElement == '#{{$randId}}') {
                    selectedFont = selectedFontEvent.fontFamily;
                    openOptions = false;
                    inputFontElement = document.getElementById('input-font-{{$randId}}');
                    inputFontElement.value = selectedFont;
                    inputFontElement.dispatchEvent(new Event('input'));
                }

            });
        }
      }"
     class="form-control-live-edit-label-wrapper">

    <button type="button" class="form-select form-control-live-edit-input"
            :style="{ fontFamily: [selectedFont] }"
            x-on:click="openOptions = !openOptions" x-html="selectedFont">
    </button>

    <input type="hidden" id="input-font-{{$randId}}" {!! $attributes->merge([]) !!} />

    <div style="height:400px;overflow:scroll;" class="dropdown-menu form-control-live-edit-input ps-0" :class="[openOptions ? 'show':'']">

        <template x-for="availableFont in availableFonts">
            <button type="button" class="dropdown-item tblr-body-color"
                    x-on:click="()=> {
                        selectedFont = availableFont;
                        openOptions = false;
                        inputFontElement = document.getElementById('input-font-{{$randId}}');
                        inputFontElement.value = availableFont;
                        inputFontElement.dispatchEvent(new Event('input'));
                    }"
                    :style="{ fontFamily: [availableFont] }">
                <span style="font-size:16px" x-text="availableFont"></span>
            </button>
        </template>

        <button type="button"
                x-on:click="()=> {
                    mw.top().app && mw.top().app.fontManager.manageFonts({
                         applySelectionToElement: '#{{$randId}}'
                    });
                }"
                class="dropdown-item tblr-body-color">
            {{_e('Add more fonts')}}
        </button>

    </div>

</div>
