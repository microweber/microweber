<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php  $module_info = false;
if (isset($params['module'])): ?>
<?php $module_info = mw()->modules->get('one=1&ui=any&module=' . $params['module']); ?>
<?php endif; ?>


<script type="text/javascript" src="<?php print(mw()->template->get_apijs_settings_url()); ?>"></script>

<script type="text/javascript" src="<?php print(mw()->template->get_apijs_url()); ?>"></script>

<script src="<?php print mw_includes_url(); ?>api/jquery-ui.js"></script>
<script type="text/javascript">
    liveEditSettings = true;

    mw.require('<?php print mw_includes_url(); ?>default.css');
    mw.require('<?php print mw_includes_url(); ?>css/components.css');
    mw.require('<?php print mw_includes_url(); ?>css/admin.css');
    mw.require('<?php print mw_includes_url(); ?>css/admin-new.css');
    mw.require('<?php print mw_includes_url(); ?>css/fade-window.css');
    mw.require('<?php print mw_includes_url(); ?>css/popup.css');
    <?php if(_lang_is_rtl()){ ?>
    mw.require('<?php print mw_includes_url(); ?>css/rtl.css');
    <?php } ?>
    mw.require("events.js");
    mw.require("url.js");
    mw.require("tools.js");
	mw.require('admin.js');


    mw.require("liveadmin.js");
    mw.require("forms.js");
    mw.require('wysiwyg.js');
    mw.require('options.js');
    mw.lib.require('font_awesome');

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

$mod_id = $mod_orig_id =false;
$is_linked_mod = false;

if(isset($params['id'])){
    $mod_orig_id = $mod_id = $params['id'];
}


if (isset($params['data-module-original-id']) and $params['data-module-original-id']) {
    $mod_orig_id = $params['data-module-original-id'];
}
if($mod_id != $mod_orig_id){
    $is_linked_mod = true;
}

