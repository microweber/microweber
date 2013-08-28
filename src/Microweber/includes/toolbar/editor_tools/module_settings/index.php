<!DOCTYPE HTML>
<html>
          <head>

          <?php  $module_info = false;
		  if(isset($params['module'])): ?>
           <?php $module_info = mw('module')->get('one=1&ui=any&module=' . $params['module']);    ?>
           <?php endif; ?>

          <script type="text/javascript" src="<?php   print(MW_INCLUDES_URL); ?>js/jquery.js"></script>
          <script type="text/javascript" src="<?php   print(MW_INCLUDES_URL); ?>api/jquery-ui.js"></script>
          <script type="text/javascript" src="<?php   print(MW_SITE_URL); ?>apijs"></script>

          <script type="text/javascript">

            liveEditSettings = true;

          </script>



          
          <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>default.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>api/api.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/mw_framework.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/liveadmin.css"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/admin.css?v=<?php print uniqid(); ?>"/>
          <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/popup.css?v=<?php print uniqid(); ?>"/>


		   <script type="text/javascript">
  //document.body.className+=' loading';


  //mw.require("http://raw.github.com/furf/jquery-ui-touch-punch/master/jquery.ui.touch-punch.js");



    mw.require("events.js", true);
    mw.require("url.js", true);
    mw.require("tools.js", true);
    mw.require("forms.js", true);
    mw.require('wysiwyg.js', true);

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

 window.onbeforeunload = function(){
   $(mwd.body).addClass("mw-external-loading")
 }

        mw_module_settings_info = "";
         <?php if(is_array( $module_info)): ?>

           mw_module_settings_info  = <?php print json_encode($module_info); ?>
		   
		   mw_module_params  = <?php print  json_encode($params); ?>
 
         <?php endif; ?>

         if(typeof thismodal == 'undefined' && self !== parent){
           var frame = parent.mw.$('#'+this.name)[0];
           thismodal = parent.mw.tools.modal.get(mw.tools.firstParentWithClass(frame, 'mw_modal'));
         }


           if(typeof thismodal != 'undefined' && thismodal != false){

            $(thismodal.main).find(".mw_modal_title").html(mw_module_settings_info.name);
            thismodal.main.scrollTop(0);
            __autoresize = function(){
                var _old = thismodal.main.height();
                thismodal.main.scrollTop(0);
                parent.mw.tools.modal.resize("#"+thismodal.main[0].id, false, mw.$('#settings-container').height()+25, false);
                var _new = thismodal.main.height();
                if(_new>_old) {
                   parent.mw.tools.modal.center("#"+thismodal.main[0].id, 'vertical');
                }

            }

			

			var toolbar = thismodal.main[0].querySelector('.mw_modal_toolbar');
			is_module_tml_holder = $(toolbar).find("#module-modal-settings-menu-holder");
			if(is_module_tml_holder.length == 0 ){
			    var dd =  mwd.createElement('div');
                dd.id = 'module-modal-settings-menu-holder';
                dd.className = 'mw-ui-dropdown mw-ui-dropdown-click';


				$(toolbar).append(dd);
			}

			

			
			  <?php if(is_array( $module_info)): ?>
			  <?php $mod_adm =  admin_url('view:').module_name_encode($module_info['module']);; ?>
			  is_module_tml_holder = $(toolbar).find("#module-modal-settings-menu-holder");
			  if(is_module_tml_holder.length > 0 ){


              is_module_tml_holder.empty();

              var holder = mwd.createElement('div');
              holder.className = 'mw-dropdown-content';




              var html = ""
              + "<div id='module-modal-settings-menu-items' module_id='<?php print $params['id'] ?>' module_name='<?php print $module_info['module'] ?>'>"
              + "</div>"
              + "<hr>"
              + "<div id='module-modal-settings-menu-holder-2'><a class='mw-ui-btn mw-ui-btn-small' href='<?php print $mod_adm  ?>'><?php _e("Go to admin"); ?></a></div>"

              var btn = "<a class='mw-ui-btn mw-ui-btn-small' onclick='$(this).toggleClass(\"active\")'><span class='ico idownarr right'></span><?php _e("Menu"); ?></a>";


              $(holder).append(html);

              $(dd).prepend(btn);

			  is_module_tml_holder.append(holder);

                parent.mw.load_module("admin/modules/saved_templates", '#module-modal-settings-menu-items' );

			 }
                                            
          	<?php endif; ?>
			
			
		



              $(window).load(function () {





                parent.mw.tools.modal.resize("#"+thismodal.main[0].id, false, $('#settings-container').height()+25, true);

                $(mwd.body).bind('mouseup click DOMNodeInserted',function(){
                  setTimeout(function(){
                     __autoresize();

                  }, 99);
                }).ajaxStop(function(){
                    setTimeout(function(){

                    __autoresize();
                  }, 99);
                });

                setInterval(function(){
                    __autoresize();
                }, 99);





              });






           };


            $(window).load(function () {

                   $(mwd.body).removeClass('mw-external-loading');
                $(mwd.body).ajaxStop(function(){
                 $(mwd.body).removeClass('mw-external-loading');
                });

             });


			</script>
          <script type="text/javascript">



        $(document).ready(function () {
mw.simpletabs(mwd.getElementById('<?php print $params['id'] ?>'));
 mw.$(".mw_option_field").bind("change", function (e) {


                if(typeof liveEditSettings === 'boolean'){
                  if(liveEditSettings){
                    $(mwd.body).addClass('loading');
                  }
                }

 var refresh_modules11 = $(this).attr('data-refresh');

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).attr('data-reload');
				}

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
                    var refresh_modules11 = '#'+refresh_modules11;
				}



			  var also_reload =  $(this).attr('data-also-reload');

			 

				var og = '<?php print $params['id'] ?>';
				 


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
				 <?php if(isset( $params['module'])): ?>
					o_data.module = '<?php print $params['module'] ?>';
				 <?php endif; ?>


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
									window.parent.mw.reload_module('#<?php print $params['id'] ?>');
                                }
								 
							 } else    if (window.mw != undefined) {
                                if (window.mw.reload_module != undefined) {
                                    window.mw.reload_module(refresh_modules11);
									window.mw.reload_module('#'+refresh_modules11);
                                }
                            }

                        }

                        if(typeof liveEditSettings === 'boolean'){
                  if(liveEditSettings){
                    $(mwd.body).removeClass('loading');
                  }
                }

                        //  $(this).addClass("done");
                    }
                });
               
            });
            });
          </script>
          </head>
          <body class="mw-external-loading loading">

          <div id="settings-main">
            <div id="settings-container">


                  <div class="mw-module-live-edit-settings <?php print $params['id'] ?>" id="<?php print $params['id'] ?>">{content}</div>


            </div>

          </div>
          
          
          
          
          
          
          <form method="get" id="mw_reload_this_module_popup_form" style="display:none">
<?php if(is_array($params )): ?>
  <?php foreach($params  as $k=> $item): ?> 
<input type="text" name="<?php print $k ?>" value="<?php print $item ?>" />

 <?php endforeach ; ?>
 <input type="submit" />
<?php endif; ?>
</form>
          
</body>
</html>