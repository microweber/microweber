<?php only_admin_access(); ?>
<script>
    mw.require('admin_package_manager.js');
</script>

<?php
$is_update_mode = false;
$core_update = false;

//$search_packages_params = array();
$search_packages_params['cache'] = true;

//$search_packages_params2 = $search_packages_params;
//$search_packages_params2['return_local_packages'] = true;
//$search_packages_params2['return_only_updates'] = true;


if (isset($params['show_only_updates']) and $params['show_only_updates']) {
    $search_packages_params['return_local_packages'] = true;
    $search_packages_params['return_only_updates'] = true;
    $is_update_mode = true;

}


$search_packages = mw()->update->composer_search_packages($search_packages_params);

 //$search_packages_update = mw()->update->composer_search_packages($search_packages_params2);
//$search_packages = mw()->update->composer_search_packages();


$packages_by_type = array();
$packages_by_type_with_update = array();

if ($search_packages and is_array($search_packages)) {
    foreach ($search_packages as $key => $item) {
        $package_has_update = false;


        //if ($item['type'] != 'microweber-core-update') {
            if (isset($item['has_update']) and $item['has_update']) {
                $package_has_update = true;
            }

            if ($package_has_update) {
                $package_has_update_key = $item['type'];
                if (!isset($packages_by_type_with_update[$package_has_update_key])) {
                    $packages_by_type_with_update[$package_has_update_key] = array();
                }
                $packages_by_type_with_update[$package_has_update_key][] = $item;

            }
        //}
        if ($item['type'] != 'microweber-core-update') {
            if (!isset($packages_by_type[$item['type']])) {
                $packages_by_type[$item['type']] = array();
            }
            $packages_by_type[$item['type']][] = $item;
        }
    }
}


if ($is_update_mode and isset($packages_by_type_with_update['microweber-core-update']) and !empty($packages_by_type_with_update['microweber-core-update'])) {
     $core_update = $packages_by_type_with_update['microweber-core-update'];
     unset($packages_by_type_with_update['microweber-core-update']);
    //$packages_by_type_with_update['microweber-core-update'] = array();
    //$packages_by_type_with_update['microweber-core-update'][] = $core_update;

}


$packages_by_type_all = array_merge($packages_by_type, $packages_by_type_with_update);

// dd($packages_by_type_all,$packages_by_type_with_update);
?>
<div class="section-header">
    <?php if ($is_update_mode) { ?>
        <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Updates"); ?></h2>
    <?php } else { ?>
        <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Packages"); ?></h2>
    <?php } ?>
    <div class="pull-right ">


        <div class="mw-field top-search">
            <input value="" name="module_keyword" placeholder="Search" type="text" autocomplete="off"
                   onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false">
            <span class="mw-ui-btn mw-field-append"
                  onclick="mw.url.windowHashParam('search',$(this).prev().val())"><span
                        class="mw-icon-search"></span></span>
        </div>


    </div>

    <div class="pull-right m-r-10" style="margin-top:1px;">
        <ul class="mw-ui-navigation mw-ui-navigation-horizontal">
            <li>
                <a href="javascript:;"><span class="mw-icon-gear"></span> <span class="mw-icon-dropdown"></span></a>
                <ul>
                    <?php if ($is_update_mode) { ?>

                        <li><a href="<?php print admin_url() ?>view:packages"><?php _e("Show all packages"); ?></a></li>

                    <?php } else { ?>

                        <li>
                            <a href="<?php print admin_url() ?>view:settings#option_group=updates"><?php _e("Show updates"); ?></a>
                        </li>
                    <?php } ?>
                    <li><a href="javascript:;"
                           onclick="mw.admin.admin_package_manager.reload_packages_list();"><?php _e("Reload packages"); ?></a>
                    </li>
                    <li><a href="javascript:;"
                           onclick="mw.admin.admin_package_manager.show_licenses_modal ();"><?php _e("Licenses"); ?></a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>


    <h2 class="pull-right "><span class="mai-modules"></span> <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline"
                                                                 href="<?php print admin_url(); ?>view:modules"><span
                    class="mw-icon-back"></span><?php _e("My Modules"); ?></a></h2>


</div>

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-packages-browser-nav-tabs-nav .mw-ui-navigation a.tablink',
            tabs: '#mw-packages-browser-nav-tabs-nav .tab'
            //linkable: 'section'
        });
    });
</script>

