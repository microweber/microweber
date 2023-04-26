<div class="mx-5 my-5">
    <form wire:submit.prevent="updatePassword">
        <div>
           <h2> Update Password</h2>
            Ensure your account is using a long, random password to stay secure.
        </div>

        <div class="col-span-6 sm:col-span-4 mt-4">
            <x-microweber-ui::label for="password" value="Password" />
            <x-microweber-ui::input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="password" />
            <x-microweber-ui::input-error for="password" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-microweber-ui::action-message class="mr-3" on="saved">
                Saved.
            </x-microweber-ui::action-message>
            <x-microweber-ui::button>
                Save
            </x-microweber-ui::button>
        </div>
    </form>
</div>
