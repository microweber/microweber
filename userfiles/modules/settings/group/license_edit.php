<?php use MicroweberPackages\ComposerClient\Client;

only_admin_access(); ?>

<?php $id = false; ?>
<?php $lic = false; ?>
<?php $local_key = false; ?>
<?php if (isset($lic['rel_type'])): ?>
    <?php
    $params['prefix'] = $lic['rel_type'];
    ?>
<?php endif; ?>

<?php
if (isset($params['lic_id'])) {
    if (intval($params['lic_id']) == 0) {
        $lic = array();
        $lic['prefix'] = '';
        $lic['local_key'] = '';
        $lic['id'] = '';
    } else {
        $lic = mw()->update->get_licenses('one=1&id=' . $params['lic_id']);
    }
} elseif (isset($params['prefix'])) {
    $lic = mw()->update->get_licenses('one=1&rel=' . $params['prefix']);
}

if ($lic) {
    $local_key = $lic['local_key'];
    $id = $lic['id'];
}

if (!isset($params['prefix'])) {
    $params['prefix'] = '';
}
?>


<?php if (!isset($params['prefix'])): ?>
    <?php return; ?>
<?php endif; ?>


<script type="text/javascript">
    $(document).ready(function () {
        var form = mw.$('#activate-form-<?php print $params['id']; ?>');
        form.off('submit')
        form.on('submit', function () {

            var data = form.serializeArray();
            var url = "<?php print site_url('api') ?>/mw_save_license"

            $.ajax({
                type: 'POST',
                url: url,
                data: data,

                async:false,
                success: function(result) {

                    if (typeof licensemodal !== 'undefined'){
                        licensemodal.remove();
                    }

                    if(typeof(result.is_active) !== "undefined" && result.is_active === true) {
                        mw.notification.success(result.warning);
                    } else{
                        mw.notification.error(result.warning);
                    }

                    //   mw.dialog.get('[parent-module="settings/group/license_edit"]').remove()

                    mw.reload_module('#<?php print $params['id']; ?>');


                }
            });

            return false;
        });
    });
</script>
<?php
if(!$params['prefix']){
    $url = 'https://microweber.org/go/whitelabel';
} else {
    $url = 'https://microweber.org/go/market?prefix='.$params['prefix'];
}
if(isset($params['require_name'])) {
    $composerClient = new \MicroweberPackages\Package\MicroweberComposerClient();
    $item = $composerClient->getPackageByName($params['require_name']);
    if (!empty($item)) {
        $item = \MicroweberPackages\Package\MicroweberComposerPackage::format($item);
        if(isset($item['buy_link'])){
            $url = $item['buy_link'];
        }

    }
}
?>

<form class="mw-license-key-activate" id="activate-form-<?php print $params['id'] ?>">

    <?php if (isset($lic['status'])): ?>
        <div class="alert alert-dismissible <?php if ($lic['status'] == 'active'): ?>alert-success<?php else: ?>alert-danger<?php endif; ?>"><?php _e('License Status:'); ?><?php _e(ucwords($lic['status'])) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label class="control-label"><?php _e('Enter the License Key'); ?><?php print ' ' . $params['prefix'] ?></label>
        <small class="text-muted d-block mb-2"><?php _e('Don\'t have a license key? You can get it from here:'); ?> <a target="_blank" href="<?php print $url; ?>"><?php _e('Get license key'); ?></a></small>
        <input type="hidden" name="rel_type" value="<?php print $params['prefix']; ?>">
        <input name="activate_on_save" type="hidden" value="1"/>
        <?php if ($id): ?>
            <input name="id" type="hidden" value="<?php print $id; ?>"/>
        <?php endif; ?>

        <input type="text" name="local_key" class="form-control" value="<?php print $local_key; ?>" placeholder="<?php _e('License key'); ?>">
    </div>

    <div class="row">
        <div class="col d-flex align-items-center justify-content-between">
            <button type="submit" value="Activate" class="btn btn-success"><?php _e('Save key'); ?></button>
        </div>
    </div>

    <hr class="thin"/>

    <div class="row">
        <div class="col-12">
            <small><?php _e('Have a problem with your White Label license key?'); ?> <a href="javascript:;" onmousedown="mw.contactForm();"><?php _e('Contact us.'); ?></a></small>
        </div>
    </div>
</form>
