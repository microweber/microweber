<?php if (is_admin() == false) {
    return array('error' => 'Not logged in as admin');
} ?>
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

$data = array();
if (isset($params['load_list'])) {
    if ($params['load_list'] == 'default') {
        $params['load_list'] = '0';
    }
    $data['list_id'] = $params['load_list'];
}


$listData = get_form_lists("id=".$data['list_id']."&limit=1");
$listData = $listData[0];


if (isset($params['keyword'])) {
    $data['keyword'] = $params['keyword'];
}
if (isset($params['for_module'])) {
    $data['module_name'] = $params['for_module'];
}


if (isset($_GET['per_page'])) {
	$option = array();
	$option['option_value'] = intval($_GET['per_page']);
	$option['option_key'] = 'per_page';
	$option['option_group'] = $params['for_module'];
	save_option($option);
}

$data['limit'] = get_option('per_page', $params['for_module']);

$custom_fields = array();


$data_paging = $data;
$data_paging['page_count'] = 1;


$data_paging = get_form_entires($data_paging);

if ((url_param('current_page') != false)) {
    $data['current_page'] = url_param('current_page');
}

if ($data['limit'] == false) {
	$data['limit'] = 10;
}

$limit_per_page = $data['limit'];

$custom_fields = array();
$data = get_form_entires($data);

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

<div class="table-responsive table-scroll-visible">
    <table id="table_data_<?php print $params['id'] ?>" cellspacing="0" cellpadding="0" width="100%" class="mw-ui-table table-style-2 layout-auto">
        <thead>
        <tr>
            <th class="mw-ui-table-small"><?php _e("ID"); ?></th>
            <th style="width: 150px; text-align: center;"><?php _e("Date"); ?></th>
            <?php if (is_array($custom_fields)): ?>
                <?php foreach ($custom_fields as $k => $item): ?>
                    <th><?php print  mw()->format->no_dashes($k); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
            <th width="20" class="mw-ui-table-small"><?php _e("Delete"); ?></th>
        </tr>
        </thead>

        <tbody>
        <?php if (is_array($data)): ?>
            <?php foreach ($data as $item) : ?>
                <tr class="mw-form-entry-item mw-form-entry-item-<?php print $item['id'] ?>">
                    <td><?php print $item['id'] ?></td>
                    <td style="text-align: center;" class="entry-col">
                        <div class="mw-date" title="<?php print mw()->format->ago($item['created_at'], 1); ?>"><?php print mw()->format->date($item['created_at'], 'd M Y H'); ?>h</div>
                    </td>

                    <?php
                    //	 $custom_fields = array();
                    //	 if(isset($item['custom_fields'])){
                    //    foreach ($item['custom_fields'] as $k=>$value) {
                    //     $custom_fields[$k] =$value;
                    //    }
                    //   }

                    ?>

                    <?php if (is_array($custom_fields)): ?>
                        <?php foreach ($custom_fields as $key => $value): ?>
                            <td class="entry-col">
                                <?php
                                $val_print = '';
                                if (isset($item['custom_fields']) and isset($item['custom_fields'][$key])) {
                                    $val_print = $item['custom_fields'][$key];
                                }
                                $values_plain = mw()->format->clean_html($val_print);

                                if (is_array($values_plain)) {
                                    $values_plain = mw()->format->array_to_ul($val_print);
                                }


                                //var_dump($values_plain);
                                //                                $max = 150;
                                //                                if (strlen($values_plain) > $max) {
                                //                                    $first = substr($values_plain, 0, $max);
                                //                                    $rest = substr($values_plain, $max);
                                //                                    print '<div>' . $first . '<span class="semi_hidden">' . $rest . '</span> <a href="javascript:;" onclick="toggle_show_less(this);" class="mw-ui-link" data-later="Less"> ' . _e('...more') . '</a></div>';
                                //                                } else {
                                //                                    print mw()->format->autolink($val_print);
                                //                                }
                                ?>

                                <?php if (mb_strlen($values_plain) > 70): ?>
                                    <div class="js-limited">
                                        <?php print $values_plain; ?>
                                        <br/>
                                        <button class="js-toggle-full mw-ui-btn mw-ui-btn-info mw-ui-btn-small mw-ui-btn-outline"><span>show more</span><span>show less</span></button>
                                    </div>
                                <?php else: ?>
                                    <?php print $values_plain; ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <td class="mw-ui-table-delete-item">
                        <a class="mw-ui-btn mw-ui-btn-icon mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-medium" href="javascript:mw.forms_data_manager.delete('<?php print $item['id'] ?>','.mw-form-entry-item-<?php print $item['id'] ?>');"><span class="mw-icon-bin"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="100" align="center" style="background: #FFFD8C;"><?php _e("No items found"); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mw-ui-row">

<?php 
$load_list = 'default';
if ((url_param('load_list') != false)) {
	$load_list = url_param('load_list');
}
if ($load_list == 1) {
	$load_list = 'default';
}
?>

 <div class="mw-ui-col">
 
<?php if (strtolower(trim($load_list)) != 'default'): ?>

  <span class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-important mw-ui-delete" onclick="mw.forms_data_manager.delete_list('<?php print addslashes($load_list); ?>');">
  <?php _e("Delete");?> <b><?php echo $listData['title']; ?></b> <?php _e("list"); ?>
  </span>
  <?php endif; ?>
 </div>

<?php if (is_array($data) && !empty($data)) : ?>
<?php if(count($data) > $limit_per_page): ?> 

<div class="mw-ui-col text-center" style="width: 70%">
    <div class="mw-paging mw-paging- mw-paging- inline-block">
    <?php print paging("num=$data_paging"); ?> 
    </div>
    <?php if (isset($params['export_to_excel'])) : ?>
    <?php endif; ?>
    <?php if (isset($params['export_to_excel'])) : ?>
    <?php endif; ?>
</div>

<div class="mw-ui-col">
    <div class="mw-field" style="width:100%;" data-before="<?php _e('Show items per page'); ?>">
        <select onchange="if (this.value) window.location.href=this.value">
            <option value="">Select</option>
            <option value="?per_page=10">10</option>
             <option value="?per_page=50">50</option>
            <option value="?per_page=100">100</option> 
            <option value="?per_page=200">200</option>
        </select>
    </div>
    <br /><br />
  <div class="text-right">
        <strong><?php print _e('Total'); ?>:</strong>
        <span><?php echo count($data); ?> messages in this list</span>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

</div>

<?php
/*<div id="start-email-campaign"> <a class="mw-ui-btn pull-right" href="javascript:;" onclick="Alert('<?php _e("Coming Soon"); ?>!');" >
  <?php _e("Start an Email Campaign"); ?>
  </a> <span class="pull-right" style="margin: 9px 20px 0 0;">
  <?php _e("Get more from your mailing lists, send email to your users"); ?>
  </span> </div>*/

?>
