<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php  $module_info = false;
if (isset($params['module'])): ?>
<?php $module_info = mw()->modules->get('one=1&ui=any&module=' . $params['module']); ?>
<?php endif; ?>   
                                                              
<script type="text/javascript" src="<?php print(site_url()); ?>apijs"></script>
<script type="text/javascript" src="<?php   print(site_url());  ?>apijs_settings?id=<?php print CONTENT_ID; ?>"></script>

<script src="<?php print mw_includes_url(); ?>api/jquery-ui.js"></script>
<script type="text/javascript">
    liveEditSettings = true;
     mw.require('<?php print mw_includes_url(); ?>default.css');
    mw.require('<?php print mw_includes_url(); ?>css/components.css');
    mw.require('<?php print mw_includes_url(); ?>css/admin.css');
    mw.require('<?php print mw_includes_url(); ?>css/popup.css');
    mw.require("events.js");
    mw.require("url.js");
    mw.require("tools.js");
    mw.require("liveadmin.js");
    mw.require("forms.js");
    mw.require('wysiwyg.js');
    mw.require('options.js');

    mw.require('admin.js');
</script>
<style>
#settings-main {
	/* overflow-x:hidden;
         overflow-y:auto; */
        min-height: 200px;
}
#settings-container {
	overflow: hidden;
	position: relative;
	min-height: 200px;
}
#settings-container:after {
	content: ".";
	display: block;
	clear: both;
	visibility: hidden;
	line-height: 0;
	height: 0;
}
</style>
<?php
$autoSize = true;
if (isset($_GET['autosize'])) {
    $autoSize = $_GET['autosize'];
}

$type = '';
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}
?>
<script type="text/javascript">

    autoSize = <?php print $autoSize; ?>;
    settingsType = '<?php print $type; ?>';

    window.onbeforeunload = function () {
        $(mwd.body).addClass("mw-external-loading")
    }

    mw_module_settings_info = "";
    <?php if(is_array( $module_info)): ?>

    mw_module_settings_info = <?php print json_encode($module_info); ?>
        <?php $mpar =$params;
        if(isset($mpar['module_settings'])){
      unset($mpar['module_settings']);
        }

        ?>


        mw_module_params =
    <?php print json_encode($mpar); ?>

    <?php endif; ?>

    if (typeof thismodal == 'undefined' && self !== parent) {
        var frame = parent.mw.$('#' + this.name)[0];
        thismodal = parent.mw.tools.modal.get(mw.tools.firstParentWithClass(frame, 'mw_modal'));
    }


    if (typeof thismodal != 'undefined' && thismodal != false) {

        $(thismodal.main).find(".mw_modal_title").html(mw_module_settings_info.name);
        thismodal.main.scrollTop(0);
        __autoresize = function (force) {
            var force = force || false;
            var _old = thismodal.main.height();
            thismodal.main.scrollTop(0);
            parent.mw.tools.modal.resize("#" + thismodal.main[0].id, false, mw.$('#settings-container').height() + 25, false);
            setTimeout(function () {
                var _new = thismodal.main.height();
                if (_new > _old || force) {
                    parent.mw.tools.modal.center("#" + thismodal.main[0].id, 'vertical');
                }
            }, 400)


        }


        var toolbar = thismodal.main[0].querySelector('.mw_modal_toolbar');
        is_module_tml_holder = $(toolbar).find("#module-modal-settings-menu-holder");
        if (is_module_tml_holder.length == 0) {
            var dd = mwd.createElement('div');
            dd.id = 'module-modal-settings-menu-holder';
            dd.className = 'mw-ui-dropdown mw-ui-dropdown-click';

           /*******************************************************
            Do not delete !!! Module template: list and 'Crete Module Template'

            $(toolbar).append(dd);
           *******************************************************/


        }


        <?php if(is_array( $module_info)): ?>
		
 
		
        <?php $mod_adm =  admin_url('load_module:').module_name_encode($module_info['module']);; ?>
        is_module_tml_holder = $(toolbar).find("#module-modal-settings-menu-holder");
        if (is_module_tml_holder.length > 0) {


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

            parent.mw.load_module("admin/modules_manager/saved_templates", '#module-modal-settings-menu-items');

        }

        <?php endif; ?>


        $(window).load(function () {


            if (autoSize) {

                parent.mw.tools.modal.resize("#" + thismodal.main[0].id, false, $('#settings-container').height() + 25, true);

                $(mwd.body).bind('mouseup click DOMNodeInserted',function () {
                    setTimeout(function () {
                        __autoresize();


                    }, 99);
                }).ajaxStop(function () {
                        setTimeout(function () {

                            __autoresize();
                        }, 99);
                    });

                setInterval(function () {
                    __autoresize();
                }, 99);

                $(window.parent.window).bind("resize", function () {
					if(parent != null){
                    parent.mw.tools.modal.center("#" + thismodal.main[0].id);
					}
                });
            }


        });


    }
    ;


    $(window).load(function () {


        $(mwd.body).removeClass('mw-external-loading');
        $(mwd.body).ajaxStop(function () {
            $(mwd.body).removeClass('mw-external-loading');
        });

    });


