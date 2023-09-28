@php
$randId = time() . rand(111,999);
@endphp

<div x-data="{
    availableFonts: {{json_encode(\MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts())}},
}"
     x-init="
      () => {
      if (mw.top().app && mw.top().app.fontManager) {
            mw.top().app.fontManager.subscribe(function(fonts) {
                availableFonts = fonts;
                console.log(fonts);
            });

            mw.top().app && mw.top().app.fontManager.manageFonts({
               applySelectionToElement: '#{{$randId}}'
            });
        }
      }"
     class="form-control-live-edit-label-wrapper">

    <div {!! $attributes->merge(['class'=>'form-select form-control-live-edit-input']) !!} >
        <template x-for="availableFont in availableFonts">
            <div :style="{ fontFamily: [availableFont] }">
                <span x-text="availableFont"></span>
            </div>
        </template>
    </div>

    <div style="font-family:'Vollkorn'">
        Volkorn
    </div>

    <div style="font-family:'Arvo'">
        Arvo
    </div>

<!--    <div class="mt-1 mb-3">
        <button type="button" class="btn btn-link mw-admin-action-links mw-adm-liveedit-tabs"
                onclick="Livewire.emit('openModal', 'font-picker-modal')">
            Add more fonts
        </button>
    </div>-->
</div>
