<?php only_admin_access(); ?>

<?php

$search_packages = mw()->update->composer_search_packages('cache=true');

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
</div>


<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#nav .mw-ui-navigation a',
            tabs: '#nav .tab'
        });
    });
</script>

<div class="admin-side-content" style="max-width: 90%">
    <div id="nav" class="mw-ui-row">
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
                <div class="mw-ui-box" style="width: 100%; padding: 12px 0;">
                    <div class="mw-ui-box-content tab">
                        <?php if ($search_packages) : ?>
                            <?php foreach ($search_packages as $key => $item): ?>
                                <?php if ($item['type'] == 'microweber-template'): ?>
                                    <div class="mw-ui-col" style="width: 33%;">
                                        <div class="mw-ui-col-container">
                                            <div class="mw-ui-box" style="min-height: 300px;">
                                                <div class="mw-ui-box-header">
                                                    <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>
                                                </div>
                                                <div class="mw-ui-box-content">

                                                    <p class="m-b-20"><?php print $item['description'] ?></p>

                                                    <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
                                                        <tbody>
                                                        <tr>
                                                            <td>Release date</td>
                                                            <td><?php print $item['latest_version']['release_date'] ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Version</td>
                                                            <td><?php print $item['latest_version']['version'] ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Author</td>
                                                            <td>Petko</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Website</td>
                                                            <td><a href="#" class="mw-blue">plumtex.com</a></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="text-center m-t-20">
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Read more</a>
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Install</a>
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important mw-ui-btn-outline"><i class="mw-icon-trash-a"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="mw-ui-box-content tab" style="display: none">
                        <?php if ($search_packages) : ?>
                            <?php foreach ($search_packages as $key => $item): ?>
                                <?php if ($item['type'] == 'microweber-module'): ?>
                                    <div class="mw-ui-col" style="width: 33%;">
                                        <div class="mw-ui-col-container">
                                            <div class="mw-ui-box" style="min-height: 300px;">
                                                <div class="mw-ui-box-header">
                                                    <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>
                                                </div>
                                                <div class="mw-ui-box-content">

                                                    <p class="m-b-20"><?php print $item['description'] ?></p>

                                                    <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
                                                        <tbody>
                                                        <tr>
                                                            <td>Release date</td>
                                                            <td><?php print $item['latest_version']['release_date'] ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Version</td>
                                                            <td><?php print $item['latest_version']['version'] ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Author</td>
                                                            <td>Petko</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Website</td>
                                                            <td><a href="#" class="mw-blue">plumtex.com</a></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="text-center m-t-20">
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Read more</a>
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Install</a>
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important mw-ui-btn-outline"><i class="mw-icon-trash-a"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="mw-ui-box-content tab" style="display: none">
                        <?php if ($search_packages) : ?>
                            <?php foreach ($search_packages as $key => $item): ?>
                                <?php if ($item['type'] != 'microweber-template' AND $item['type'] != 'microweber-module'): ?>
                                    <div class="mw-ui-col" style="width: 33%;">
                                        <div class="mw-ui-col-container">
                                            <div class="mw-ui-box" style="min-height: 300px;">
                                                <div class="mw-ui-box-header">
                                                    <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>
                                                </div>
                                                <div class="mw-ui-box-content">

                                                    <p class="m-b-20"><?php print $item['description'] ?></p>

                                                    <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
                                                        <tbody>
                                                        <tr>
                                                            <td>Release date</td>
                                                            <td><?php print $item['latest_version']['release_date'] ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Version</td>
                                                            <td><?php print $item['latest_version']['version'] ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Author</td>
                                                            <td>Petko</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Website</td>
                                                            <td><a href="#" class="mw-blue">plumtex.com</a></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="text-center m-t-20">
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Read more</a>
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Install</a>
                                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important mw-ui-btn-outline"><i class="mw-icon-trash-a"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
