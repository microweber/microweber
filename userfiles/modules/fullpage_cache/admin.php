<?php must_have_access(); ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">

        <script>
            $(document).ready(function () {


            })
        </script>

        <div class="module-fullpage-cache-settings">





        </div>
    </div>
</div>
