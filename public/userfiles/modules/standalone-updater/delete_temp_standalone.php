<?php must_have_access(); ?>


<?php if (isset($_GET['delete_temp']) && $_GET['delete_temp'] == 1): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            retryDeleteTemp = 0;
            function delete_temp_standalone() {
                retryDeleteTemp = (retryDeleteTemp + 1);
                if (retryDeleteTemp > 4) {
                    return false;
                }
                $.ajax({
                    url: "<?php echo route('api.standalone-updater.delete-temp'); ?>",
                }).done(function () {

                    mw.notification.success("<?php _ejs("Update complete"); ?>.");
                }).fail(function (jqXHR, textStatus) {
                    delete_temp_standalone();
                });
            }
            delete_temp_standalone();
        });
    </script>
<?php endif; ?>