</script>
<?php

//var_dump($params);

?>
<script type="text/javascript">


    $(document).ready(function () {




         mw.$(".mw_option_field").addClass('mw-options-form-binded');
        mw.$(".mw_option_field").bind("change", function (e) {
			
			if($(this).hasClass('mw-options-form-binded-custom')){
			return;
			}
			
			
			
            if (mw.notification != undefined) {
                mw.notification.success('<?php _e('Settings are saved') ?>');
            }


            if (typeof liveEditSettings === 'boolean') {
                if (liveEditSettings) {
                    $(mwd.body).addClass('loading');
                }
            }


            var reaload_in_parent = $(this).attr('parent-reload');
            var refresh_modules11 = $(this).attr('data-refresh');

            if (refresh_modules11 == undefined) {
                var refresh_modules11 = $(this).attr('data-reload');
            }

            if (refresh_modules11 == undefined) {
                var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
                var refresh_modules11 = '#' + refresh_modules11;


            }

            var also_reload = $(this).attr('data-also-reload');
            if (og == also_reload || also_reload == null) {
                var also_reload = $(this).attr('data-reload');
            }
		    var og = $(this).attr('data-option-group');
            if (og == undefined || og == null) {
                  var og = $(this).attr('option-group');
            }
            if (og == undefined || og == null) {
                var og = '<?php print $params['id'] ?>';
            }
            if (this.type === 'checkbox') {
                var val = '';
                var items = mw.$('input[name="' + this.name + '"]');
                for (var i = 0; i < items.length; i++) {
                    var _val = items[i].value;
                    var val = items[i].checked == true ? (val === '' ? _val : val + ", " + _val) : val;
                }
            }
            else {
                val = this.value
            }


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
                url: mw.settings.site_url + "api/save_option",
                data: o_data,
                success: function () {
                    if (refresh_modules11 != undefined && refresh_modules11 != '') {
                        refresh_modules11 = refresh_modules11.toString();
						 if(!!mw.admin){
						 if(typeof(top.mweditor) != 'undefined'  && typeof(top.mweditor) == 'object'   && typeof(top.mweditor.contentWindow) != 'undefined'){
							 top.mweditor.contentWindow.mw.reload_module('#<?php print $params['id'] ?>')
							}
						 
						 }
                        if (window.parent.mw != undefined) {
							
							if(self !== top){
								mw.reload_module_parent('#<?php print $params['id'] ?>');
							}
							
                            if (window.parent.mw.reload_module != undefined) {
                                if(!!mw.admin){
                                  window.parent.mw.reload_module('#<?php print $params['id'] ?>');
                                }
                                else{
                                    if (window.parent.mweditor != undefined) {
                                      window.parent.mweditor.contentWindow.mw.reload_module('#<?php print $params['id'] ?>', function(){
                                        setTimeout(function(){
                                           window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                        }, 333);
                                      });
                                    }
                                    if (window.parent.mw != undefined) {
                                        window.parent.mw.reload_module('#<?php print $params['id'] ?>', function(){
                                            setTimeout(function(){
                                                window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                            }, 333);
                                        });
                                    }
                                }

                            }
                        }
                        if (also_reload != undefined) {

                            var curm = "";

                            <?php if(isset( $params['module'])): ?>
                            var curm = "<?php print $params['module'] ?>";
                            <?php endif; ?>


                            if (curm == also_reload) {
								
								// window.mw.reload_module(also_reload);
								 window.location.href = window.location.href;
                                //$('#mw_reload_this_module_popup_form').submit();

                            } else {
                                if (window.mw.reload_module != undefined) {


                                    window.mw.reload_module(also_reload);
									
									
									if(self !== top){
										mw.reload_module_parent(also_reload);
									}

                                }
                            }
                        }
                    }
                    if (typeof liveEditSettings === 'boolean') {
                        if (liveEditSettings) {
                            $(mwd.body).removeClass('loading');
                        }
                    }
                }
            });

        });
    });
</script>
</head>
<body class="mw-external-loading loading">
<div id="settings-main">
  <div id="settings-container">
    <div class="mw-module-live-edit-settings <?php print $params['id'] ?>"
             id="module-id-<?php print $params['id'] ?>">{content} </div>
  </div>
</div>
<form method="get" id="mw_reload_this_module_popup_form" style="display:none">
  <?php $mpar = $params;
    if (isset($mpar['module_settings'])) {
        unset($mpar['module_settings']);
    }

    ?>
  <?php if (is_array($params)): ?>
  <?php foreach ($params as $k => $item): ?>
  <input type="text" name="<?php print $k ?>" value="<?php print $item ?>"/>
  <?php endforeach; ?>
  <input type="submit" />
  <?php endif; ?>
</form>
<script type="text/javascript">
    $(document).ready(function () {

        __global_options_save_msg = function () {
            if (mw.notification != undefined) {
                mw.notification.success('Settings are updated!');
            }

            if (window.parent.mw != undefined && window.parent.mw.reload_module != undefined) {
                window.parent.mw.reload_module("#<?php print $params['id'] ?>");
            }

        }

        // mw.options.form('#settings-container');
    });
</script>
</body>
</html>