<style>
    .package-image {
        display: block;
        width: 150px;
        height: 150px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center top;
        margin: 10px auto 10px auto !important;
        overflow: hidden;
    }

    .package-image.package-microweber-template {
        width: 100%;
        height: 475px;
        cursor: -webkit-grab;
        cursor: -moz-grab;
        cursor: grab;
    }

    .package-image img {
        width: 100%;
    }

    html.has-scroll-hover .package-image {
        cursor: grabbing;
    }

    .mw-scroll-hover {
        width: 300px;
        height: 400px;
        overflow: hidden;
        cursor: grab;
    }

    .package-microweber-module {
        width: 100%;
        background-size: contain;
    }

    .package-ext-link {

        /* These are technically the same, but use both */
        overflow-wrap: break-word;
        word-wrap: break-word;

        -ms-word-break: break-all;
        /* This is the dangerous one in WebKit, as it breaks things wherever */
        word-break: break-all;
        /* Instead use this non-standard one: */
        word-break: break-word;

        /* Adds a hyphen where the word breaks, if supported (No Blink) */
        -ms-hyphens: auto;
        -moz-hyphens: auto;
        -webkit-hyphens: auto;
        hyphens: auto;
        overflow: hidden;

    }

    .package-item-footer {
        padding: 12px 0;
    }

    .package-item-footer .mw-ui-row {
        display: flex !important;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .package-item-footer strong {
        font-size: 16px;
    }

    .package-item-footer .title a {
        font-size: 16px;
        font-weight: bold;
    }

    .package-col-microweber-module .package-item-footer {
        display: block;
    }

    .package-col-microweber-module .package-item-footer div + div {
        text-align: right;
        flex-direction: row;
    }

    .mw-ui-box {
        height: 100%;
    }
</style>


<script>

    $(document).ready(function () {

        $('.package-microweber-template').each(function () {
            $(this)
                .on('mouseenter', function () {
                    var el = $(this);
                    el.stop();
                    this._hoverInterval = setInterval(function (node) {
                        node[0].scrollTop = node[0].scrollTop + 4
                    }, 10, el);
                })
                .on('mouseleave', function () {
                    clearInterval(this._hoverInterval);
                    if (!mw.scrollHoverPageY) {
                        $(this).stop().animate({scrollTop: 0}, 200);
                    }
                })
            /*.on('mousedown touchstart', function (e) {
                e.preventDefault();
                clearInterval(this._hoverInterval);
                $(this).stop();
                mw.scrollHover$ = $(this);
                $(document.documentElement).addClass('has-scroll-hover')
            })*/
        });

        $(document).on('mouseup touchend', function () {
            mw.scrollHover$ = null;
            mw.scrollHoverY = null;
            mw.scrollHoverPageY = null;
            $(document.documentElement).removeClass('has-scroll-hover')
        }).on('mousemove touchmove', function (e) {
            if (mw.scrollHover$) {
                var state;
                state = (mw.scrollHoverPageY > e.pageY ? mw.scrollHoverPageY - e.pageY : -(e.pageY - mw.scrollHoverPageY));
                if (isNaN(state)) {
                    state = 0;
                }
                if (Math.abs(state) > 20) {
                    state = state < 0 ? -2 : 2;
                }
                mw.scrollHover$[0].scrollTop = mw.scrollHover$[0].scrollTop + state;

                mw.scrollHoverPageY = e.pageY;
                mw.scrollHoverY = state;
            }
        })


    });

</script>

<script>

    $(document).ready(function () {
        $('.mw-sel-item-key-install').change(function () {
            var val = $("option:selected", this).val();
            var vkey = $(this).data('vkey');

            var holder = mw.tools.firstParentOrCurrentWithClass(this, 'js-package-install-content');

            $('.js-package-install-btn', holder).html("Install " + val);
            $('.js-package-install-btn', holder).data('vkey', val);
            $('.js-package-install-btn', holder).show();
            $('.js-package-install-btn-help-text', holder).hide();


        });

    });


</script>


<style>
    .mw-sel-item-key-install {
        border: none;
    }

    .marketplace-title {
        padding-bottom: 40px;
    }

    #mw-packages-browser-nav-tabs-nav .mw-ui-col-container {
        padding-left: 0;
    }

    #packages-browser-nav li .mw-notification-count{
        position: absolute;
        right: 8px;
        top: 8px;
    }
    #packages-browser-nav li a{
        white-space: normal;
        padding-right: 30px;
        position: relative;
        height: auto;
    }
</style>

