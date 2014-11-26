// JavaScript Document

/**
 *
 * Options API
 *
 * @package		js
 * @subpackage		options
 * @since		Version 0.567
 */

// ------------------------------------------------------------------------

/**
 * mw.options
 *
 *  mw.options object
 *
 * @package		js
 * @subpackage	options
 * @category	options internal api
 * @version 1.0
 */
mw.options = {
    saveOption:function(o, c, err){
      if(typeof o !== 'object'){ return false;}
      if((!o.group && !o.option_group)  || (!o.key && !o.option_key) || (!o.value && !o.option_value)){ return false; }
      var data = {
        option_group: o.group || o.option_group,
        option_key: o.key || o.option_key,
        option_value: o.value || o.option_value
      }
      $.ajax({
          type: "POST",
          url: mw.settings.site_url+"api/save_option",
          data: data,
          success:function(a){
            if(typeof c === 'function'){
              c.call(a);
            }
          },
          error:function(a){
            if(typeof err === 'function'){
              err.call(a);
            }
          }
      });
    },
    save:function(el, callback){

            	var el = el;

        		mw.extend(el);


			  	var also_reload = el.attr('data-also-reload');
 				var opt_id = el.attr('data-id');
                var refresh_modules11 = el.attr('data-refresh');

				if(og1 == undefined){
				    var og1 = el.attr('option-group');
				}

				if(og1 == undefined){
				    var og1 =  el.attr('option_group');
				}

				if(og1 == undefined){
				    var og1 =  el.attr('data-option-group');
				}
				//mw.log(modal);


				var refresh_modules12 = el.attr('data-reload');
				 if(refresh_modules12 == undefined){
				    var refresh_modules12 =  el.attr('data-refresh');
				}



				var also_reload = el.attr('data-reload');
				
				
				if(also_reload == undefined){
					var also_reload = el.attr('data-also-reload');
				}


                var modal = el.getModal().container;



				if(refresh_modules11 == undefined && modal!==undefined){
					mw.extend(modal);
				    var for_m_id = modal.attr('data-settings-for-module');

				}

                var a = ['data-module-id','data-settings-for-module','data-refresh','option-group','data-option-group'], i=0, l=a.length, og='';
         		var mname = modal!==undefined ? modal.attr('data-type'):undefined;

					if(refresh_modules11 == undefined){
					    for( ; i<l; i++){
	                  var og = og === undefined ? el.attr(a[i]) : og;
	                }
				} else {
					var og = refresh_modules11;
				}

				if(og1 != undefined){
					var og = og1;
					if(refresh_modules11 == undefined){
						if(refresh_modules12 == undefined){
							refresh_modules11 = og1;
							} else {
								refresh_modules11 = refresh_modules12;
							}
						}
				}
				
				if(og == null || og == ''){
				
				var og_test = mw.tools.firstParentWithClass(el, 'module');
				 og = og_test.id;
				 //alert(og);
				}
				
                if(el.type==='checkbox'){
                   var val = '';
                   var items = mwd.getElementsByName(el.name), i=0, len = items.length;
                   for( ; i<len; i++){
                       var _val = items[i].value;
                       var val = items[i].checked==true ? (val==='' ? _val: val+", "+_val) : val;
                   }
                }
                else{val = el.value }
				var o_data = {
                    option_key: el.attr('name'),
                    option_group: og,
                    option_value: val
                }
				if(mname !== undefined){
					o_data.module = mname;
				}


				if(for_m_id !== undefined){
					o_data.for_module_id = for_m_id;
				}
				 if(og != undefined){
				    o_data.id = have_id;
				}

				var have_id = el.attr('data-custom-field-id');

				if(have_id != undefined){
				    o_data.id = have_id;
				}

				var have_option_type = el.attr('data-option-type');

				if(have_option_type != undefined){
				    o_data.option_type = have_option_type;
				} else {
					var have_option_type = el.attr('option-type');

					if(have_option_type != undefined){
					    o_data.option_type = have_option_type;
					}
				}
				var reaload_in_parent = el.attr('parent-reload');
				
				if(opt_id !== undefined){


				o_data.id = opt_id;

				}


                $.ajax({
                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function (data) {


                        if(reaload_in_parent != undefined && reaload_in_parent !== null){
						// window.parent.mw.reload_module("#"+refresh_modules11);
	
						return false;	
						}
						
						
						if(also_reload != undefined){

							
							
						 

							if (window.mw != undefined && reaload_in_parent !== true) {
                                if (window.mw.reload_module !== undefined) {
 
									window.mw.reload_module(also_reload, function(reloaded_el){

										mw.options.form(reloaded_el, callback);
									});
									window.mw.reload_module('#'+also_reload, function(reloaded_el){

										mw.options.form(reloaded_el, callback);
									});
                                }
                            }

						}



                        if (reaload_in_parent !== true && for_m_id != undefined && for_m_id != '') {
                            for_m_id = for_m_id.toString()
                            if (window.mw != undefined) {
								
								
								
								
                                //if (window.mw.reload_module !== undefined) {
//	
//									window.mw.reload_module('#'+for_m_id, function(reloaded_el){
//
//										mw.options.form(reloaded_el, callback);
//									});
//                                }
                            }
                        } else if (reaload_in_parent !== true && refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()
							
							//mw.log(refresh_modules11);


                            if (window.mw != undefined) {
							
							 
								if(reaload_in_parent !== true){

                                    if(refresh_modules11 == refresh_modules12 ){
                                        mw.reload_module(refresh_modules11);
                                    }



                                if (window.mw.reload_module !== undefined) {

									 mw.reload_module_parent(refresh_modules11);
									   mw.reload_module_parent("#"+refresh_modules11);

                                }
								
								}
								
                            }
                        }
 
                         typeof callback === 'function' ?  callback.call(data) : '';

                    }
          });
    }
};

mw.options.form = function($selector, callback, beforepost){


        var callback = callback || '';
        var items = $($selector).find("input, select, textarea");
        items.each(function(){
          var item = $(this);

          item.removeClass('mw-options-form-binded');

          if(item.hasClass('mw_option_field') && !item.hasClass('mw-options-form-binded')){
              item.addClass('mw-options-form-binded');
			  item.addClass('mw-options-form-binded-custom');
			  //item.unbind("change");

              item.bind("change", function(e){
              	  if(typeof beforepost === 'function'){
              	  	beforepost.call(this);
              	  }
                  mw.options.save(this, callback);

              });
          }
        });
}

