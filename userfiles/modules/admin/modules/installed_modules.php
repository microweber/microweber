<script type="text/javascript">
    mw.require('forms.js');
</script>

<script type="text/javascript">

    $(document).ready(function () {
        mw.dropdown();
        mw.$('#modules_categories_tree_<?php print $params['id']; ?> .fixed-side-column-container .well').prepend('<ul class="category_tree"><li><a href="#?category=0" data-category-id="0" onclick="mw.url.windowHashParam(\'category\', 0);return false;"><?php _e("All"); ?></a></li></ul>');
        mw.$('#modules_categories_tree_<?php print $params['id']; ?> li a').each(function () {
            var el = this;
            var id = el.attributes['data-category-id'].nodeValue;
            el.href = '#?category=' + id;
            el.className += ' cat-' + id;
            el.setAttribute('onclick', 'mw.url.windowHashParam("category",' + id + ');return false;');
        });

        if (mw.url.getHashParams(window.location.hash).installed === '0') {
            mw.$('.installed_switcher').addClass('mw-switcher-off');

        } else {
            mw.$('.installed_switcher').removeClass('mw-switcher-off');

        }

        var h = mw.hash();

        if (h === '' || h === '#' || h === '#?') {
            _modulesSort();
        } else {
            var hash = mw.url.getHashParams(h);
            try {
                mwd.querySelector(".modules-index-bar input[value='" + hash.ui + "']").checked = true;
            } catch (e) {
            }
        }

        mw.$("#modules-sort-types").on('change', function () {
            var val = $(this).getDropdownValue();
            mw.url.windowHashParam('ui', val);
        });
    });

    function mw_reload_all_modules() {
        mw.notification.success('Reloading...', 3000)
        mw.tools.loading(true);

        mw.$('#modules_admin_<?php print $params['id']; ?>').attr('reload_modules', 1);
        mw.$('#modules_admin_<?php print $params['id']; ?>').attr('cleanup_db', 1);
        $(".reload-module-btn").addClass('reloading')
        mw.load_module('admin/modules/manage', '#modules_admin_<?php print $params['id']; ?>', function () {
            mw.notification.success('Modules have been reloaded', 5000)
            mw.$('#modules_admin_<?php print $params['id']; ?>').removeAttr('cleanup_db');
            $(".reload-module-btn").removeClass('reloading');
            mw.tools.loading(false);
        });
    }

    _modulesSort = function () {
        var hash = mw.url.getHashParams(window.location.hash);

        //hash.ui === undefined ? mw.url.windowHashParam('ui', 'admin') : '' ;
        // hash.category === undefined ? mw.url.windowHashParam('category', '0') : '' ;

        var attrs = mw.url.getHashParams(window.location.hash);
        var holder = mw.$('#modules_admin_<?php print $params['id']; ?>');

        var arr = ['data-show-ui', 'data-search-keyword', 'data-category', 'data-installed', 'install_new'], i = 0, l = arr.length;

        var sync = ['ui', 'search', 'category', 'installed', 'install_new'];

        for (; i < l; i++) {
            holder.removeAttr(arr[i]);
        }

        if (hash.ui === undefined) {
            holder.attr('data-show-ui', 'admin');
        }


        for (var x in attrs) {
            if (x === 'category' && (attrs[x] === '0' || attrs[x] === undefined)) continue;
            holder.attr(arr[sync.indexOf(x)], attrs[x]);
        }
        mw.load_module('admin/modules/manage', '#modules_admin_<?php print $params['id']; ?>', function () {
            $('#module_keyword').removeClass('loading');

            var el = $("#modules_admin_<?php print $params['id']; ?> .mw-modules-admin");
            // $( "#modules_admin_<?php print $params['id']; ?> .mw-modules-admin" ).sortable('destroy');
            el.sortable({
                handle: ".mw_admin_modules_sortable_handle",
                items: "li",
                axis: 'y',
                update: function () {
                    var serial = el.sortable('serialize');
                    $.ajax({
                        url: mw.settings.api_url + 'module/reorder_modules',
                        type: "post",
                        data: serial
                    });
                }
            });
        });
    }

    $(document).ready(function () {
        mw.on.hashParam('ui', _modulesSort);

        mw.on.hashParam('search', function () {
            _modulesSort();
            var field = mwd.getElementById('module_keyword');

            if (!field.focused) {
                field.value = this;
            }
        });

        mw.on.hashParam('install_new', function () {
            _modulesSort();
        });

        mw.on.hashParam('category', function () {
            _modulesSort();
            mw.$("#mw_index_modules a.active").removeClass('active');
            mw.$("#mw_index_modules .cat-" + this).addClass('active');
        });
        mw.on.hashParam('installed', function () {
            _modulesSort();
        });
    });

    $(document).ready(function () {
        _modulesSort();
        mw.on.hashParam('market', function () {
            if (this != false) {
                mw.$('html').addClass('market-init');
                mw.load_module('admin/modules/market', '#modules_market_<?php print $params['id']; ?>');
                /*        $("#modules_admin_<?php print $params['id']; ?>").hide();
                 $("#modules_market_<?php print $params['id']; ?>").show();
                 $(".modules-index-bar").hide();*/
            } else {
                mw.$('html').removeClass('market-init');
            }
        })


    });


