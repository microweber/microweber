
<x-microweber-ui::action-section>
    <x-slot name="title">
        <h5 class="font-weight-bold settings-title-inside"><?php _e('Delete Account');?> </h5>

    </x-slot>

    <x-slot name="description">
        <small class="text-muted">
          <?php _e('Permanently delete your account');?>  .
        </small>
    </x-slot>

    <x-slot name="content">
        <small class="text-muted">
           <?php _e('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to');?>   <retain class=""></retain>
        </small>

    <div class="mt-4">
    <x-microweber-ui::danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
        <?php _e('Delete Account');?>
    </x-microweber-ui::danger-button>
    </div>

    <!-- Delete User Confirmation Modal -->
    <x-microweber-ui::dialog-modal wire:model="confirmingUserDeletion">
    <x-slot name="title">
        <?php _e('Delete Account');?>
    </x-slot>

    <x-slot name="content">
        <?php _e('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account');?> .

<!--        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-microweber-ui::input type="password" class="mt-1 block w-3/4" placeholder="Password"
                                    x-ref="password"
                                    wire:model.defer="password"
                                    wire:keydown.enter="deleteUser" />

            <x-microweber-ui::input-error for="password" class="mt-2" />
        </div>-->
    </x-slot>

        <x-slot name="footer" class="d-flex align-items-center justify-content-between">
            <div>
                <x-microweber-ui::secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    <?php _e('Cancel');?>
                </x-microweber-ui::secondary-button>
            </div>

             <div>
                 <x-microweber-ui::danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                     <?php _e('Delete Account');?>
                 </x-microweber-ui::danger-button>
             </div>
        </x-slot>

</x-microweber-ui::dialog-modal>
</x-slot>
</x-microweber-ui::action-section>
