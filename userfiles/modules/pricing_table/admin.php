<?php only_admin_access(); ?>

<?php
$columns = get_option('columns', $params['id']);
$feature = get_option('feature', $params['id']);
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.lib.require('bootstrap3ns');
    });
</script>

<div class="module-live-edit-settings">
    <div class="bootstrap3ns">

        <div class="row">
            <div class="col-xs-12">

                <module type="admin/modules/templates"/>

            </div>
        </div>

    </div>
</div>