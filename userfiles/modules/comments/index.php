<?php

if(get_option('enable_comments', 'comments')=='y'){

  $login_required = get_option('user_must_be_logged', 'comments')=='y';


?>
<?php
$paging_param = $params['id'].'_page';
 $curent_page_from_url = url_param($paging_param);
if(isset($params['content-id'])){
	 $data['rel'] = 'content';
	 $data['rel_id'] = $params['content-id'];
}


$data = $params;
if (!isset($params['rel'])) {

    $data['rel'] = 'content';
}


    $display_comments_from_which_post =  get_option('display_comments_from_which_post', $params['id']);
	//d( $display_comments_from_which_post);

if($display_comments_from_which_post == 'current_post' and isset($data['rel_id'])){

unset($data['rel_id']);

}
if (!isset($data['rel_id']) or $data['rel_id'] == false) {



    if (defined('POST_ID') == true and intval(POST_ID) != 0) {
       $data['rel_id'] = POST_ID;
    }
}
if (!isset($data['rel_id'])) {
    if (defined('PAGE_ID') == true) {
      $data['rel_id'] = PAGE_ID;
    }
}
if (!isset($data['rel_id'])) {

 $data['rel_id'] = $params['id'];

}


$display_comments_from =  get_option('display_comments_from', $params['id']);
$enable_comments_paging = get_option('enable_comments_paging',  $params['id'])=='y';
$global_per_page = get_option('paging', 'comments');
$global_set_paging = get_option('set_paging', 'comments') == 'y';



$hide_comment_form = false;
$comments_data = array();
$comments_data['rel_id'] = $data['rel_id'];
$comments_data['rel'] = $data['rel'];

if($display_comments_from  != false and $display_comments_from   == 'current' and $display_comments_from_which_post != false and $display_comments_from_which_post != 'current_post'){

$comments_data['rel_id'] = $data['rel_id'] =  $display_comments_from_which_post;
$comments_data['rel'] =  $data['rel'] = 'content';
}






$form_title = false;
$display_form_title_from_module_id =  get_option('form_title', $params['id']);
 if($display_form_title_from_module_id != false and trim($display_form_title_from_module_id) != ''){
	$form_title = $display_form_title_from_module_id;
  }



if($display_comments_from  != false and $display_comments_from   == 'recent'){
$hide_comment_form = true;
$comments_data = array();
$comments_data['order_by'] = "created_on desc";

}


if($display_comments_from  != false and $display_comments_from   == 'module'){

$comments_data['rel_id'] =   $data['rel_id'] =  $params['id'];
$comments_data['rel'] =  $data['rel'] = 'modules';
 $display_comments_from_module_id =  get_option('module_id', $params['id']);
 if($display_comments_from_module_id != false and trim($display_comments_from_module_id) != ''){
		$comments_data['rel_id'] =   $data['rel_id'] =  $display_comments_from_module_id;
		$display_form_title_from_module_id =  get_option('form_title', $display_comments_from_module_id);
 if($display_form_title_from_module_id != false and trim($display_form_title_from_module_id) != ''){
	$form_title = $display_form_title_from_module_id;
  }
  }


}




$paging  = false;
$comments_per_page = get_option('comments_per_page',  $params['id']);


 $disabled_comments_paging = get_option('enable_comments_paging',  $params['id'])=='n';

if( $enable_comments_paging == false and $global_set_paging != false and  $disabled_comments_paging == false){
	 if(intval($global_per_page) > 0){
		 $enable_comments_paging = 1;
		 $comments_per_page = $global_per_page;
	 }
}





if( $enable_comments_paging != false){
	 if(intval( $comments_per_page) != 0) {

 		 $comments_data['limit'] =   $comments_per_page;

		 $paging_data  = $comments_data;
		 $paging_data['page_count']  = true;
		// d($paging_data);
		 $paging = get_comments( $paging_data);
		// d( $paging);
	 }

}










if( $curent_page_from_url != false){
	if( intval( $curent_page_from_url) > 0){
	$comments_data['curent_page'] = intval( $curent_page_from_url);

	}
}






 $comments = get_comments($comments_data);




$template = get_option('data-template', $params['id']);
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
    $template_file = module_templates($params['type'], $template);

//
} else {
  $template_file = module_templates($params['type'], 'default');
}



?>
<script type="text/javascript">

    mw.require("url.js", true);
    mw.require("tools.js", true);
    mw.require("forms.js", true);
</script>




















<script type="text/javascript">
    $(document).ready(function(){


    });

 mw.init_commnets = function(){

var comm_hold = "login-comments-form-<?php print $params['id'] ?>";
var comm_module_id = "<?php print $params['id'] ?>";
 var login_hold = "login-comments-form-<?php print $params['id'] ?>";



		 mw.$('#'+comm_module_id+' a.comments-login-link').click(function() {


			if($('#'+login_hold).length == 0){
			 $('#<?php print $params['id']; ?>').append('<span id='+comm_hold+'></span>');

			}
			 mw.load_module('users/login','#'+login_hold);
			 return false;
		});


			 mw.$('#<?php print $params['id'] ?> a.comments-register-link').click(function() {
			    var login_hold = "login-comments-form-<?php print $params['id'] ?>";
					if($('#'+login_hold).length == 0){
					   	$('#<?php print $params['id'] ?>').append('<span id="login-comments-form-<?php print $params['id'] ?>"></span>');
					}
			 mw.load_module('users/register','#'+login_hold);
			 return false;
		});





        mw.$('form#comments-form-<?php print $params['id'] ?>').submit(function() {

            mw.form.post('form#comments-form-<?php print $params['id'] ?>', '<?php print site_url('api/post_comment'); ?>',
			function(msg) {

				var resp = this;
				 var data2 =  (resp);
				 if(typeof(data2.error) != 'undefined'){
                    mw.response(mw.$('form#comments-form-<?php print $params['id'] ?>'),data2);
				 }
				if(typeof(resp.error) != 'undefined'){
					var err_hold = "error-comments-form-<?php print $params['id'] ?>";
					if($('#'+err_hold).length == 0){
					    var html = "<span class='alert alert-error' id='"+err_hold+"'></span>";
						$('#comments-form-<?php print $params['id'] ?>').append(html);
					}
					$('#'+err_hold).html(resp.error);
				}
                else {
					mw.reload_module('#<?php print $params['id'] ?>');
				}

			});
            return false;
        });
 }


</script>
<?php
 include($template_file);
switch ($template_file):  case true:  ?>

<?php
          if ($template_file != false) {
              break;
          }
        ?>
<?php
    case false:
        ?>
<?php break; ?>
<?php endswitch; ?>
<?php  }  else {

	mw_notif_live_edit('Comments posting is disabled from the admin panel');
} ?>
