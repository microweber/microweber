<?php only_admin_access(); ?>
    <div class="mw-ui-box mw-ui-settings-box mw-ui-box-content ">
        <h4>Import CSV</h4>


        <form class="form-horizontal" id="events_import_file_form" method="POST"
              action="<?php print api_url('calendar_import_by_csv'); ?>"
              enctype="multipart/form-data" >
            <label for="csv_file" class="col-md-4 control-label">CSV file to import</label>
            <input id="csv_file" type="file" class="form-control" name="csv_file" required>

            <button type="submit" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline pull-right"><span>Import </span>
            </button>

        </form>


    </div>


    <div class="mw-ui-box mw-ui-settings-box mw-ui-box-content ">
    <h4>Export CSV</h4>
        <a href="<?php print api_url('calendar_export_to_csv_file') ?>" target="_blank" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline pull-right">Export</a>

    </div>

    <script type='text/javascript'>
        $(document).ready(function () {
            //form Submit

            $("#events_import_file_form").submit(function (evt) {
                evt.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '<?php print api_url('calendar_import_by_csv'); ?>',
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function (response) {
                        mw.notification.msg(response,10000);
                        if (typeof(reload_calendar_after_save) != 'undefined') {
                            reload_calendar_after_save();
                        }
                    }
                });
                return false;
            });
        });
    </script>
<?php


