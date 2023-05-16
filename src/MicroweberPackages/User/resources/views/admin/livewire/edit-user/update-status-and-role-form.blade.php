<div>
    <x-microweber-ui::form-section submit="updateProfileInformation">
        <x-slot name="title">
            <?php _e('User status and role');?>
        </x-slot>

        <x-slot name="description">
            <?php _e('Update your accounts profile information and email address');?>.
        </x-slot>

        <x-slot name="form">

            <!-- Role -->
            <div class="col-span-6 sm:col-span-4 my-3">
                <x-microweber-ui::label for="role" value="Role of the user" />
                <div class="text-muted mb-2"><?php _e('Choose the current role of the user');?>. </div>
                <select id="role" class="form-select" wire:model.defer="state.is_admin">
                    <option value="0"><?php _e('User');?></option>
                    <option value="1"><?php _e('Admin');?></option>
                </select>
                <x-microweber-ui::input-error for="role" class="mt-2" />
            </div>

            <!-- Is Active -->
            <div class="col-span-6 sm:col-span-4 my-3">
                <x-microweber-ui::label for="is_active" value="Is Active?" />
                <div class="text-muted mb-2"><?php _e('Choose the current status of this user');?></div>
                <select id="is_active" class="form-select" wire:model.defer="state.is_active">
                    <option value="1"><?php _e('Active');?></option>
                    <option value="0"><?php _e('Disabled');?></option>
                </select>
                <x-microweber-ui::input-error for="is_active" class="mt-2" />
            </div>

            <br />
            <a href="{{api_url('users/export_my_data?user_id=' . $state['id'])}}" class="btn btn-outline-primary btn-sm"><?php _e('Export user data');?></a>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="$emit('openModal', 'admin::user-tos-log', {{ json_encode(['userId' => $state['id']]) }})"><?php _e('Terms agreement log');?></button>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="$emit('openModal', 'admin::user-login-attempts', {{ json_encode(['userId' => $state['id']]) }})"><?php _e('Login attempts');?></button>

        </x-slot>

        <x-slot name="actions">
            <x-microweber-ui::action-message class="mr-3" on="saved">
                <?php _e('Saved');?>.
            </x-microweber-ui::action-message>

            <x-microweber-ui::button>
                <?php _e('Save');?>
            </x-microweber-ui::button>
        </x-slot>
    </x-microweber-ui::form-section>
</div>
