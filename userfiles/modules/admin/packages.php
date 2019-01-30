<?php only_admin_access(); ?>

<?php

$search_packages = mw()->update->composer_search_packages('cache=true');

d($search_packages);
?>
<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2 class="mw-side-main-title relative"><span class="mw-icon-updates"></span>
            <?php _e("Updates"); ?>
        </h2>
    </div>
</div>

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#nav .mw-ui-navigation a',
            tabs: '#nav .tab'
        });
    });
</script>

<div class="admin-side-content">
    <div id="nav" class="mw-ui-row">
        <div class="mw-ui-col" style="width: 20%;">
            <div class="mw-ui-col-container">
                <ul class="mw-ui-box mw-ui-navigation" id="nav">
                    <li><a href="javascript:;" class="active">Themes</a></li>
                    <li><a href="javascript:;">Plugins</a></li>
                    <li><a href="javascript:;">Core</a></li>
                </ul>
            </div>
        </div>

        <div class="mw-ui-col" style="width: 80%;">
            <div class="mw-ui-col-container">
                <div class="mw-ui-box" style="width: 100%;">
                    <div class="mw-ui-box-content tab">


                        <?php if ($search_packages) { ?>


                            <?php foreach ($search_packages as $key => $item): ?>

                                <div class="mw-ui-col" style="width: 33.3%;">
                                    <div class="mw-ui-col-container">
                                        <div class="mw-ui-box">
                                            <div class="mw-ui-box-header">
                                                <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>
                                            </div>
                                            <div class="mw-ui-box-content">

                                                <?php print $item['description'] ?>

                                                <ul>
                                                   <li>release_date: <?php print $item['latest_version']['release_date'] ?></li>
                                                   <li>version: <?php print $item['latest_version']['version'] ?></li>
                                                   <li>author: Petko</li>
                                                   <li>website: example.com</li>
                                                </ul>

                                                <a class="mw-ui-btn">Read more</a>
                                                <a class="mw-ui-btn">Install</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>


                        <?php } ?>

                        <div class="mw-ui-col" style="width: 33.3%;">
                            <div class="mw-ui-col-container">
                                <div class="mw-ui-box">
                                    <div class="mw-ui-box-header">
                                        <span class="mw-icon-gear"></span><span> Module title</span>
                                    </div>
                                    <div class="mw-ui-box-content">

                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et
                                        sapien varius gravida. Fusce
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mw-ui-col" style="width: 33.3%;">
                            <div class="mw-ui-col-container">
                                <div class="mw-ui-box">
                                    <div class="mw-ui-box-header">
                                        <span class="mw-icon-gear"></span><span> Module title</span>
                                    </div>
                                    <div class="mw-ui-box-content">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et
                                        sapien varius gravida. Fusce
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mw-ui-col" style="width: 33.3%;">
                            <div class="mw-ui-col-container">
                                <div class="mw-ui-box">
                                    <div class="mw-ui-box-header">
                                        <span class="mw-icon-gear"></span><span> Module title</span>
                                    </div>
                                    <div class="mw-ui-box-content">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et
                                        sapien varius gravida. Fusce
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-box-content tab" style="display: none">About - Lorem Ipsum</div>
                    <div class="mw-ui-box-content tab" style="display: none">About - Lorem Ipsum 2</div>
                </div>
            </div>
        </div>
    </div>
</div>
