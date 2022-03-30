<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<link rel="stylesheet" href="<?php echo modules_url() . '/admin/import_export_tool/css/style.css?v=' .time(); ?>" type="text/css"/>

<script>
    function addNewImportModal() {
        var newImportDialog = mw.dialog({
            width: 900,
            height: 'auto',
            autoHeight:true,
            content: '<div id="mw_admin_import_modal_content">Loading...</div>',
            title: "<?php _e("Add new import"); ?>",
            id: 'mw_admin_import_modal'
        });

        var params = {};
        mw.load_module('admin/import_export_tool/modals/import', '#mw_admin_import_modal_content', function () {
            newImportDialog.center();
        }, params);
    }
</script>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">

        <?php
        $showTab = 'import';
        if (isset($_GET['tab'])) {
            $showTab = $_GET['tab'];
        }
        ?>
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center <?php if ($showTab =='import'):?> active <?php endif; ?>" data-toggle="tab" href="#import"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e("Import"); ?></a>
            <a class="btn btn-outline-secondary justify-content-center <?php if ($showTab =='export'):?> active <?php endif; ?>" data-toggle="tab" href="#export"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e("Export"); ?></a>
            <a class="btn btn-outline-secondary justify-content-center <?php if ($showTab =='settings'):?> active <?php endif; ?>" data-toggle="tab" href="#settings"><i class="mdi mdi-cog mr-1"></i> <?php _e("Settings"); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade <?php if ($showTab =='import'):?> show active <?php endif; ?>" id="import">
                <a href="#" class="btn btn-outline-primary" onclick="addNewImportModal()"><?php _e("Add new import"); ?></a>

                <div class="row">
                    <div class="col-md-12">


                    </div>
                </div>
            </div>
            <div class="tab-pane fade <?php if ($showTab =='export'):?> show active <?php endif; ?>" id="export">
                <a href="#" class="btn btn-outline-primary" onclick=""><?php _e("Add new export"); ?></a>
            </div>
            <div class="tab-pane fade <?php if ($showTab =='settings'):?> show active <?php endif; ?>" id="settings"></div>
        </div>

    </div>
</div>

