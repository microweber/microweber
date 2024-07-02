<div>
    <div class="text-center my-8">
        <div x-data="editCampaignTitle()" class="flex items-center justify-center p-4 m-auto">
            <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex gap-1 select-none cursor-pointer text-2xl">
                <span x-text="text"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-2 relative -top-2" viewBox="0 0 20 20">
                    <path fill="currentColor" d="m2.292 13.36l4.523 4.756L.5 20zM12.705 2.412l4.522 4.755L7.266 17.64l-4.523-4.754zM16.142.348l2.976 3.129c.807.848.086 1.613.086 1.613l-1.521 1.6l-4.524-4.757L14.68.334l.02-.019c.119-.112.776-.668 1.443.033" />
                </svg>
            </a>
            <input wire:model.live="state.name" type="text" x-model="text" x-show="isEditing" @click.away="toggleEditingState" @keydown.enter="disableEditing" @keydown.window.escape="disableEditing"
                   class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-2 px-4 appearance-none leading-normal w-[25rem]" x-ref="input">
        </div>
    <script>
        function editCampaignTitle() {
            return {
                text: @entangle('state.name'),
                isEditing: false,
                toggleEditingState() {
                    this.isEditing = !this.isEditing;

                    if (this.isEditing) {
                        this.$nextTick(() => {
                            this.$refs.input.focus();
                        });
                    }
                },
                disableEditing() {
                    this.isEditing = false;
                }
            };
        }
    </script>

        <div class="mt-4">
            <h1 class="text-2xl font-bold">
                Setup your email marketing campaign
            </h1>
            <p class="text-gray-600">
                Continue with the wizard to create a new email marketing campaign
            </p>
        </div>

    </div>

    <div class="mb-12">
        {{$this->form}}
    </div>

    <x-filament-actions::modals />

</div>