</script>
<style>
    html.market-init, .market-init body {
        overflow: hidden;
    }

    @media (max-width: 768px) {
        #modules-list-title-and-search .mw-ui-col,
        #modules-list-title-and-search + .mw-ui-inline-list {
            padding-bottom: 12px;
        }

    }

    html.market-init .tree-column {
        -webkit-transform: translateX(-210px);
        -moz-transform: translateX(-210px);
        -ms-transform: translateX(-210px);
        -o-transform: translateX(-210px);
        transform: translateX(-210px);
    }

    html.market-init #modules_admin_<?php print $params['id'];
?>, html.market-init .modules-index-bar {
        -webkit-transform: scale(0);
        -moz-transform: scale(0);
        -ms-transform: scale(0);
        -o-transform: scale(0);
        transform: scale(0);
        opacity: 0;
    }

    html.market-init #modules_admin_<?php print $params['id'];
?>, html.market-init .modules-index-bar, html.market-init .tree-column {
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        -ms-transition: all 0.3s;
        transition: all 0.3s;
    }

    #mw-update-frame {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        left: 100px;
        background: url(<?php print mw_includes_url();
?>img/load.gif) no-repeat center;
    }
</style>


<div id="edit-content-row" class="mw-ui-row">
    <div class="mw-ui-col tree-column" style="display:none">
        <div class="tree-column-holder">
            <div class="fixed-side-column scroll-height-exception-master">
                <div class="admin-side-box scroll-height-exception">
                    <h2 class="mw-side-main-title"><span class="mw-icon-module"></span><span>
            <?php _e("Extensions"); ?>
            </span></h2>
                </div>
                <div class="mw-admin-side-nav" id="modules_categories_tree_<?php print $params['id']; ?>">
                    <div class="fixed-side-column-container">
                        <div class="mw-ui-sidenav"><a href="javascript:;" onclick="mw_show_my_modules()" class="active">
                                <?php _e("My modules"); ?>
                            </a>
                            <?php if (mw()->ui->disable_marketplace != true): ?>
                                <a href="#market=mw">
                                    <?php _e("Market"); ?>
                                </a>
                            <?php endif; ?>
                            <?php $modules_sidebar_menu = mw()->modules->ui('admin.modules.sidebar'); ?>
                            <?php if (!empty($modules_sidebar_menu)): ?>
                                <?php foreach ($modules_sidebar_menu as $type => $item): ?>
                                    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                    <?php $link = (isset($item['link'])) ? ($item['link']) : false; ?>
                                    <?php $market_module = (isset($item['module'])) ? ($item['module']) : false; ?>
                                    <?php if ($market_module != false): ?>
                                        <a href="<?php print admin_url('view:modules/load_module:') . $market_module; ?>"
                                           class="<?php print $class; ?>"><?php print $title; ?></a>
                                    <?php else: ?>
                                        <a href="<?php print $link; ?>"
                                           class="<?php print $class; ?>"><?php print $title; ?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="text-center scroll-height-exception">
                    <div class="mw-ui-box-content"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="section-header">
        <h2 class="pull-left"><span class="mai-modules"></span> <?php _e("My Modules"); ?></span></h2>
        <div class="pull-right">
            <div class="pull-right">
                <div class="top-search">
                    <input value="" name="module_keyword" placeholder='<?php _e("Search for modules"); ?>' type="text" autocomplete="off" onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false">
                    <span class="top-form-submit" onclick="mw.url.windowHashParam('search',$(this).prev().val())"><span class="mw-icon-search"></span></span>
                </div>
            </div>







            <div class="mw-dropdown pull-left nested-dropdown" style="display:none;"><span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium mw-dropdown-val mw-dropdown-button"><?php _e("Categories"); ?></span>
                <div class="mw-dropdown-content">
                    <module type="categories" data-for="modules" id="modules_admin_categories_<?php print $params['id']; ?>"/>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-side-content">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="#installed=1" class="mw-ui-btn" onclick="mw.url.windowHashParam('installed', 1);return false;" ><?php _e("Installed"); ?></a>
            <a href="#installed=0" class="mw-ui-btn" onclick="mw.url.windowHashParam('installed', 0);return false;" ><?php _e("Uninstalled"); ?></a>
            <div class="mw-dropdown mw-dropdown-default  pull-left" id="modules-sort-types">
                <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">
                    <?php _e("Module types"); ?>
                </span>
                <div class="mw-dropdown-content" style="display: none;">
                    <ul>
                        <li value="live_edit"><?php _e("Live edit modules"); ?></li>
                        <li value="admin"><?php _e("Admin modules"); ?></li>
                        <li value="advanced"><?php _e("Advanced"); ?></li>
                    </ul>
                </div>
            </div>
            <span onclick="mw_reload_all_modules()" class="mw-ui-btn mw-ui-btn-icon tip reload-module-btn" data-tip="<?php _e("Reload modules"); ?>"><span class="mw-icon-reload"></span></span>

        </div>
        <div class="mw-ui-box mw-ui-box-content">
            <div id="modules_admin_<?php print $params['id']; ?>"></div>
            <div id="modules_market_<?php print $params['id']; ?>"></div>
        </div>

    </div>
</div>
