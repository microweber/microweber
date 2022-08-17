<?php
$type = get_option('type', $params['id']);
$link_text = get_option('link_text', $params['id']);
if(!$link_text) $link_text = 'Click to open modal';
$source = get_option('source', $params['id']);
if(!$source) $source = 'edited_text';
$page_id = get_option('page_id', $params['id']);

$time_delay = get_option('time_delay', $params['id']);
if (!$time_delay) {
    $time_delay = 3000;
}


if ($type == 'on_time'):
    $session_get = false;
    $modal_id = 'popup-' . $params['id'];
    if (isset($_COOKIE['popup-' . $params['id']])) {
        $session_get = $_COOKIE['popup-' . $params['id']];
    }

    if ($session_get != 'yes') {
        $showPopUp = true;
    } else {
        $showPopUp = false;
    }

    if ($showPopUp): ?>
        <script>
            $(window).on('load', function () {
                <?php if (in_live_edit()): ?>
                $('#popup-<?php print $params['id']; ?>').modal({backdrop: false});
                <?php else: ?>
                setTimeout(function () {
					<?php if($source == 'existing_page' && !empty($page_id)): ?>
					popup_get_content();
					<?php endif;?>
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

<?php
elseif ($type == 'on_leave_window'):
	$session_get = false;
	$modal_id = 'popup-' . $params['id'];
	if (isset($_COOKIE['popup-' . $params['id']])) {
		$session_get = $_COOKIE['popup-' . $params['id']];
	}

	if ($session_get != 'yes') {
		$showPopUp = true;
	} else {
		$showPopUp = false;
	}

	if ($showPopUp): ?>
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
			$('#popup-<?php print $params['id'];?>').hide();
			setTimeout(function () {
				$('body').append('<div class="on_leave_window"></div>');
				$('.on_leave_window').mouseenter(function () {
					mw.cookie.set('<?php print $modal_id; ?>', 'yes');
					<?php if($source == 'existing_page' && !empty($page_id)): ?>
					popup_get_content();
					<?php endif;?>
					$('#popup-<?php print $params['id']; ?>').modal('show');
					$('.on_leave_window').remove();
				});
			}, 5000);
		});
	</script>
	<?php endif;

elseif ($type == 'on_click_host'):
?>
	<script type="text/javascript">
		$(document).ready(function () {
			var prev_a = $('#<?php print $params['id'];?>').prevAll().find('a:last');
			if(typeof prev_a !== "undefined"){
			    prev_a.prop('href', '#popup-<?php print $params['id']; ?>');
			    <?php if($source == 'existing_page' && !empty($page_id)): ?>
			    prev_a.click(function(){
			        popup_get_content();
			        $('#popup-<?php print $params['id']; ?>').modal('show');
			    });
			    <?php endif;?>
			} else {
				console.log('Popup module on_click_host error: previous a-tag not found');
			}
		});
	</script>
<?php
endif;


if($source == 'existing_page' && !empty($page_id)): ?>
<script type="text/javascript">
	function popup_get_content() {
		$.ajax({
			url: '<?php print api_url('popup_module_get_content_by_id') . '?page_id=' . $page_id; ?>',
			type: 'GET',
			success: function (data) {
				$('#modal-title').html(data.title);
				$('#modal-content').html(data.content);
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				console.log(XMLHttpRequest);
				console.log(textStatus);
				console.log(errorThrown);
			}
		});
	}
</script>
<?php endif;


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

<?php if ($type != 'on_click' && in_live_edit()) print lnotif('Pop-Up Settings'); ?>

<?php if (in_live_edit()): ?>
    <style>
        #popup-<?php print $params['id']; ?> {
            z-index: 1102 !important;
            top: 10%;
        }
    </style>
    <?php if ($type != 'on_click'): ?>
    <a class="btn btn-default pull-right" data-bs-toggle="modal" href="#popup-<?php print $params['id']; ?>"
       data-backdrop="false" style="margin-top: -30px;"><?php _e("Open Pop-Up"); ?></a>
    <?php endif; ?>
<?php endif; ?>
