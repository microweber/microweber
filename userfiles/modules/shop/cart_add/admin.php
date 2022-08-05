<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <?php
        $for_id = false;
        $for = 'content';

        if (isset($params['rel'])) {
            $params['rel_type'] = $params['rel'];
        }

        if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')) {
            $for_id = $params['content-id'] = POST_ID;
            $for = 'content';
        }

        if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')) {
            $for_id = $params['content-id'] = PAGE_ID;
            $for = 'content';
        }
        ?>

        <script>
            $(document).ready(function () {
                $(window).on("custom_fields.save", function (event) {
                    mw.reload_module_parent('#<?php print $params['id'] ?>');
                });
            });
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <?php if ($for_id): ?>
                <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <?php endif; ?>
            <a class="btn btn-outline-secondary justify-content-center <?php if (!$for_id): ?>active<?php endif; ?>" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <?php if ($for_id): ?>
                <div class="tab-pane fade show active" id="settings">
                    <!-- Settings Content -->
                    <div class="module-live-edit-settings module-cart-add-settings">
                        <module type="custom_fields/admin" data-content-id="<?php print intval($for_id); ?>"/>
                    </div>
                    <!-- Settings Content - End -->
                </div>
            <?php endif; ?>

            <div class="tab-pane fade <?php if (!$for_id): ?>show active<?php endif; ?>" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>

    </div>
</div>
