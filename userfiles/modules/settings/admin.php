<?php $rand = $params['id']; ?>
<?php
must_have_access(); ?>
<?php $option_groups = array('website', 'users', 'template', 'email'); ?>
<script>
    mw.require('forms.js');
</script>

<?php
$show_group = false;
?>

<?php
if (isset($_GET['group'])) {
    $show_group = $_GET['group'];
} else if (isset($params['group'])) {
    $show_group = $params['group'];
} else if (isset($_GET['option_group'])) {
    $show_group = $_GET['option_group'];
}
?>

<script type="text/javascript">
    _settingsSort = function (group_set) {
         var group = mw.url.windowHashParam('option_group');

         if(group_set){
           //  group = group_set;
           //  alert(group);
         }

        if (group != undefined) {
            mw.$('#settings_admin_<?php print $rand; ?>').attr('option_group', group);

        }
        else {
            mw.$('#settings_admin_<?php print $rand; ?>').attr('option_group', 'admin__modules');
        }
        mw.$('#settings_admin_<?php print $rand; ?>').attr('is_system', 1);

        mw.tools.loading(document.querySelector('#settings_admin_<?php print $rand; ?>'), true)

        mw.load_module('settings/system_settings', '#settings_admin_<?php print $rand; ?>', function () {
            mw.tools.loading(document.querySelector('#settings_admin_<?php print $rand; ?>'), false)
        });

    }


    mw.on.hashParam('ui', _settingsSort);

    mw.on.hashParam('option_group', function (pval) {

        if (pval != false) {

        }
        else {
            mw.url.windowHashParam('option_group', 'admin__modules');
        }
        if (pval != false) {
            _settingsSort(pval)

        }  else {
            _settingsSort();
        }

        $(".active-parent li.active, #mw-admin-main-menu .active .active").removeClass('active');
        // var link = $('a[href*="option_group=' + this + '"]');
        //
        // link
        //     .parent()
        //     .addClass('active');

    });


    mw.on.hashParam('installed', function () {

        _settingsSort();

    });

    $(document).ready(function () {
        var group = mw.url.windowHashParam('option_group');

        if (typeof group == 'undefined') {

            mw.$(".mw-admin-side-nav .item-website").addClass("active");
        }
    });
</script>
<script type="text/javascript">
    //mw.require("options.js");
    mw.require("<?php print $config['url_to_module']; ?>settings.css");


</script>


<div id="settings_admin_<?php print $rand; ?>" class=" ">
    <?php if ($show_group) { ?>
sdadaa
<module type="settings/group/<?php print $show_group ?>"  />
    <?php }  ?>
</div>

<?php show_help('settings'); ?>
