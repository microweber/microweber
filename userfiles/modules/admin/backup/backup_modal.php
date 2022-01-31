<template id="backup-modal">

    <div class="mw-backup mw-backup-type">

        <div class="mw-backup-options">
            <br/>
            <h2 style="font-weight: bold">Want do you want to backup?</h2>
            <br/>

            <label class="mw-ui-check mw-backup-option">
                <div class="option-radio">
                    <input type="radio" name="backup_by_type" checked="checked" value="content"/>
                    <span></span>
                </div>
                <h3>Content backup</h3>
                <p>Create backup of your sites without <b>sensitive</b> information
                    <br/>
                    <small class="text-muted">
                        This will create a zip with live-edit css, media, post categories & pages.
                    </small>
                </p>
            </label>


            <label class="mw-ui-check mw-backup-option">
                <div class="option-radio">
                    <input type="radio" name="backup_by_type" value="custom"/>
                    <span></span>
                </div>
                <h3>Custom backup</h3>
                <p>Create backup with custom selected tables, media, modules & templates
                    <br/>
                    <small class="text-muted">
                        You can select one by one information included in zip.
                    </small>
                </p>
            </label>


            <label class="mw-ui-check mw-backup-option active">
                <div class="option-radio">
                    <input type="radio" name="backup_by_type" value="full"/>
                    <span></span>
                </div>
                <h3>Full backup</h3>
                <p>
                    Create full backup of all database tables, system settings and options
                    <br/>
                    <small class="text-muted">
                        Include <b>sensitive</b> information like users, <b>passwords</b>, <b>api keys</b>, settings.
                    </small>
                </p>

            </label>


        </div>

        <div style="margin-bottom:20px;" class="js-backup-import-installation-language-wrapper"></div>
        <div class="backup-import-modal-log-progress"></div>

        <div class="mw-backup-buttons">
            <a class="btn btn-link button-cancel" onClick="mw.backup.close_modal();">Close</a>
            <button class="btn btn-primary btn-rounded button-start" onclick="mw.backup.next()" type="submit">Next
            </button>
        </div>

    </div>

    <div class="mw-backup-custom">
        
    </div>

</template>
