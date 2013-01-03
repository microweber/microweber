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
    save:function(el, callback){
 
           //    mw.extend(el);
			 
				var el = el;
				mw.extend(el);
				
				
			
 
                var refresh_modules11 = el.attr('data-refresh');
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = el.attr('data-reload');
				}
				
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = el.attr('data-reload');
				}
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = el.attr('option-group');
				}
				
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = el.attr('option_group');
				}
				
					if(refresh_modules11 == undefined){
				    var refresh_modules11 = el.attr('data-option-group');
				}
				//mw.log(modal);
				
				
					
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
				
                $.ajax({
                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function (data) {

						 if (for_m_id != undefined && for_m_id != '') {
                            for_m_id = for_m_id.toString()
                            if (window.mw != undefined) {
                                if (window.mw.reload_module !== undefined) {
                                    
									window.mw.reload_module('#'+for_m_id);
                                }
                            }
                        } else   if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()
                            if (window.mw != undefined) {
                                if (window.mw.reload_module !== undefined) {
                                    window.mw.reload_module(refresh_modules11);
									window.mw.reload_module('#'+refresh_modules11);
                                }
                            }
                        }

                         typeof callback === 'function' ?  callback.call(data) : '';

                    }
          });
    }
};

mw.options.form = function($selector, callback){
        var callback = callback || '';
        var items = mw.$($selector).find("input, select, textarea");
        items.each(function(){
          var item = $(this);
          if(!item.hasClass('mw-options-form-binded')){
              item.addClass('mw-options-form-binded');
              item.bind("change", function(){
                  mw.options.save(this, callback);
              });
          }
        });
}

