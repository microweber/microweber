
/*var mw_edit = document.createElement("edit");
 var mw_module = document.createElement("module");
 var mw_moduleedit = document.createElement("moduleedit");
 var mw_editblock = document.createElement("editblock");*/

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
MW = window.mw;
mw = window.mw;

mw.ready = function(elem, callback) {

	$(document).ready(function() {
		$(elem).each(function() {
			var el = $(this);
			if (!el.hasClass("exec")) {
				el.addClass("exec");
				callback.call(el);
			}
		});

		$(document.body).ajaxStop(function() {
			$(elem).each(function() {
				var el = $(this);
				if (!el.hasClass("exec")) {
					el.addClass("exec");
					callback.call(el);
				}
			});
		});

	});

}

mw.module = function($vars, $update_element) {

	$.ajax( {
		url : '{SITE_URL}api/module',
		type : "POST",
		data : ($vars),
		async : false,

		success : function(resp) {
			$($update_element).html(resp);

			if ($vars.callback != undefined) {
				$vars.callback.call(this);

			}

		}
	});
}

mw.reload_module = function($module_name) {
	
	
	if ($module_name == undefined) {

	} else {
		var module_name = $module_name.toString();
		refresh_modules_explode = module_name.split(",");
	//	alert(refresh_modules_explode);
		for ( var i = 0; i < refresh_modules_explode.length; i++) {
			var $module_name = refresh_modules_explode[i];

			
			
			
			if ($module_name != undefined) { 
			//	$("div.module").each(
				//$("div.module[mw_params_module='"+$module_name+"']").each(
				$("div.module").each(
 								function() {

									var mw_params_module = $(this).attr(	"mw_params_module");
									var mw_params_module_id = $(this).attr(	"module_id");
									
									mw_params_module = mw_params_module.replace(/\\/g,"/"); 
									
								//$all_attr = 	 $.getAttributes('#foo'), true );
									$all_attr =  $(this).getAttributes();
									
									if (mw_params_module != $module_name) {
										// var mw_params_module =
										// $(this).attr("id");
										// alert(mw_params_module);
									}

						 
									if (window.console != undefined) {
							 		//	console.log('Reload module   ' + mw_params_module  +mw_params_module_id + '  ' + $module_name);	
							 		}
									
									if (mw_params_module == $module_name || mw_params_module_id == $module_name) {
										var mw_params_encoded = $(this).attr(	"mw_params_encoded");
										var elem = $(this)
										
		
								 
										 url1= '{SITE_URL}api/module/index/reload_module:' + mw_params_encoded;
										 elem.load(url1,$all_attr,function() {
											 window.mw_sortables_created = false;
										 }); 
										 
										 
										 
										
										 
											

//										$.ajax( {
//													url : '{SITE_URL}api/module/index/reload_module:' + mw_params_encoded,
//													type : "POST",
//													data: $all_attr,
//													async : false,
//
//													success : function(resp) {
//												//	alert(resp);
//														//$(this).empty();
//											elem.before(resp).remove(); 
//													// elem.empty();
//													// elem.append(resp);
//
//												}
//												});

									}

								});

			}

		}
		 if(typeof init_edits == 'function') { 
		//	 window.mw_sortables_created = false;
			// init_edits(); 
			 }
		 
	//	 $('.mw').trigger('mw_module_reloaded', [$module_name]);

	}

}

mw.clear_cache = function() {
	$.ajax( {
		url : '{SITE_URL}ajax_helpers/clearcache',
		type : "POST",
		success : function(resp) {

		}
	});
}