<div class="admin-side-content" style="max-width: 90%">
    <?php if (!$is_update_mode) : ?>
        <div class="mw-flex-row m-b-20">
            <div class="mw-flex-col-xs-12 mw-flex-col-md-12 mw-flex-col-lg-12">
                <div class="mw-ui-col-container">
                    <h1 class="bold">Marketplace</h1>
                    <p>Welcome to the marketplace. Here you will find new modules, templates and updates.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>


  <?php

  /*  <div class="mw-flex-row p-l-20  m-b-20">
    <ul class="mw-ui-btn-nav mw-ui-btn-nav" >
        <?php if ($is_update_mode) { ?>

            <li><a  class="mw-ui-btn mw-ui-btn-notification" href="<?php print admin_url() ?>view:packages"><?php _e("Show all packages"); ?></a></li>

        <?php } else { ?>

            <li>
                <a  class="mw-ui-btn mw-ui-btn-notification" href="<?php print admin_url() ?>view:settings#option_group=updates"><?php _e("Show updates"); ?></a>
            </li>
        <?php } ?>



        <li><a href="javascript:;" onclick="mw.admin.admin_package_manager.reload_packages_list();" class="mw-ui-btn-info mw-ui-btn"><?php _e("Reload packages"); ?></a></li>
        <li><a href="javascript:;" onclick="mw.admin.admin_package_manager.show_licenses_modal ();" class="mw-ui-btn"><?php _e("Licenses"); ?></a></li>
    </ul>
    </div>*/

  ?>






    <div id="mw-packages-browser-nav-tabs-nav" class="mw-flex-row">


        <?php if ($packages_by_type_all) : ?>


            <div class="mw-flex-col-xs-12 mw-flex-col-md-4 mw-flex-col-lg-2">
                <div class="mw-ui-col-container">
                    <ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-menu" id="packages-browser-nav">
                        <?php if ($packages_by_type_all) : ?>

                        <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>
                            <?php

                            $pkkeys = explode('-', $pkkey);
                            array_shift($pkkeys);
                            $pkkeys = implode('-', $pkkeys);


                            ?>
                            <li class="m-0"><a class="tablink"
                                               href="javascript:void(0);"><?php print titlelize($pkkeys) ?></a></li>

                        <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ($packages_by_type_with_update): ?>


                        <li class="opened">
                            <?php
                            $total = 0;
                            $items = '';
                            foreach ($packages_by_type_with_update as $pkkey => $pkitems):
                                $pkkeys = explode('-', $pkkey);
                                array_shift($pkkeys);
                                $pkkeys = implode('-', $pkkeys);

                                if ($pkkeys == 'core-update') {
                                    $pkkeys = 'Version update';
                                } else {
                                    $pkkeys = $pkkeys . ' updates';
                                }
                                $count = count($pkitems);
                                $total += $count;
                                $items .= '<li><a class="tablink" href="javascript:;">' . titlelize($pkkeys) . '<sup class="mw-notification-count">' . $count . '</sup></a></li>';
                            endforeach;

                            ?>
                            <a href="javascript:;">Updates <sup style="display: none" class="mw-notification-count"><?php print $total; ?></sup></a>
                            <ul><?php print $items; ?></ul>
                            <?php endif; ?>


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
                        <hr>
                    <?php endforeach; ?>


                <?php endforeach; ?>
            <?php endif; ?>



            <?php if ($packages_by_type and !empty($packages_by_type)) : ?>

                <div class="mw-ui-col-container">


                <div>


                <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>
                    <div class="tab">
                        <?php if ($pkitems) : ?>
                            <div class="mw-flex-row">
                                <?php foreach ($pkitems as $key => $item): ?>
                                    <div
                                            class="mw-flex-col-xs-12 mw-flex-col-sm-6 mw-flex-col-md-12 mw-flex-col-lg-<?php print $item['type'] === 'microweber-module' ? '3' : '4'; ?>  m-b-20 package-col-<?php print $item['type']; ?>">
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
                <?php foreach ($packages_by_type_with_update as $pkkey => $pkitems): ?>
                    <div class="tab">
                        <?php if ($pkitems) : ?>
                            <div class="mw-flex-row">
                                <?php foreach ($pkitems as $key => $item): ?>
                                    <?php

                                    $holder_class = 'mw-flex-col-lg-4';
                                    if ($item['type'] == 'microweber-module') {
                                        $holder_class = 'mw-flex-col-lg-3';
                                    }
                                    if ($item['type'] == 'microweber-core-update') {
                                        $holder_class = 'mw-flex-col-lg-6  ';
                                    }
                                    ?>
                                    <div class="mw-flex-col-xs-12 mw-flex-col-sm-6 mw-flex-col-md-12  <?php print $holder_class; ?>  m-b-20 package-col-<?php print $item['type']; ?>">
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
                <?php if (!$core_update) : ?>
                    <div class="mw-ui-box-content tab">
                        No packages found.
                    </div>
                <?php endif; ?>
                </div>
                </div>


            <?php endif; ?>


        </div>
    </div>
</div>
