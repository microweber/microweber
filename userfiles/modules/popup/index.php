<?php
$type = get_option('type', $params['id']);
$time_delay = get_option('time_delay', $params['id']);
if (!$time_delay) {
    $time_delay = 3000;
}
?>


<?php if ($type == 'on_time'): ?>
    <?php $session_get = false;
    $modal_id = 'popup-' . $params['id'];
    if (isset($_COOKIE['popup-' . $params['id']])) {
        $session_get = $_COOKIE['popup-' . $params['id']];
    }

    if ($session_get != 'yes') {
        $showPopUp = true;
    } else {
        $showPopUp = false;
    }
    ?>

    <?php if ($showPopUp): ?>
        <script>
            $(window).on('load', function () {

                <?php if (in_live_edit()): ?>
                $('#popup-<?php print $params['id']; ?>').modal({backdrop: false});
                <?php else: ?>
                setTimeout(function () {
                    $('#popup-<?php print $params['id']; ?>').modal('show');
                }, <?php print $time_delay; ?>);
                <?php endif; ?>

            });
        </script>
    <?php endif; ?>

    <script>
        $(document).ready(function () {
            $('#popup-<?php print $params['id']; ?>-accept').on('click', function () {
                mw.cookie.set('<?php print $modal_id; ?>', 'yes');
                $('#popup-<?php print $params['id']; ?>').modal('toggle');
            });
        });
    </script>
<?php elseif ($type == 'on_leave_window'): ?>
    <?php $session_get = false;
    $modal_id = 'popup-' . $params['id'];
    if (isset($_COOKIE['popup-' . $params['id']])) {
        $session_get = $_COOKIE['popup-' . $params['id']];
    }

    if ($session_get != 'yes') {
        $showPopUp = true;
    } else {
        $showPopUp = false;
    }
    ?>

    <?php if ($showPopUp): ?>
        <style>
            .on_leave_window {
                z-index: 99999;
                height: 2px;
                top: 0;
                width: 100%;
                position: fixed;
                /*border: 1px solid red;*/
            }
        </style>
        <script>
            $(document).ready(function () {
                setTimeout(function () {
                    $('body').append('<div class="on_leave_window"></div>');
                    $('.on_leave_window').mouseenter(function () {
                        mw.cookie.set('<?php print $modal_id; ?>', 'yes');
                        $('#popup-<?php print $params['id']; ?>').modal('show');
                        $('.on_leave_window').remove();
                    });
                }, 5000);
            });
        </script>
    <?php endif; ?>
<?php endif; ?>


<?php
$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}
?>

<?php print lnotif('Pop-Up Settings'); ?>

<?php if (in_live_edit()): ?>
    <style>
        #popup-<?php print $params['id']; ?> {
            z-index: 900 !important;
            top: 10%;
        }
    </style>
    <a class="btn btn-default pull-right" data-toggle="modal" href="#popup-<?php print $params['id']; ?>"
       data-backdrop="false" style="margin-top: -30px;">Open Pop-Up</a>
<?php endif; ?>