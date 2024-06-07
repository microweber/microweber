import BaseComponent from "../../api-core/services/containers/base-class.js";

export class AdminFilament extends BaseComponent {
    constructor() {
        super();

        document.addEventListener('livewire:init', () => {
            this.init();
        });


    }

    init() {


        Livewire.on('mw-option-saved', function ($event) {
            if ($event.optionGroup !== undefined) {
                if (self !== top) {
                    if (typeof top.mw !== 'undefined'
                        && typeof top.mw.top !== 'undefined'
                        && typeof top.mw.top().app !== 'undefined'
                        && typeof top.mw.top().app.liveEdit !== 'undefined'
                    ) {
                        top.mw.top().reload_module_everywhere('#' + $event.optionGroup);
                    }
                }
            }


        });


        // listen for events from alpine and filament
    }
}
