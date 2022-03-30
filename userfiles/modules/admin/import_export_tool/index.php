<?php must_have_access(); ?>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup/css/style.css' ?>" type="text/css"/>

<script type="text/javascript">
    var importContentFromFileText = '<?php _e("Importing content from file"); ?>';
    var userfilesUrl = '<?php echo userfiles_url() ?>';
    var moduleImagesUrl = '<?php echo modules_url() . '/admin/backup/images/' ?>';
    mw.require("<?php print $config['url_to_module']; ?>js/upload-file.js");
    mw.require("<?php print $config['url_to_module']; ?>js/import.js?v=10");
    mw.require("<?php print $config['url_to_module']; ?>js/export.js?v=10");
    mw.lib.require('mw_icons_mind');

</script>
<?php $here = $config['url_to_module']; ?>

<div class="row mt-3">
    <div class="col-6 mb-4"></div>
</div>

<div class="mw_edit_page_right">

</div>
