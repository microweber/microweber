<template id="export-template">
    <div class="export-stepper">

        <div class="export-stepper-1">
            <div class="export-stepper-content">

                <div class="mw-construct-itd mb-4">
                    <div class="mw-construct-itd-icon">
                        <span class="mw-micon-Data-Download"></span>
                    </div>
                    <div class="mw-construct-itd-content">
                        <h3><?php _e("Create full backup of your site"); ?></h3>
                        <p><?php _e("Use the button to export full backup of your website whit all data"); ?>.</p>
                    </div>
                </div>


<!--
                                    <div class="step-header-actions step-header-actions-2">
                                        <a class="btn btn-link d-flex-inline align-items-center px-0" data-mwstepper="prev"><span class="mdi mdi-arrow-left bg-primary text-white p-2 rounded-circle mr-2"></span><?php _e("back"); ?></a>
                                    </div>

                                    <div class="export-step-2-items-head">
                                        <div class="mw-construct-itd">
                                            <div class="mw-construct-itd-content">
                                                <h5><?php _e("Select types of data you want to export"); ?></h5>
                                            </div>
                                        </div>
                                        <label class="mw-ui-check mw-ui-check-lg">
                                            <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectAll();">
                                            <span></span><span><?php _e("Select all"); ?></span>
                                        </label>

                                        <label class="mw-ui-check mw-ui-check-lg">
                                            <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectNone();">
                                            <span></span><span><?php _e("Unselect all"); ?></span>
                                        </label>
                                    </div>

                                    <div id="backup-select-options-to-export"></div>
                                    <br /> -->

                <div class="mw-ui-box2 export-stepper-1-select">
                    <div class="mw-construct-itd">
                        <div class="mw-construct-itd-content">
                            <h5><?php _e("Select file format for export"); ?></h5>
                            <p><?php _e("Choose the file format you want to export your backup"); ?>. </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <select class="js-export-format form-control" name="export_format">
                            <option value="json">Json</option>
                            <option value="csv">CSV</option>
                            <option value="xml">XML</option>
                            <option value="xlsx">Excel</option>
                        </select>
                    </div>
                </div>


                <div class="step-actions step-1-actions d-flex justify-content-between mt-3">



                    <!--
                    <span class="btn btn-link btn-outline-primary js-toggle-backup-select-items" onclick="mw.$('.js-toggle-backup-select-items').toggleClass('d-none')" >
                        <?php _e("Advanced settings"); ?>
                    </span>
                     -->


                    <button type="button" class="btn btn-outline-primary js-toggle-backup-select-items" data-mwstepper="next">
                        <i class="mdi mdi-database-export"></i> <?php _e("Custom"); ?>
                    </button>


                    <button type="button" class="btn btn-primary" onclick="mw.backup_export.export_fullbackup_start()">
                        <?php _e("Create Full Backup"); ?>
                    </button>
                </div>

            </div>
        </div>

        <div class="export-stepper-2">
            <div class="export-stepper-content">

                <div class="step-header-actions step-header-actions-2">
                    <a class="btn btn-link d-flex-inline align-items-center px-0" data-mwstepper="prev"><span class="mdi mdi-arrow-left bg-primary text-white p-2 rounded-circle mr-2"></span><?php _e("back"); ?></a>
                </div>


                <!---
                <div class="mw-ui-box2 export-stepper-2-select">
                    <div class="mw-construct-itd">
                        <div class="mw-construct-itd-content">
                            <h5><?php _e("Select file format for export"); ?></h5>
                            <p><?php _e("Choose the file format you want to export your backup"); ?>. </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <select class="js-export-format form-control" data-width="100%" name="export_format">
                            <option value="json">Json</option>
                            <option value="csv">CSV</option>
                            <option value="xml">XML</option>
                            <option value="xlsx">Excel</option>
                        </select>
                    </div>
                </div>

                <br />-->

                <div id="backup-select-options-to-export"></div>


                <div class="step-actions step-actions-2 text-end text-right mt-3">
                    <a href="javascript:;" class="btn btn-primary" data-mwstepper="next"><?php _e("Next"); ?></a>
                </div>

        </div>

        </div>

        <div class="export-stepper-3">
            <div class="export-stepper-content">
                <div class="export-step-3-items-head">
                    <div class="mw-construct-itd">
                        <div class="mw-construct-itd-content">
                            <h5><?php _e("Select which pages or categories of your website to export"); ?></h5>
                            <p><?php _e("Select pages, categories, posts etc. you want to export"); ?>.</p>
                        </div>
                    </div>
                    <label class="mw-ui-check mw-ui-check-lg">
                        <input type="radio" name="all" onchange="mw.backup_export.select_all();">
                        <span></span><span><?php _e("Select all"); ?></span>
                    </label>

                    <label class="mw-ui-check mw-ui-check-lg">
                        <input type="radio" name="all" onchange="mw.backup_export.unselect_all();">
                        <span></span><span><?php _e("Unselect all"); ?></span>
                    </label>
                </div>

                <div class="mw-ui-box2">
                    <div id="quick-parent-selector-tree"></div>
                </div>

                <br />
                <div class="step-actions step-actions-3">
                    <button type="button" class="mw-ui-btn mw-ui-btn-info" onclick="mw.backup_export.export_start()">Export selected data</button>
                </div>

            </div>
        </div>

        <div class="export-stepper-4">
            <h3 class="export-step-4-action"><?php _e("Exporting your content"); ?>Exporting your content</h3>
            <div class="export-stepper-content">
                <div class="js-export-log"></div>
            </div>
        </div>

    </div>
</template>
