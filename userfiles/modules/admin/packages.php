<?php only_admin_access(); ?>
<script>

    mw.require('admin_package_manager.js');


</script>

<?php

$search_packages = mw()->update->composer_search_packages('cache=true');
//$search_packages = mw()->update->composer_search_packages();

//dd($search_packages);
?>


<div class="section-header">
    <h2 class="pull-left"><span class="mw-icon-updates"></span> <?php _e("Packages"); ?></h2>
    <div class="pull-right">
        <div class="top-search">
            <input value="" name="module_keyword" placeholder="Search" type="text" autocomplete="off" onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false">
            <span class="top-form-submit" onclick="mw.url.windowHashParam('search',$(this).prev().val())"><span class="mw-icon-search"></span></span>
        </div>
    </div>

    <div class="pull-right m-r-10" style="margin-top:1px;">
        <select class="mw-ui-field">
            <option selected="selected" value="-1">Select some</option>
            <option value="1">Mr</option>
            <option value="2">Mrs</option>
            <option value="3">Miss</option>
            <option value="4">Ms</option>
        </select>
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

<div class="admin-side-content" style="max-width: 90%">
    <div id="mw-packages-browser-nav-tabs-nav" class="mw-ui-row">
        <div class="mw-ui-col" style="width: 20%;">
            <div class="mw-ui-col-container">
                <ul class="mw-ui-box mw-ui-navigation" id="nav">
                    <li><a href="javascript:;" class="active">Themes</a></li>
                    <li><a href="javascript:;">Plugins</a></li>
                    <li><a href="javascript:;">Others</a></li>
                </ul>
            </div>
        </div>

        <div class="mw-ui-col" style="width: 80%;">
            <div class="mw-ui-col-container">

                <div id="mw-updates-queue"></div>


                <div class="mw-ui-box" style="width: 100%; padding: 12px 0;">
                    <div class="mw-ui-box-content tab">
                        <?php if ($search_packages) : ?>
                            <div class="mw-flex-row">
                                <?php foreach ($search_packages as $key => $item): ?>
                                    <?php if ($item['type'] == 'microweber-template'): ?>
                                        <div class="mw-flex-col-xs-12  m-b-20">

                                            <?php

                                            $view_file = __DIR__ . '/developer_tools/package_manager/partials/package_item.php';

                                            $view = new \Microweber\View($view_file);
                                            $view->assign('item', $item);

                                             print    $view->display();

                                            ?>




                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mw-ui-box-content tab" style="display: none">
                        <?php if ($search_packages) : ?>
                            <div class="mw-flex-row">
                                <?php foreach ($search_packages as $key => $item): ?>
                                    <?php if ($item['type'] == 'microweber-module'): ?>
                                        <div class="mw-flex-col-xs-12 m-b-20">


                                            <?php

                                            $view_file = __DIR__ . '/developer_tools/package_manager/partials/package_item.php';

                                            $view = new \Microweber\View($view_file);
                                            $view->assign('item', $item);

                                            print    $view->display();

                                            ?>

                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mw-ui-box-content tab" style="display: none">
                        <?php if ($search_packages) : ?>
                            <div class="mw-flex-row">
                                <?php foreach ($search_packages as $key => $item): ?>
                                    <?php if ($item['type'] != 'microweber-template' AND $item['type'] != 'microweber-module'): ?>
                                        <div class="mw-flex-col-xs-12 m-b-20">



                                            <?php

                                            $view_file = __DIR__ . '/developer_tools/package_manager/partials/package_item.php';

                                            $view = new \Microweber\View($view_file);
                                            $view->assign('item', $item);

                                            print    $view->display();

                                            ?>


                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
