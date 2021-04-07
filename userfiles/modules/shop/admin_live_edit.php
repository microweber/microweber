<?php
$rand = crc32(serialize($params));
?>

<script>
    $(mwd).ready(function () {
        MenuTabs = mw.tabs({
            nav: '#menu-tabs a',
            tabs: '.tab'
        });
    });
</script>

<div class="module-live-edit-settings">
    <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
        <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
        <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
    </nav>

    <div class="tab-content py-3">
        <div class="tab-pane fade show active" id="settings">

            SHOP SETTINGS


        </div>
        <div class="tab-pane fade" id="templates">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
