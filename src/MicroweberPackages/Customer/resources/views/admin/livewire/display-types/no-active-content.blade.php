
<div class="card-body mb-3">
    <div class="card-body">

        <div class="no-items-found customers py-5">
            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_clients.svg'); ">
                        <h4><?php _e('You don’t have customers yet'); ?></h4>
                        <p><?php _e('Here you can mange your cus'); ?></p>
                        <br/>
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-rounded">
                            <?php _e('Add customers'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
