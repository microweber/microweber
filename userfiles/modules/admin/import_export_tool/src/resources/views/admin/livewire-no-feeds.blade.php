
<div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_notifications.svg'); ">
    <h4>You don’t have any feeds for importing</h4>
    <p>Create your first import feed right now.<br>
        You are able to do that in very easy way!</p>
    <br>
    <button type="button" wire:loading.attr="disabled" wire:click="$emit('openModal', 'import_export_tool_new_import_modal')" class="btn btn-primary btn-rounded">Create new import feed</button>
</div>
