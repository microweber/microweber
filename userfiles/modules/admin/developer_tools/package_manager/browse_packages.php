<?php only_admin_access(); ?>
<script>
    mw.require('admin_package_manager.js');
</script>

<?php
$is_update_mode = false;
$core_update = false;

//$search_packages_params = array();
$search_packages_params['cache'] = true;

if (isset($params['show_only_updates']) and $params['show_only_updates']) {
    $search_packages_params['return_local_packages'] = true;
    $search_packages_params['return_only_updates'] = true;
    $is_update_mode = true;

}



$search_packages = mw()->update->composer_search_packages($search_packages_params);
//$search_packages = mw()->update->composer_search_packages();


$packages_by_type = array();


if ($search_packages and is_array($search_packages)) {
    foreach ($search_packages as $key => $item) {
        if (!isset($packages_by_type[$item['type']])) {
            $packages_by_type[$item['type']] = array();
        }
        $packages_by_type[$item['type']][] = $item;
    }
}

if (isset($packages_by_type['microweber-core-update']) and !empty($packages_by_type['microweber-core-update'])) {
    $core_update = $packages_by_type['microweber-core-update'];

}


?>

<div class="section-header">
    <?php if ($is_update_mode) { ?>
        <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Updates"); ?></h2>
    <?php } else { ?>
        <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Packages"); ?></h2>
    <?php } ?>
    <div class="pull-right">
        <div class="top-search">
            <input value="" name="module_keyword" placeholder="Search" type="text" autocomplete="off"
                   onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false">
            <span class="top-form-submit" onclick="mw.url.windowHashParam('search',$(this).prev().val())"><span
                        class="mw-icon-search"></span></span>
        </div>
    </div>

    <div class="pull-right m-r-10" style="margin-top:1px;">
        <ul class="mw-ui-navigation mw-ui-navigation-horizontal">
            <li>
                <a href="javascript:;"><span class="mw-icon-gear"></span> <span class="mw-icon-dropdown"></span></a>
                <ul>
                    <?php if(isset($_GET['only_updates']) and $_GET['only_updates']){ ?>

                        <li><a href="?all">Show all packages</a></li>

                    <?php } else { ?>

                        <li><a href="?only_updates=1">Show updates</a></li>

                    <?php } ?>
                    <li><a href="javascript:;" onclick="mw.admin.admin_package_manager.reload_packages_list();">Reload packages</a></li>
                    <li><a href="javascript:;"
                           onclick="mw.admin.admin_package_manager.show_licenses_modal ();">Licenses</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-packages-browser-nav-tabs-nav .mw-ui-navigation a',
            tabs: '#mw-packages-browser-nav-tabs-nav .tab'
        });
    });
</script>


<script>

    $( document ).ready(function() {
        $('.mw-sel-item-key-install').change(function() {
            var val = $("option:selected", this).val();
            var vkey= $(this).data('vkey');

            var holder = mw.tools.firstParentOrCurrentWithClass(this,'js-package-install-content');

            $('.js-package-install-btn',holder).html("Install "+val);
            $('.js-package-install-btn',holder).data('vkey',val);


        });

    });







</script>


<style>
    .mw-sel-item-key-install {
        border: none;
    }
</style>

<div class="admin-side-content" style="max-width: 90%">
    <div id="mw-packages-browser-nav-tabs-nav" class="mw-flex-row">


        <?php if ($packages_by_type) : ?>




        <div class="mw-flex-col-xs-12 mw-flex-col-md-4 mw-flex-col-lg-2">
            <div class="mw-ui-col-container">
                <ul class="mw-ui-box mw-ui-navigation" id="nav">

                    <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>
                        <?php

                        $pkkeys = explode('-', $pkkey);
                        array_shift($pkkeys);
                        $pkkeys = implode('-', $pkkeys);


                        ?>
                        <li><a href="javascript:;"><?php print titlelize($pkkeys) ?></a></li>

                    <?php endforeach; ?>


                </ul>
            </div>
        </div>

        <?php endif; ?>






        <div class="mw-flex-col-xs-12 mw-flex-col-md-8 mw-flex-col-lg-10">


            <?php if ($core_update) : ?>
                <?php foreach ($core_update as $pkkey => $pkitems): ?>


                    <?php foreach ($core_update as $key => $item): ?>
                        <div class="mw-flex-col-xs-12 mw-flex-col-sm-12 mw-flex-col-md-12 mw-flex-col-lg-12  m-b-20">
                            <?php
                            $view_file = __DIR__ . '/partials/package_item.php';

                            $view = new \Microweber\View($view_file);
                            $view->assign('item', $item);
                            $view->assign('no_img', true);
                            $view->assign('box_class', 'mw-ui-box-info ');


                            print    $view->display();
                            ?>
                        </div>
                    <?php endforeach; ?>


                <?php endforeach; ?>
            <?php endif; ?>


            <div class="mw-ui-col-container">


                <div class="mw-ui-box" style="width: 100%; padding: 12px 0;">





                    <?php if ($packages_by_type) : ?>
                        <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>
                            <div class="mw-ui-box-content tab">
                                <?php if ($pkitems) : ?>
                                    <div class="mw-flex-row">
                                        <?php foreach ($pkitems as $key => $item): ?>
                                            <div class="mw-flex-col-xs-12 mw-flex-col-sm-6 mw-flex-col-md-12 mw-flex-col-lg-4  m-b-20">
                                                <?php
                                                $view_file = __DIR__ . '/partials/package_item.php';

                                                $view = new \Microweber\View($view_file);
                                                $view->assign('item', $item);

                                                print    $view->display();
                                                ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                    <div class="mw-ui-box-content tab">
                        No packages found.

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
