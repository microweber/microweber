import BaseComponent from "../../api-core/services/containers/base-class.js";

export class AdminFilament extends BaseComponent {
    constructor() {
        super();

        document.addEventListener('livewire:init', () => {
            this.init();
        });




    }

    init() {




        // listen for events from alpine and filament
    }
}
