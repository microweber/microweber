<div>

    <div class="col-12">
        <div class="row row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                            <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256"><path fill="currentColor" d="M117.25 157.92a60 60 0 1 0-66.5 0a95.83 95.83 0 0 0-47.22 37.71a8 8 0 1 0 13.4 8.74a80 80 0 0 1 134.14 0a8 8 0 0 0 13.4-8.74a95.83 95.83 0 0 0-47.22-37.71ZM40 108a44 44 0 1 1 44 44a44.05 44.05 0 0 1-44-44Zm210.14 98.7a8 8 0 0 1-11.07-2.33A79.83 79.83 0 0 0 172 168a8 8 0 0 1 0-16a44 44 0 1 0-16.34-84.87a8 8 0 1 1-5.94-14.85a60 60 0 0 1 55.53 105.64a95.83 95.83 0 0 1 47.22 37.71a8 8 0 0 1-2.33 11.07Z"/></svg>
                              </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <strong><?php echo $subscribers->total(); ?></strong>
                                </div>
                                <div class="text-secondary">
                                    Subscribers
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                            <span class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                             <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 1024 1024"><path fill="currentColor" d="M704 192h160v736H160V192h160v64h384zM288 512h448v-64H288zm0 256h448v-64H288zm96-576V96h256v96z"/></svg>
                            </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $listsCount }}
                                </div>
                                <div class="text-secondary">
                                    Lists
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                            <span class="bg-twitter text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M18 13v-2h4v2h-4Zm1.2 7L16 17.6l1.2-1.6l3.2 2.4l-1.2 1.6Zm-2-12L16 6.4L19.2 4l1.2 1.6L17.2 8ZM5 19v-4H4q-.825 0-1.412-.587T2 13v-2q0-.825.588-1.412T4 9h4l5-3v12l-5-3H7v4H5Zm9-3.65v-6.7q.675.6 1.088 1.463T15.5 12q0 1.025-.413 1.888T14 15.35Z"/></svg>
                            </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $campaignsCount }}
                                </div>
                                <div class="text-secondary">
                                    Campaigns
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                            <span class="bg-facebook text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M22 5.5H9c-1.1 0-2 .9-2 2v9a2 2 0 0 0 2 2h13c1.11 0 2-.89 2-2v-9a2 2 0 0 0-2-2m0 3.67l-6.5 3.33L9 9.17V7.5l6.5 3.31L22 7.5v1.67M5 16.5c0 .17.03.33.05.5H1c-.552 0-1-.45-1-1s.448-1 1-1h4v1.5M3 7h2.05c-.02.17-.05.33-.05.5V9H3c-.55 0-1-.45-1-1s.45-1 1-1m-2 5c0-.55.45-1 1-1h3v2H2c-.55 0-1-.45-1-1Z"/></svg>
                            </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{$emailsSentCount}}
                                </div>
                                <div class="text-secondary">
                                    Emails sent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

                                // Reload the modules
                                mw.reload_module('newsletter/subscribers_list')
                                mw.reload_module_parent('newsletter')
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
            <div>
                Selected: {{ count($this->checked) }}
            </div>
            <button class="btn btn-outline-danger" wire:click="deleteSelected()" onclick="return confirm('Are you sure?')">
                <?php _e('Delete selected'); ?>
            </button>
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
