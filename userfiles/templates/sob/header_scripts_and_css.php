<script type="text/javascript">
function css_browser_selector(u){var ua = u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',o='opera',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);
</script>
<script type="text/javascript">

			var template_url = '<?php print TEMPLATE_URL; ?>';
            <?php if(!empty($subdomain_user)): ?>
                var img_url = '<?php print TEMPLATE_URL; ?>affimg/';
  			    var imgurl = '<?php print TEMPLATE_URL; ?>affimg/';
            <?php else: ?>
                var img_url = '<?php print TEMPLATE_URL; ?>img/';
  			    var imgurl = '<?php print TEMPLATE_URL; ?>img/';
            <?php endif;?>
			var site_url = '<?php print site_url(); ?>';
			var ajax_mail_form_url = '<?php print site_url('main/mailform_send'); ?>';
			var ajax_mail_form_url_validate = '<?php print site_url('main/mailform_send/validate:yes'); ?>';
			var ajax_mail_form_url = '<?php print site_url('main/mailform_send2'); ?>';
			var current_url = '<?php print current_url(); ?>';
			var imgurl = '<?php print TEMPLATE_URL; ?>img/';


        </script>
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>css/style.css" type="text/css" media="all"  />
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>css/jquery.autocomplete.css" type="text/css" />
<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">
google.load("jquery", "1.4.2");
//google.load("jqueryui", "1.7.1");
</script>

<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/ooyes.api.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/ooyes.validate.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/functions.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/ajax.js"></script>
 <script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/jquery.form.js"></script>
<!-- <script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/jquery.autocomplete.min.js"></script>-->
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/jquery.fn.handleKeyboardChange.js"></script>
<script type="text/javascript" src="{JS_API_URL}"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<!--<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>js/jquery.autocomplete/styles.css" type="text/css" />-->

<?php if($user_profile_edit): ?>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/valums-ajax-upload/ajaxupload.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/jcrop/js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>js/jcrop/css/jquery.Jcrop.css" type="text/css" />
<?php endif; ?>


 <!--[if IE]><?php echo '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->
<link rel="shortcut icon" type="image/x-icon" href="<?php print TEMPLATE_URL; ?>favicon.ico" />
<script src="<?php print TEMPLATE_URL; ?>js/jquery.idle/idle-timer.js" type="text/javascript"></script>
<script type="text/javascript">
    publish_dropdown = ''
    + '<li><a href="<?php print site_url('users/user_action:post/type:none'); ?>">Article</a></li>'
    + '<li><a href="<?php print site_url('users/user_action:post/type:trainings'); ?>">Traning</a></li>'
    + '<li><a href="<?php print site_url('users/user_action:post/type:products'); ?>">Product</a></li>'
    + '<li><a href="<?php print site_url('users/user_action:post/type:services'); ?>">Service</a></li>'
    + '<li><a href="<?php print site_url('users/user_action:post/type:blog'); ?>">Blog</a></li>'
    + '<li><a style="border:none;" href="<?php print site_url('users/user_action:post/type:gallery'); ?>">Gallery</a></li>';
</script>

<script type="text/javascript">

   /* (function($){



        $(document).bind("idle.idleTimer", function(){

            //$("#status").html("User is idle :(").css("backgroundColor", "silver");

			//	alert(current_url);

			parent.location.href=current_url;

        });

        $(document).bind("active.idleTimer", function(){

            // $("#status").html("User is active :D").css("backgroundColor", "yellow");

        }).trigger('active.idleTimer');;

        $.idleTimer(777000);

    })(jQuery);*/
    $(document).ready(function(){
      $("a[href*='http://']").not("[href*='<?php print site_url(); ?>']").each(function(){
        var html = $(this).html();
        var href = $(this).attr("href");
        $(this).attr("target", "_blank");
		
		$("#user_image").hide();
		

		<?php if(!empty($post)): ?>
		 $(this).attr("href", "<?php print site_url(); ?>surl.php?post=<?php print $post['id'] ?>&url=" + href);
		 <?php else: ?>
		  $(this).attr("href", "<?php print site_url(); ?>surl.php?url=" + href);
		<?php endif; ?>
		

        if(mw.browser.msie()){ // msie !!!
           $(this).html(html);
        }

      });
    });


</script>
<script type="text/javascript">


function userPictureDelete(){
	if(confirm('Do You realy want to delete this photo?')){
		$.post("<?php  print site_url('ajax_helpers/user_delete_picture')  ?>", { time: Math.random() },
				   function(data){			
			 			$("#user_image").hide();
			 			$("#user_image_href").hide();
				   });
	}
	
	
}
 
                    $(document).ready(function(){

                        var options = {
                            //target:        '#output2',   // target element(s) to be updated with server response
                            beforeSubmit:  Before_submit,  // pre-submit callback
                            success:       After_submit,  // post-submit callback
                            // other available options:
                            url:       '<?php print site_url('main/mailform_send2'); ?>'         // override for form's 'action' attribute
                            //type:      type        // 'get' or 'post', override for form's 'method' attribute
                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                            //clearForm: true        // clear all form fields after successful submit
                            //resetForm: true        // reset the form after successful submit

                            // $.ajax options can be used here too, for example:
                            //timeout:   3000
                        };

                        $("#contact-form").submit(function(){
                          if($(this).hasClass("error")){
                             /*  */
                          }
                          else{

                             $("#contact-form").ajaxSubmit(options);

                             return false;
                          }
                        });

                    });

                      function Before_submit(){
                          //alert(1)
                          $("#cloading").css("visibility", "visible");
                          return true;
                      }

                      function After_submit(data){
                          $("#cloading").css("visibility", "hidden");
                           var message = "<div id='msgSent'>Thank you. <br />Your message has been sent.</div>"+data;
                           mw.box.alert(message);
                      }
                </script>

<?php //if(is_file(ACTIVE_TEMPLATE_DIR.'shop_cart.js.php') == true){ include (ACTIVE_TEMPLATE_DIR.'shop_cart.js.php'); } ?>
<?php if(is_file(ACTIVE_TEMPLATE_DIR.'users/users.js.php') == true){ include (ACTIVE_TEMPLATE_DIR.'users/users.js.php'); }  ?> 
<?php if($load_tiny_mce == true) : ?>








<script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
<script type="text/javascript">
$(document).ready(function(){





		$('textarea.richtext').tinymce({
			// Location of TinyMCE script
			script_url : '<?php print_the_static_files_url() ; ?>js/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : mcePlugins,

			// Theme options
			theme_advanced_buttons1 : mceButtons.buttons1,
			theme_advanced_buttons2 : mceButtons.buttons2,
			theme_advanced_buttons3 : mceButtons.buttons3,
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			relative_urls : false,
			convert_urls : false,
			remove_script_host : false,
			document_base_url : "<?php print site_url(); ?>",
			valid_elements : validElements , //in ooyes.api.js
			remove_linebreaks : false,
			height : "auto",
			//content_css : "http://192.168.0.197/wfl_dev/userfiles/templates/waterforlifeusa/css/ooyes.framework.css?" + new Date().getTime(),
			file_browser_callback : "ajaxfilemanager",
            theme_advanced_resizing_use_cookie : false,
            theme_advanced_resizing : false,

			

			// Example content CSS (should be your site CSS)
			//content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			//template_external_list_url : "lists/template_list.js",
			//external_link_list_url : "lists/link_list.js",
			//external_image_list_url : "lists/image_list.js",
			//media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});











function ajaxfilemanager(field_name, url, type, win) {
			var ajaxfilemanagerurl = "<?php print_the_static_files_url() ; ?>js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
			switch (type) {
				case "image":
					break;
				case "media":
					break;
				case "flash": 
					break;
				case "file":
					break;
				default:
					return false;
			}
            tinyMCE.activeEditor.windowManager.open({
                url: "<?php print_the_static_files_url() ; ?>js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",
                width: 782,
                height: 440,
                inline : "yes",
				 resizable : "yes",
                close_previous : "yes"
            },{
                window : win,
                input : field_name
            });
            
/*            return false;			
			var fileBrowserWindow = new Array();
			fileBrowserWindow["file"] = ajaxfilemanagerurl;
			fileBrowserWindow["title"] = "Ajax File Manager";
			fileBrowserWindow["width"] = "782";
			fileBrowserWindow["height"] = "440";
			fileBrowserWindow["close_previous"] = "no";
			tinyMCE.openWindow(fileBrowserWindow, {
			  window : win,
			  input : field_name,
			  resizable : "yes",
			  inline : "yes",
			  editor_id : tinyMCE.getWindowArg("editor_id")
			});
			
			return false;*/
		}
</script>
<!-- /TinyMCE -->








<?php endif; ?>