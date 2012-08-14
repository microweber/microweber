 if (window.console != undefined) {
     console.log('Microweber Javascript Framework Loaded');
 }

 /*
  * Microweber - Javascript Framework
  * 
  * Copyright (c) Mass Media Group (www.ooyes.net) Licensed under the Microweber
  * license http://microweber.com/license
  * 
  */

 window.mw = window.mw ? window.mw : {};



 mw.module = function ($vars, $update_element) {

     $.ajax({
         url: '{SITE_URL}api/module',
         type: "POST",
         data: ($vars),
         async: false,

         success: function (resp) {
             $($update_element).html(resp);

             if ($vars.callback != undefined) {
                 $vars.callback.call(this);

             }

         }
     });
 }

 mw.load_module = function ($module_name, $update_element) {

     var attributes = {};
     attributes.module = $module_name;

     url1 = '{SITE_URL}api/module';
     $($update_element).load(url1, attributes, function () {
         mw.settings.sortables_created = false;
     });


 }

 mw.load_layout_element = function ($layout_element_name, $update_element) {

     var attributes = {};
     attributes.element = $layout_element_name;

     url1 = '{SITE_URL}api/content/load_layout_element';
     $($update_element).load(url1, attributes, function () {
         mw.settings.sortables_created = false;
     });
 }

 // mw.reload_module_interval('custom_fields/');
 mw.reload_module_interval = function ($module_name, $interval) {
     if ($interval == undefined) {
         $interval = 500;
     }

     $interval = parseInt($interval);






     t_reload_module_interval = setInterval("mw.reload_module('" + $module_name + "')", $interval);





 }

 mw.reload_module = function ($module_name) {


     if ($module_name == undefined) {

     } else {
         var module_name = $module_name.toString();
         refresh_modules_explode = module_name.split(",");
         //	alert(refresh_modules_explode);
         for (var i = 0; i < refresh_modules_explode.length; i++) {
             var $module_name = refresh_modules_explode[i];




             if ($module_name != undefined) {
                 //	$("div.module").each(
                 //$("div.module[mw_params_module='"+$module_name+"']").each(
                 //  $mods = $("div.module");

                 $mods = $(".module[data-type='" + $module_name + "']", '.edit');
                 if ($mods.length == 0) {
                     $mods = $($module_name);
                     //alert('cant_reload_mod' + $module_name)	;
                 }

                 $mods.each(

                 function () {

                     var mw_params_module = $(this).attr("data-module");
                     var mw_params_module_id = $(this).attr("module_id");
                     if (mw_params_module != undefined) {
                         mw_params_module = mw_params_module.replace(/\\/g, "/");
                     } else {
                         mw_params_module = $module_name;
                     }
                     //$all_attr = 	 $.getAttributes('#foo'), true );
                     //$all_attr =  $(this).getAttributes();

                     mw_params_module_no_adm = mw_params_module.replace(/admin/i, "").replace(/^\/|\/$/g, '');


                     var attributes = {};



                     $.each(this.attributes, function (index, attr) {
                         attributes[attr.name] = attr.value;



                     });


                     $all_attr = attributes;
                     if (window.console != undefined) {
                         console.log(mw_params_module_no_adm);
                         console.log($module_name);
                         console.log(mw_params_module);

                     }



                     if (mw_params_module == $module_name || mw_params_module_id == $module_name || mw_params_module_no_adm == $module_name) {
                         // encoded = $(this).attr("data-params-encoded");
                         elem = $(this);



                         url1 = mw.settings.site_url + 'api/module/index/reload_module';
                         elem.load(url1, $all_attr, function () {
                             mw.settings.sortables_created = false;
                         });




                     }

                 });

             }

         }
         if (typeof init_edits == 'function') {
             //	 mw.settings.sortables_created = false;
             // init_edits(); 
         }

         //	 $('.mw').trigger('mw_module_reloaded', [$module_name]);

     }

 }

 mw.clear_cache = function () {
     $.ajax({
         url: '{SITE_URL}ajax_helpers/clearcache',
         type: "POST",
         success: function (resp) {

         }
     });
 }