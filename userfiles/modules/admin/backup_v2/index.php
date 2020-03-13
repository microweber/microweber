<?php only_admin_access(); ?>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup_v2/css/style.css' ?>" type="text/css" />
<script  type="text/javascript">
	var importContentFromFileText = '<?php _e("Importing content from file"); ?>';
	var userfilesUrl = '<?php echo userfiles_url() ?>';
	var moduleImagesUrl = '<?php echo modules_url() . '/admin/backup_v2/images/' ?>';
	mw.require("<?php print $config['url_to_module']; ?>js/upload-file.js");
    mw.require("<?php print $config['url_to_module']; ?>js/backup-import.js?v=10");
    mw.require("<?php print $config['url_to_module']; ?>js/backup-export.js?v=10");
    mw.lib.require('mw_icons_mind');
</script>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<style>
    #quick-parent-selector-tree{
        max-height: 40vh;
        overflow: auto;
    }
    .export-stepper-1-select{
        margin: 26px 0 12px;
    }

    .export-stepper .mw-stepper-item{
        padding: 10px;
    }
    .export-stepper-1-select .mw-construct-itd{
        width: 100%;
        padding-bottom: 12px;
    }
    .step-actions .mw-ui-btn{
        min-width: 180px;
    }
    .step-actions{
        display: flex;
        padding: 12px 22px 0;
        justify-content: space-between;
        align-items: center;
    }
    .step-actions-2, .step-actions-3{
        justify-content: flex-end;
    }
    .step-header-actions{
        padding-bottom: 25px;
        justify-content: flex-end;
        display: flex;
    }
    .export-step-2-items-head .mw-construct-itd,
    .export-step-3-items-head .mw-construct-itd{
        display: flex;
        padding-bottom: 10px;
    }
    .export-step-2-items-head,
    .export-step-3-items-head{
        padding-bottom: 15px;

    }

    .export-step-2-items-head .mw-ui-check + .mw-ui-check,
    .export-step-3-items-head .mw-ui-check + .mw-ui-check{
        margin-left: 10px;
    }

    .js-export-log-content{
        text-align: left;
        color: #fff;
        background: #575757;
        padding-top: 15px;
        padding-left: 15px;
        padding-bottom: 15px;
        font-family: monospace;
        border-radius: 4px;
        margin-top:15px;
        margin-bottom:15px;
    }

    .js-export-log:empty{
        display: none;
    }
    .export-stepper-1 .export-stepper-content,
    .export-stepper-4 .export-stepper-content{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    @media (min-height: 700px) {
        .export-stepper-1 .export-stepper-content,
        .export-stepper-4 .export-stepper-content{
            min-height: 580px;
        }
    }


</style>

<div id="mw-admin-content" class="admin-side-content">

    <div class="mw_edit_page_default" id="mw_edit_page_left">

        <div class="mw-ui-btn-nav pull-left">
            <a href="javascript:;" onclick="mw.backup_export.choice('#export-template')" class="mw-ui-btn mw-ui-btn-notification">
                <i class="mw-icon-download"></i>&nbsp; <span><?php _e("Create backup & export content"); ?></span>
            </a>
        </div>

        <template id="export-template">

            <div class="export-stepper">
                <div class="export-stepper-1">
                    <div class="export-stepper-content">

                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-icon">
                                <span class="mw-micon-Data-Download"></span>
                            </div>
                            <div class="mw-construct-itd-content">
                                <h3>Create full backup of your site</h3>
                                <p>Use the button to export full backup of your website whit all data.</p>
                            </div>
                        </div>

                        <div class="mw-ui-box2 export-stepper-1-select">
                            <div class="mw-construct-itd">
                                <div class="mw-construct-itd-content">
                                    <h5>Select file format for export</h5>
                                    <p>Choose the file format you want to export your backup. </p>
                                </div>
                            </div>
                            <div class="mw-field w100">
                                <select class="js-export-format" name="export_format">
                                    <option value="json">Json</option>
                                    <option value="csv">CSV</option>
                                    <option value="xml">XML</option>
                                    <option value="xlsx">Excel</option>
                                </select>
                            </div>
                        </div>


                    <div class="step-actions step-1-actions">
                        <span
                            class="mw-ui-btn mw-ui-btn-info pull-left"
                            onclick="mw.backup_export.export_fullbackup_start()">
                            Create Full Backup
                        </span>
                        <span
                            class="mw-ui-link pull-right"
                            data-mwstepper="next">
                            Continue with advanced backup settings
                        </span>
                    </div>

                    </div>
                </div>
                <div class="export-stepper-2">
                    <div class="export-stepper-content">

                        <div class="step-header-actions step-header-actions-2">
                            <a
                                class="mw-ui-link mw-btn-prepend"
                                data-mwstepper="prev">
                                <span class="mw-icon-arrow-left-c mw-icon-round mw-icon-info"></span>back
                            </a>
                        </div>

                        <div class="export-step-2-items-head">
                            <div class="mw-construct-itd">
                                <div class="mw-construct-itd-content">
                                    <h5>Select types of data you want to export.</h5>
                                </div>
                            </div>
                            <label class="mw-ui-check mw-ui-check-lg">
                                <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectAll();">
                                <span></span><span>Select all</span>
                            </label>

                            <label class="mw-ui-check mw-ui-check-lg">
                                <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectNone();">
                                <span></span><span>Unselect all</span>
                            </label>
                        </div>

                        <div id="backup-select-options-to-export"></div>

                        <div class="mw-ui-box2 export-stepper-1-select">
                            <div class="mw-construct-itd">
                                <div class="mw-construct-itd-content">
                                    <h5>Select file format for export</h5>
                                    <p>Choose the file format you want to export your backup. </p>
                                </div>
                            </div>
                            <div class="mw-field w100">
                                <select class="js-export-format" name="export_format">
                                    <option value="json">Json</option>
                                    <option value="csv">CSV</option>
                                    <option value="xml">XML</option>
                                    <option value="xlsx">Excel</option>
                                </select>
                            </div>
                        </div>



                        <div class="step-actions step-actions-2">
                            <a class="mw-ui-btn mw-ui-btn-info" data-mwstepper="next">Next</a>
                        </div>

                    </div>
                </div>
                <div class="export-stepper-3">

                    <div class="export-stepper-content">
                        <div class="step-header-actions step-header-actions-2">
                            <a
                                class="mw-ui-link mw-btn-prepend"
                                data-mwstepper="prev">
                                <span class="mw-icon-arrow-left-c mw-icon-round mw-icon-info"></span>back
                            </a>
                        </div>

                        <div class="export-step-3-items-head">
                            <div class="mw-construct-itd">
                                <div class="mw-construct-itd-content">
                                    <h5>Select which pages or categories of your website to export</h5>
                                    <p>Select pages, categories, posts etc. you want to export.
                                    </p>
                                </div>
                            </div>
                            <label class="mw-ui-check mw-ui-check-lg">
                                <input type="radio" name="all" onchange="mw.backup_export.select_all();">
                                <span></span><span>Select all</span>
                            </label>

                            <label class="mw-ui-check mw-ui-check-lg">
                                <input type="radio" name="all" onchange="mw.backup_export.unselect_all();">
                                <span></span><span>Unselect all</span>
                            </label>
                        </div>


                        <div class="mw-ui-box2">
                            <div id="quick-parent-selector-tree"></div>
                        </div>


                        <div class="step-actions step-actions-3">
                            <a class="mw-ui-btn mw-ui-btn-info" onclick="mw.backup_export.export_start()">
                                Export selected data
                            </a>
                        </div>

                    </div>
                </div>
                <div class="export-stepper-4">
                    <h3 class="export-step-4-action">Exporting your content</h3>
                    <div class="export-stepper-content">
                        <div class="js-export-log"></div>
                    </div>
                </div>

            </div>






        </template>

        <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info pull-right">
            <i class="mw-icon-upload"></i>&nbsp;
            <span><?php _e("Upload file"); ?></span>
        </span>

		<!-- Upload file notification loader -->
        <div id="mw_uploader_loading" class="mw-ui-btn mw-ui-btn-notification" style="display:none;"><?php _e("Uploading files"); ?></div>

        <div class="vSpace">&nbsp;</div>
    </div>

    <div class="mw_edit_page_right" style="padding: 10px 0;">
        <module type="admin/backup_v2/manage"/>
    </div>

</div>