?>
<script type="text/javascript">
    addIcon = function () {
        if(window.thismodal && thismodal.main){
            var holder = $(".mw_modal_toolbar", thismodal.main);
            if($('.mw_modal_icon', holder).length === 0){
                holder.prepend('<span class="mw_modal_icon"><img src="<?php print $module_info['icon']; ?>"></span>')
            }
        }
    };
    addIcon();

    autoSize = <?php print $autoSize; ?>;
    settingsType = '<?php print $type; ?>';

    window.onbeforeunload = function () {
        $(mwd.body).addClass("mw-external-loading")
    };


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

    if (typeof thismodal == 'undefined' && self !== parent && typeof this.name != 'undefined' && this.name != '') {

        var frame = parent.mw.$('#' + this.name)[0];
        thismodal = parent.mw.tools.modal.get(mw.tools.firstParentWithClass(frame, 'mw_modal'));

    }


    if (typeof thismodal != 'undefined' && thismodal != false) {



        var modal_title_str = '';
        if(typeof(mw_module_settings_info.name) == "undefined"){
            modal_title_str = "<?php _e("Settings"); ?>"
        } else {
            modal_title_str = mw_module_settings_info.name;
        }


		var ex_title =  $(thismodal.main).find(".mw_modal_title").html();

		if(ex_title == ''){
        $(thismodal.main).find(".mw_modal_title").html(modal_title_str+'');
		}
		if (typeof thismodal.main.scrollTop == 'function') {
			 thismodal.main.scrollTop(0);
		}


        __autoresize = function (force) {
            var force = force || false;
            var _old = thismodal.main.height();

			if (typeof thismodal.main.scrollTop == 'function') {
				 thismodal.main.scrollTop(0);
			}

			if (typeof thismodal.main[0] != 'undefined') {



					 parent.mw.tools.modal.resize("#" + thismodal.main[0].id, false, mw.$('#settings-container').height() + 25, false);
					setTimeout(function () {
						var _new = thismodal.main.height();
						if (_new > _old || force) {
							parent.mw.tools.modal.center("#" + thismodal.main[0].id, 'vertical');
						}
					}, 400)





			}





        }




if (typeof thismodal.main[0] != 'undefined') {



    var toolbar = thismodal.main[0].querySelector('.mw_modal_toolbar');
    is_module_tml_holder = $(toolbar).find("#module-modal-settings-menu-holder");
    if (is_module_tml_holder.length == 0) {

        var dd = mwd.createElement('div');
        dd.id = 'module-modal-settings-menu-holder';
        dd.className = 'mw-presets-dropdown';
        $(toolbar).append(dd);
        /*******************************************************
         Do not delete !!! Module template: list and 'Crete Module Template'

         $(toolbar).append(dd);
         *******************************************************/


    }



    mw.module_preset_linked_dd_menu_show_icon  = function () {
        var toolbar = thismodal.main[0].querySelector('.mw_modal_toolbar');
        is_module_preset_tml_holder = $("#module-modal-preset-linked-icon",toolbar);

        if (is_module_preset_tml_holder.length == 0) {
            var linked_dd =  window.parent.mwd.createElement('div');
            linked_dd.id = 'module-modal-preset-linked-icon';
             linked_dd.style.display = "none";

            $(toolbar).prepend(linked_dd);

        };
        is_module_preset_tml_holder = window.parent.$("#module-modal-preset-linked-icon");
        <?php if($is_linked_mod){  ?>
        $("#module-modal-preset-linked-icon",toolbar).addClass('is-linked').show();
        <?php  }else { ?>
        $("#module-modal-preset-linked-icon",toolbar).removeClass('is-linked').hide();

        <?php  } ?>
    }


    $( document ).ready(function() {


    //   window.top.module_settings_modal_reference = thismodal;


        <?php if(is_array( $module_info)): ?>


        <?php $mod_adm =  admin_url('load_module:').module_name_encode($module_info['module']); ?>




        is_module_tml_holder = $(toolbar).find("#module-modal-settings-menu-holder");

        if (is_module_tml_holder.length > 0) {


            is_module_tml_holder.empty();

            var holder = mwd.createElement('div');
            holder.className = 'mw-module-presets-content';


            var html = ""
                + "<div id='module-modal-settings-menu-items' module_id='<?php print $params['id'] ?>' module_name='<?php print $module_info['module'] ?>'>"
                + "</div>"
                + "<hr>"
                + "<div id='module-modal-settings-menu-holder-2'>"
                + "<a href='<?php print $mod_adm  ?>'><?php _e("Go to admin"); ?></a></div>";




			window.parent.modal_preset_manager_html_placeholder_for_reload = function(){
			var modal_preset_manager_html_placeholder_for_reload_content = ""
                + "<div id='module-modal-settings-menu-items-presets-holder' module_id='<?php print $params['id'] ?>' module_name='<?php print $module_info['module'] ?>'>"
                + "</div>"




		/*

		here for popup
		mw_admin_edit_tax_item_popup_modal_opened = window.parent.mw.modal({
					content:   modal_preset_manager_html_placeholder_for_reload_content,
					title:     'Edit module presets',
					id:        'modal_preset_manager_html_placeholder_for_reload_pop'
				});
*/
                var presetsthismodalid  = thismodal.main[0].id;

                window.parent.module_settings_modal_reference_preset_editor_modal_id = presetsthismodalid;
                window.parent.module_settings_modal_reference_window = window;

//                var src = mw.settings.site_url + 'api/module?id=<?php //print $params['id'] ?>//&live_edit=true&module_settings=true&&type=editor/module_presets&autosize=true&module_id=<?php //print $params['id'] ?>//&module_name=<?php //print urlencode($params['module'])  ?>//';
//                var modal = window.parent.mw.tools.modal.frame({
//                    url: src,
//                    // width: 500,
//                    //height: $(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
//                    name: 'mw-module-presets-editor-front',
//                    title: 'Module presets',
//                    template: 'default',
//                    center: false,
//                    resize: true,
//                    draggable: true
//                });

              //  $('#module-modal-settings-menu-holder-open-presets').html('');



                // HERE FOR DROPDOWN
                  window.parent.$('#module-modal-settings-menu-holder-open-presets').html(modal_preset_manager_html_placeholder_for_reload_content);


                 window.parent.mw.load_module("editor/module_presets", '#module-modal-settings-menu-items-presets-holder');

				};
				var html = ""

                + "<div id='module-modal-settings-menu-content'>" +
                    "<a  href='javascript:window.parent.modal_preset_manager_html_placeholder_for_reload();void(0)'>Presets</a>" +

                "</div>"
                + "<div id='module-modal-settings-menu-holder-open-presets' onclick='void();'></div>"

            var btn = document.createElement('a');
				btn.className = 'mw-module-presets-opener';
                $(btn).on('click', function(){
                    $(this).parent().toggleClass('active');
                });

			var module_has_editable_parent = window.parent.$('#<?php print $params['id'] ?>');

			if(typeof(module_has_editable_parent[0]) != 'undefined' && window.parent.mw.tools.hasParentsWithClass(module_has_editable_parent[0],'edit')){
                $(holder).append(html);
                $(dd).prepend(btn);

                is_module_tml_holder.append(holder);

			}




        }

        window.parent.modal_preset_manager_html_placeholder_for_reload();
        mw.module_preset_linked_dd_menu_show_icon();
        <?php endif; ?>


    });



}

        $(window).load(function () {
if (typeof thismodal.main[0] != 'undefined') {

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
}

        });


    };


    $(window).load(function () {


        $(mwd.body).removeClass('mw-external-loading');
        $(mwd.body).ajaxStop(function () {
            $(mwd.body).removeClass('mw-external-loading');
        });

        addIcon();

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
                mw.notification.success('<?php _e('Settings are updated!'); ?>');
            }

            if (window.parent.mw != undefined && window.parent.mw.reload_module != undefined) {
                window.parent.mw.reload_module("#<?php print $params['id'] ?>");
            }

        }

        // mw.options.form('#settings-container');
    });
