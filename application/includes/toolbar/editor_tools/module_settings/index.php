<!DOCTYPE HTML>
<html>
          <head>
          <script type="text/javascript" src="<?php   print(INCLUDES_URL); ?>js/jquery.js"></script>
          <script type="text/javascript" src="<?php   print(SITE_URL); ?>apijs"></script>
          <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>default.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>api/api.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/mw_framework.css"/>
          <script type="text/javascript">
  //document.body.className+=' loading';
 
 
  //mw.require("http://raw.github.com/furf/jquery-ui-touch-punch/master/jquery.ui.touch-punch.js");
  mw.require("events.js");
  mw.require("url.js");
  mw.require("tools.js");
 
 mw.require('jquery-ui.js');


			</script>
          <script type="text/javascript">



        $(document).ready(function () {
mw.simpletabs(mwd.getElementById('<? print $params['id'] ?>'));
 mw.$(".mw_option_field").bind("change", function () {
 var refresh_modules11 = $(this).attr('data-refresh');

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).attr('data-reload');
				}

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
                    var refresh_modules11 = '#'+refresh_modules11;
				}

			 

				var og = '<? print $params['id'] ?>';
				 


                 if(this.type==='checkbox'){
                   var val = '';
                   var items = mw.$('input[name="'+this.name+'"]');
                   for(var i=0; i<items.length; i++){
                       var _val = items[i].value;
                       var val = items[i].checked==true ? (val==='' ? _val: val+", "+_val) : val;

                   }
                 }
                 else{val = this.value }





				var o_data = {
                    option_key: $(this).attr('name'),
                    option_group: og,
                    option_value: val
                   // chkboxes:checkboxes_obj
                }
				 <? if(isset( $params['module'])): ?>
					o_data.module = '<? print $params['module'] ?>';
				 <? endif; ?>


                $.ajax({

                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function () {
                        if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()
 
                         
							
							 if (window.parent.mw != undefined) {
								  if (window.parent.mw.reload_module != undefined) {
                                   // window.parent.mw.reload_module(refresh_modules11);
									window.parent.mw.reload_module('#<? print $params['id'] ?>');
                                }
								 
							 } else    if (window.mw != undefined) {
                                if (window.mw.reload_module != undefined) {
                                    window.mw.reload_module(refresh_modules11);
									window.mw.reload_module('#'+refresh_modules11);
                                }
                            }

                        }

                        //  $(this).addClass("done");
                    }
                });
               
            });
            });
          </script>
          </head>
          <body>
          <div class="mw-module-live-edit-settings <? print $params['id'] ?>" id="<? print $params['id'] ?>">{content}</div>
</body>
</html>