<?php

if(get_option('enable_comments', 'comments')=='y'){

  $login_required = get_option('user_must_be_logged', 'comments')=='y';


?>
<?
$data = $params;
if (!isset($params['to_table'])) {

    $data['to_table'] = 'table_content';
}



if (!isset($data['to_table_id'])) {



    if (defined('POST_ID') == true and intval(POST_ID) != 0) {
        $data['to_table_id'] = POST_ID;
    }
}
if (!isset($data['to_table_id'])) {
    if (defined('PAGE_ID') == true) {
        $data['to_table_id'] = PAGE_ID;
    }
}
?>
<?
$comments_data = array();
$comments_data['to_table_id'] = $data['to_table_id'];
$comments_data['to_table'] = $data['to_table'];
//$comments_data['debug'] = $data['to_table'];
$comments = get_comments($comments_data);
//d($data);


$template = get_option('data-template', $params['id']);
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
    $template_file = module_templates($params['type'], $template);

//d();
} else {
  $template_file = module_templates($params['type'], 'default');	
}
?>
<script type="text/javascript">
    mw.require("url.js", true);
    mw.require("tools.js", true);
    mw.require("forms.js", true);
</script>
<script  type="text/javascript">
    $(document).ready(function(){
        mw.$('form#comments-form-<? print $data['id'] ?>').submit(function() {
            mw.form.post('form#comments-form-<? print $data['id'] ?>', '<? print site_url('api/post_comment'); ?>',
			function() {
				mw.reload_module('#<? print $params['id'] ?>');
			});
            return false;
        });
    });
</script>
<?php  switch ($template_file):  case true:  ?>
<? include($template_file); ?>
<?
          if ($template_file != false) {
              break;
          }
        ?>
<?php
    case false:
        ?>

<?php break; ?>
<?php endswitch; ?>
<?php  }   ?>