</script>
<script type="text/javascript">

    window.slowDownEventTime = null;
function slowDownEvent(e, el, call){
    clearTimeout(slowDownEventTime);
    slowDownEventTime = setTimeout(function () {
        call.call(el, e);
    },333)
}

function mw_option_save_rebind_form_fields(){

      mw.$(".mw_option_field").not('.mw-options-form-binded-custom').not('.mw-options-form-binded').on("change input", function (e) {

          slowDownEvent(e, this, function(){
              if($(this).hasClass('mw-options-form-binded-custom')){
                  return;
              }

              $(this).addClass('mw-options-form-binded');


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


              if (window.parent.mw.drag != undefined) {

                  var mod_body = window.parent.document.getElementById('<?php print $params['id'] ?>');
                  if(mod_body){


                      var body = window.parent.mw.drag.parseContent(mod_body).body;
                      var edits = body.querySelectorAll('.edit.changed');
                      var mod_edits = window.parent.mw.drag.collectData(edits);
                      if (!mw.tools.isEmptyObject(mod_edits)){

                          var mod_edits_save = window.parent.mw.drag.save(mod_edits);
                          window.parent.mw.drag.save(mod_edits_save)
                      }
                  }

              }


              $.ajax({
                  type: "POST",
                  url: mw.settings.site_url + "api/save_option",
                  data: o_data,
                  success: function () {



                      if (refresh_modules11 != undefined && refresh_modules11 != '') {
                          refresh_modules11 = refresh_modules11.toString();
                          if(!!mw.admin){
                              if(typeof(top.mweditor) != 'undefined'  && typeof(top.mweditor) == 'object'   && typeof(top.mweditor.contentWindow) != 'undefined'){
                                  setTimeout(function(){

                                      top.mweditor.contentWindow.mw.reload_module('#<?php print $params['id'] ?>')
                                  }, 777);
                              }

                          }
                          if (window.parent.mw != undefined) {

                              if(self !== top){
                                  setTimeout(function(){

                                      var mod_element = window.parent.document.getElementById('<?php print $params['id'] ?>');
                                      if(mod_element){
                                          // var module_parent_edit_field = window.parent.mw.tools.firstParentWithClass(mod_element, 'edit')
                                          var module_parent_edit_field = window.parent.mw.tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]'])
                                          if(module_parent_edit_field){
                                              window.parent.mw.tools.addClass(module_parent_edit_field, 'changed');
                                              window.parent.mw.askusertostay = true;

                                          }
                                      }

                                      mw.reload_module_parent('#<?php print $params['id'] ?>');
                                  }, 777);
                              }

                              if (window.parent.mw.reload_module != undefined) {
                                  if(!!mw.admin){
                                      setTimeout(function(){
                                          window.parent.mw.reload_module('#<?php print $params['id'] ?>');
                                      }, 777);
                                  }
                                  else{
                                      if (window.parent.mweditor != undefined) {
                                          window.parent.mweditor.contentWindow.mw.reload_module('#<?php print $params['id'] ?>', function(){
                                              setTimeout(function(){
                                                  window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                              }, 777);
                                          });
                                      }
                                      if (window.parent.mw != undefined) {
                                          window.parent.mw.reload_module('#<?php print $params['id'] ?>', function(){
                                              setTimeout(function(){
                                                  window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                              }, 777);
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
                                      setTimeout(function(){

                                          window.mw.reload_module(also_reload, function(){

                                              mw_option_save_rebind_form_fields()
                                          });


                                          if(self !== top){
                                              mw.reload_module_parent(also_reload);
                                          }
                                      }, 777);
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


}




$( frame ).on('unload',function() {
     window.parent.$('#module-modal-settings-menu-holder').remove();

});
    $(document).ready(function () {



	mw_option_save_rebind_form_fields()

	});
</script>
</body>
</html>
