@php
$randId = time() . rand(111,999);
@endphp

<div x-data="{
    selectedFont: 'Inter', openOptions:false,
    availableFonts: {{json_encode(\MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts())}},
}"
     x-init="
      () => {
      if (mw.top().app && mw.top().app.fontManager) {
            mw.top().app.fontManager.subscribe(function(fonts) {
                availableFonts = fonts;
                console.log(fonts);
            });
        }
      }"
     class="form-control-live-edit-label-wrapper">

    <button type="button" class="form-select form-control-live-edit-input"
            :style="{ fontFamily: [selectedFont] }" 
            x-on:click="openOptions = !openOptions" x-html="selectedFont">
    </button>

    <input type="hidden" {!! $attributes->merge(['class'=>'form-select form-control-live-edit-input']) !!} />

    <button class="dropdown-menu form-control-live-edit-input ps-0" :class="[openOptions ? 'show':'']">
        <template x-for="availableFont in availableFonts">
            <button type="button" class="dropdown-item tblr-body-color"
                    x-on:click="selectedFont = availableFont; openOptions = false"
                    :style="{ fontFamily: [availableFont] }">
                <span style="font-size:16px" x-text="availableFont"></span>
            </button>
        </template>
    </div>

    <div class="mt-1 mb-3">
        <button type="button"
                x-on:click="()=> {
                    mw.top().app && mw.top().app.fontManager.manageFonts({
                         applySelectionToElement: '#{{$randId}}'
                    });
                }"
                class="btn btn-link mw-admin-action-links mw-adm-liveedit-tabs">
            Add more fonts
        </button>
    </div>

</div>
