<!DOCTYPE HTML>
<html>
          <head>
          
          <?  $module_info = false;
		  if(isset($params['module'])): ?>
           <? $module_info = get_modules_from_db('one=1&ui=any&module=' . $params['module']);    ?>
           <? endif; ?>
       
          <script type="text/javascript" src="<?php   print(SITE_URL); ?>apijs"></script>
          
          <script type="text/javascript">
            mw.require("<?php   print(INCLUDES_URL); ?>js/jquery.js");
  		    mw.require("<?php   print(INCLUDES_URL); ?>api/jquery-ui.js");
          </script>



          
          <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>default.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>api/api.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/mw_framework.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/liveadmin.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/admin.css"/>


		   <script type="text/javascript">
  //document.body.className+=' loading';

 
  //mw.require("http://raw.github.com/furf/jquery-ui-touch-punch/master/jquery.ui.touch-punch.js");


  mw.require("events.js");
  mw.require("url.js");
  mw.require("tools.js");
   mw.require("forms.js");
 mw.require('wysiwyg.js');

</script>

<style>

#settings-main{
 /* overflow-x:hidden;
  overflow-y:auto; */
  min-height: 200px;

}

#settings-container{
  overflow: hidden;
  position: relative;
  min-height: 200px;

}

#settings-container:after{
  content: ".";
  display: block;
  clear: both;
  visibility: hidden;
  line-height: 0;
  height: 0;
}

</style>

 <script type="text/javascript">
         <? if(isarr( $module_info)): ?>

           mw_module_settings_info  = <? print json_encode($module_info); ?>


           if(typeof thismodal != 'undefined'){



            $(thismodal.main).find(".mw_modal_title").html(mw_module_settings_info.name);



            __autoresize = function(){
                var _old = thismodal.main.height();
                parent.mw.tools.modal.resize("#"+thismodal.main[0].id, false, $('#settings-container').height()+25, false);
                var _new = thismodal.main.height();
                if(_new>_old) {
                   parent.mw.tools.modal.center("#"+thismodal.main[0].id, 'vertical')
                }
            }


              $(window).load(function () {
                $(mwd.body).removeClass('mw-external-loading');
                parent.mw.tools.modal.resize("#"+thismodal.main[0].id, false, $('#settings-container').height()+25, true);
                $(mwd.body).bind('mouseup DOMNodeInserted',function(){
                  setTimeout(function(){
                     __autoresize();
                  }, 99);
                }).ajaxStop(function(){
                    setTimeout(function(){
                    __autoresize();
                  }, 99);
                });

              });





           };


          <? endif; ?>




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



			  var also_reload =  $(this).attr('data-also-reload');

			 

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
 
                         
						 
						 if(also_reload != undefined){
							 if (window.mw.reload_module != undefined) {
                                    window.mw.reload_module(also_reload);
							 
                                }
						 }
							
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
          <body class="mw-external-loading">

          <div id="settings-main">
            <div id="settings-container">


                  <div class="mw-module-live-edit-settings <? print $params['id'] ?>" id="<? print $params['id'] ?>">{content}</div>

                   <div class="mw_clear">&nbsp;</div>
            </div>
            <div class="mw_clear">&nbsp;</div>
          </div>
</body>
</html>