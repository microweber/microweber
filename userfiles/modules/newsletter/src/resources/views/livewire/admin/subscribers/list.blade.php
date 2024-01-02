<div>

    <div class="card mt-4">
    <div class="card-body">

    <?php if ($subscribers): ?>

    <div class="d-flex justify-content-between align-items-center">
        <div>
             <h4><?php _e('Subscribers list'); ?></h4>
        </div>
        <div class="">


            <script>
                function edit_subscriber(id = false) {
                    var data = {};
                    data.id = id;
                    edit_subscriber_modal = mw.tools.open_module_modal('newsletter/edit_subscriber', data, {overlay: true, skin: 'simple'});
                }

                function delete_subscriber(id) {
                    var ask = confirm("<?php _ejs('Are you sure you want to delete this subscriber?'); ?>");
                    if (ask == true) {
                        var data = {};
                        data.id = id;
                        $.ajax({
                            url: mw.settings.api_url + 'newsletter_delete_subscriber',
                            type: 'POST',
                            data: data,
                            success: function (result) {
                                mw.notification.success('<?php _ejs('Subscriber deleted'); ?>');
                                window.Livewire.emit('refreshSubscribers');
                            }
                        });
                    }
                    return false;
                }
            </script>

            <a href="javascript:;" class="btn btn-outline-primary mb-3" onclick="edit_subscriber();">
                <i class="mdi mdi-plus"></i> <?php _e('Add new subscriber'); ?>
            </a>
            <a href="javascript:;" class="btn btn-outline-success mb-3"
               onclick="Livewire.emit('openModal', 'admin-newsletter-import-subscribers-modal')" >
                <?php _e('Import Subscribers'); ?>
            </a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Search..." wire:model="keyword" />
    </div>

    <div>
        @if(!empty($this->checked))
            <div class="px-2 py-2">
                <div>
                    Selected: {{ count($this->checked) }}
                </div>
                <button class="btn btn-outline-danger" wire:click="deleteSelected()" onclick="return confirm('Are you sure?')">
                    <?php _e('Delete selected'); ?>
                </button>
            </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th class="font-weight-bold" scope="col" width="40px">
                    <input type="checkbox" wire:click="selectAll()" />
                </th>
                <th class="font-weight-bold" scope="col"><?php _e('Name'); ?></th>
                <th class="font-weight-bold" scope="col"><?php _e('E-mail'); ?></th>
                <th class="font-weight-bold" scope="col"><?php _e('Subscribed at'); ?></th>
                <th class="font-weight-bold" scope="col"><?php _e('Subscribed'); ?></th>
                <th class="font-weight-bold text-center" scope="col"><?php _e('Action'); ?></th>
            </tr>
            </thead>

            <tbody class="small">
            <?php foreach ($subscribers as $key => $subscriber): ?>
            <tr>
                <td>
                    <input type="checkbox" wire:model="checked" value="<?php print $subscriber['id']; ?>" />
                </td>
                <td data-label="<?php _e('Name'); ?>"><?php print $subscriber['name']; ?></td>
                <td data-label="<?php _e('E-mail'); ?>"><?php print $subscriber['email']; ?></td>
                <td data-label="<?php _e('Subscribed at'); ?>"><?php print $subscriber['created_at']; ?></td>
                <td data-label="<?php _e('Subscribed'); ?>">
                    <?php
                    if ($subscriber['is_subscribed']) {
                        _e('Yes');
                    } else {
                        _e('No');
                    }
                    ?>
                </td>
                <td class="text-center">
                    <button class="btn btn-outline-primary btn-sm" onclick="edit_subscriber('<?php print $subscriber['id']; ?>')"><?php _e('Edit'); ?></button>

                    <button class="btn btn-outline-danger btn-sm" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')">
                        <i class="fa fa-times"></i>  &nbsp; Delete
                    </button>

                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info"><?php _e("You don't have any subscribers yet"); ?></div>
    <?php endif; ?>

    <div>
        <div class="d-flex justify-content-center mt-4">
            {!! $subscribers->links('microweber-ui::livewire.pagination') !!}
        </div>
    </div>

</div>
</div>
</div>
