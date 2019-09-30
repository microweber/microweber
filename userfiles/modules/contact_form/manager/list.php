<?php
if (is_admin() == false) {
	return array(
		'error' => 'Not logged in as admin'
	);
}
?>
<script type="text/javascript">
    mw.require('<?php print $config['url_to_module']; ?>forms_data_manager.js');
</script>

<script type="text/javascript">
    toggle_show_less = function (el) {
        var el = $(el);
        el.prev().toggleClass('semi_hidden');
        var html = el.html();
        el.html(el.dataset("later"));
        el.dataset("later", html);
    }

    $(document).ready(function () {
        $('.js-toggle-full').on('click', function () {
            $(this).parent().toggleClass('more');
            $(this).toggleClass('showed');
        });
    });
</script>
<style>
    .js-limited {
        min-width: 250px;
        max-width: 500px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .js-limited.more {
        white-space: inherit;
        word-wrap: break-word;
    }

    .js-toggle-full {
        margin-top: 10px;
    }

    .js-toggle-full span:last-child {
        display: none;
    }

    .js-toggle-full.showed span:first-child {
        display: none;
    }

    .js-toggle-full.showed span:last-child {
        display: block;
    }

    .entry-col {
        min-width: 150px;
    }
</style>

<?php

$filterData = array();
if (isset($params['load_list'])) {
    if ($params['load_list'] == 'default') {
        $params['load_list'] = '0';
    }
    $filterData['list_id'] = $params['load_list'];
}

if ($filterData['list_id'] == 'all_lists') {
	$listData = array(
		'title'=> 'All lists'
	);
} else {
	$listData = get_form_lists("id=" . $filterData['list_id'] . "&limit=1");
	$listData = $listData[0];
}

$limit = 30;
if (isset($params['keyword'])) {
	$filterData['keyword'] = $params['keyword'];
}
if (isset($params['for_module'])) {
    //$data['module_name'] = $params['for_module'];
}
if (isset($_GET['per_page']) and $_GET['per_page']) {
    $limit = intval($_GET['per_page']);
}

//if (isset($_GET['per_page'])) {
//	$option = array();
//	$option['option_value'] = intval($_GET['per_page']);
//	$option['option_key'] = 'per_page';
//	$option['option_group'] = $params['for_module'];
//	save_option($option);
//}
//
//$data['limit'] = get_option('per_page', $params['for_module']);
$data_count_all = $filterData;
$data_count_all['limit'] = null;
$data_count_all['count'] = true;
$total_count = get_form_entires($data_count_all);


$filterData['limit'] = $limit;
$custom_fields = array();

if ((url_param('current_page') != false)) {
	$filterData['current_page'] = url_param('current_page');
}

$data_paging = $filterData;
$data_paging['page_count'] = 1;


$data_paging = get_form_entires($data_paging);

//
//if ($data['limit'] == false) {
//	$data['limit'] = 10;
//}

//$limit_per_page = $data['limit'];
$limit_per_page = 50;
$custom_fields = array();


if ($filterData['list_id'] == 'all_lists') {
	$data = get_form_entires(array('limit'=>$filterData['limit']));
} else {
	$data = get_form_entires($filterData);
}

if (is_array($data)) {
    foreach ($data as $item) {

        if (isset($item['custom_fields'])) {
            foreach ($item['custom_fields'] as $k => $value) {

                if ($k != 'for'
                    and $k != 'for_id'
                    and $k != 'module_name'
                ) {
                    $custom_fields[$k] = $value;
                }


            }
        } else if (isset($item['form_values'])) {
            // $custom_fields =  json_decode($item['form_values']);

        }
    }
}
?>

<?php
$view = new \Microweber\View(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'admin_messages_list.php');
$view->assign('last_messages', $data);
echo $view->__toString();
?>

<div class="mw-ui-row m-t-20">

    <?php
    $load_list = 'default';
    if ((url_param('load_list') != false)) {
        $load_list = url_param('load_list');
    }
    ?>
    
	<?php 
	$hideEditButton = false;
	if ($load_list == 'default') {
		$hideEditButton = true;
	} else if ($load_list == 'all_lists') {
		$hideEditButton = true;
	}
	?>
    
    <div class="mw-ui-col">
        <?php if (!$hideEditButton): ?>
            <span class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-important mw-ui-delete"
                  onclick="mw.forms_data_manager.delete_list('<?php print addslashes($load_list); ?>');">
                    <?php _e("Delete"); ?>&nbsp;<b><?php echo $listData['title']; ?></b>&nbsp;<?php _e("list"); ?>
                </span>
        <?php endif; ?>
    </div>

    <?php if (is_array($data) && !empty($data)) : ?>
        <div class="mw-ui-col text-center" style="width: 70%">
            <div class="mw-paging mw-paging- mw-paging- inline-block">
                <?php print paging("num=$data_paging"); ?>
            </div>
            <?php if (isset($params['export_to_excel'])) : ?>
            <?php endif; ?>
            <?php if (isset($params['export_to_excel'])) : ?>
            <?php endif; ?>
        </div>

        <div class="mw-ui-col" style="width: 240px;">
            <div class="mw-field" style="width:100%;" data-before="<?php _e('Show items per page'); ?>">
                <form method="get">
                    <select name="per_page" onchange="this.form.submit()">
                        <option value="">Select</option>
                        <option value="10" <?php if ($limit == 30): ?>  selected  <?php endif; ?>>30</option>
                        <option value="50" <?php if ($limit == 50): ?>  selected  <?php endif; ?>>50</option>
                        <option value="100" <?php if ($limit == 100): ?>  selected  <?php endif; ?>>100</option>
                        <option value="200" <?php if ($limit == 200): ?>  selected  <?php endif; ?>>200</option>
                    </select>


                </form>
            </div>
            <br/><br/>
            <div class="text-right">
                <strong><?php print _e('Total'); ?>:</strong>
                <span><?php echo ($total_count); ?> messages in this list</span>
            </div>
        </div>
    <?php endif; ?>
</div>
