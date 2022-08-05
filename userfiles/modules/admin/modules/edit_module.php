<?php
$id = false;

if (isset($params["data-module-id"])) {
    $id = $params["data-module-id"];
}

$data = array();
if ($id != false) {
    $data = mw()->module_manager->get('ui=any&limit=1&id=' . $id);
    if (isset($data[0])) {
        $data = $data[0];
    }
}
?>
<?php if (!empty($data)): ?>
    <script>mw.lib.require('mwui_init');</script>
    <script type="text/javascript">
        $(document).ready(function () {
            mw.$('#module_admin_settings_form_<?php print $params['id']; ?>').submit(function () {
                mw.form.post(mw.$('#module_admin_settings_form_<?php print $params['id']; ?>'), '<?php print site_url('api') ?>/module/save', function () {
                });

                return false;
            });

            mw.$('#module_uninstall_<?php print $params['id']; ?>').unbind('click');
            mw.$('#module_uninstall_<?php print $params['id']; ?>').click(function (e) {
                e.stopPropagation();
                e.preventDefault();

                if (confirm("<?php _ejs('Are you sure you want to UNINSTALL this module?'); ?>")) {
                    var for_module = {}
                    for_module.id = $(this).attr('data-module-id');
                    $.post('<?php print site_url('api') ?>/uninstall_module/', for_module, function (data) {
                        $('#module_uninstall_<?php print $params['id']; ?>').hide();
                        $('#module_install_<?php print $params['id']; ?>').show();
                        $('#module_open_<?php print $params['id']; ?>').hide();
                        mw.notification.success("<?php _e('Module uninstalled'); ?>");
                    });
                    return false;
                }
            });

            mw.$('#module_install_<?php print $params['id']; ?>').unbind('click');
            mw.$('#module_install_<?php print $params['id']; ?>').click(function () {
                mw.notification.success("<?php _e('Installing... please wait'); ?>");

                var for_module = {}
                for_module.for_module = $(this).attr('data-module-name');
                $.post('<?php print site_url('api') ?>/install_module/', for_module, function (data) {
                    $('#module_install_<?php print $params['id']; ?>').hide();

                    $('#module_uninstall_<?php print $params['id']; ?>').show();
                    $('#module_open_<?php print $params['id']; ?>').show();
                    mw.notification.success("<?php _e('Module is installed'); ?>");

                });

                return false;
            });

            mw.$('#module_update_<?php print $params['id']; ?>').off('click');
            mw.$('#module_update_<?php print $params['id']; ?>').on('click', function () {
                //  var for_module = {}
                var for_module = $(this).attr('data-module-name');
                mw.notification.warning("Installing update for module: " + for_module + '');

                $.post('<?php print admin_url() ?>view:modules?add_module=' + for_module, function (data) {
                    mw.notification.success("<?php _e('New update for module'); ?><b> " + for_module + '</b><?php _e('is installed'); ?>');
                });

                return false;
            });

        });
    </script>




    <?php
    $badge = '';
    if (isset($data['ui']) && $data['ui']) {
        $badge .='<span class="mw-modules-badge badge badge-info rounded-circle p-2 mr-1 tip" data-tip="'._e('Live edit', true).'"><i class="mdi mdi-eye text-primary"></i></span>';
    }
    if (isset($data['ui_admin']) && $data['ui_admin']) {
        $badge .='<span class="mw-modules-badge badge badge-secondary rounded-circle p-2 mr-1 tip" data-tip="'._e('Admin', true).'"><i class="mdi mdi-view-grid-plus"></i></span>';
    }
    if (isset($data['is_system']) && $data['is_system']) {
        $badge .='<span class="mw-modules-badge cog-badge badge rounded-circle p-2 mr-1 tip" data-tip="'._e('System', true).'"><i class="mdi mdi-cog text-success"></i></span>';
    }
    if (((isset($data['is_system']) && $data['is_system'] == 0) &&
        (isset($data['ui_admin']) && $data['ui_admin'] == 0) &&
        (isset($data['ui']) && $data['ui'] == 0)) || (isset($data['is_integration']) && $data['is_integration'])) {
        $badge .='<span class="mw-modules-badge cog-settings badge badge-danger rounded-circle p-2 mr-1 tip" data-tip="'._e('Integration', true).'"><i class="mdi mdi-wrench text-danger"></i></span>';
    }
    ?>

    <div class="card mw-modules-module-holder p-1">
        <div class="card-body px-2 pt-1" <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?>onclick="window.location.href = '<?php echo module_admin_url($data['module']) ?>';"<?php endif; ?>>
            <div class="text-start text-left pb-3">
            <?php echo $badge; ?>
            </div>

            <div class="h-100 d-flex align-items-center justify-content-center flex-column">
            <form class="admin-modules-list-form <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0) {
                print 'module-installed';
            } else {
                print 'module-uninstalled';
            } ?> " id="module_admin_settings_form_<?php print $params['id']; ?>">
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <?php if (isset($data['icon'])): ?>
                        <img data-module-icon="<?php print $data['icon'] ?>" class="module-img"  data-title="<?php print $data['module'] ?>"/>
                    <?php endif; ?>

                    <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?><a class="btn btn-link text-dark p-0" href='<?php module_admin_url($data['module']); ?>'><?php endif; ?>
                        <div class="admin-modules-list-description mt-0">
                            <h6>
                                <?php if (isset($data['name'])): ?>
                                    <?php _e($data['name']) ?>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?></a><?php endif; ?>

                    <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
                    <input type="hidden" name="installed" value="<?php print $data['installed'] ?>"/>
                    <input type="hidden" name="ui" value="<?php print $data['ui'] ?>"/>
                    <input type="hidden" name="ui_admin" value="<?php print $data['ui_admin'] ?>"/>
                    <input type="hidden" name="position" value="<?php print $data['position'] ?>"/>
                </div>

                <?php if (user_can_destroy_module($data)): ?>
                <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?>
                    <button class="btn btn-link btn-sm text-danger btn-rounded btn-icon position-absolute module-uninstall-btn" data-bs-toggle="tooltip" data-title="<?php _e("Uninstall"); ?>" name="uninstall" type="button" id="module_uninstall_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" data-module-id="<?php print$data['id'] ?>" value="đ"><i class="mdi mdi-close-thick"></i></button>
                    <!-- <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert module-ctrl-btn"><?php _e("Open"); ?></span>-->
                <?php else: ?>
                    <button class="btn btn-outline-success btn-sm position-absolute module-ctrl-btn" name="install" type="button" id="module_install_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" data-module-id="<?php print $data['id'] ?>"><?php _e("Install"); ?></button>
                <?php endif; ?>
                <?php endif; ?>

            </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
$(document).ready(function (){

    $('.module-item-module img,.mw-modules-module-holder img').each(function (){
        var src = this.dataset.moduleIcon.trim();
        var img = this;
        if(src.includes('.svg') && src.includes(location.origin)) {
            var el = document.createElement('div');
            el.className = img.className;
            // var shadow = el.attachShadow({mode: 'open'});
            var shadow = el;
            fetch(src)
                .then(function (data){
                    return data.text();
                }).then(function (data){
                var shImg = document.createElement('div');
                shImg.innerHTML = data;
                shImg.part = 'mw-module-icon';
                if(shImg.querySelector('svg') !== null) {
                    shImg.querySelector('svg').part = 'mw-module-icon-svg';

                    Array.from(shImg.querySelectorAll('style')).forEach(function (style) {
                        style.remove()
                    })
                    Array.from(shImg.querySelectorAll('[id],[class]')).forEach(function (item) {
                        item.removeAttribute('class')
                        item.removeAttribute('id')
                    })

                    shadow.appendChild(shImg);
                    if (img.parentNode) {
                        img.parentNode.replaceChild(el, img)
                    }
                }
            })
        } else {
            this.src = src;
        }
    })
})
</script>
