// JavaScript Document

mw.options = {
    save:function(el){
                var _el = $(el);
                var refresh_modules11 = el.attributes['data-refresh'].nodeValue;
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = _el.attr('data-reload');
				}





				if(refresh_modules11 == undefined){
				    var refresh_modules11 = '#' + mw.tools.firstParentWithClass(el, 'mw_modal_container').attributes['data-settings-for-module'].nodeValue;
				}
         		var mname = mw.tools.firstParentWithClass(el, 'module').attributes['data-type'].nodeValue;
				var og = _el.attr('data-module-id');
				var og = el.attributes['data-module-id'].nodeValue;
				if(og == undefined){
				    var og = _el.parents('.mw_modal_container:first').attr('data-settings-for-module');
				}
				if(og == undefined){
				    var og = _el.attr('data-refresh');
				}
				if(og == undefined){
				    var og = _el.attr('option-group');
				}
				if(og == undefined){
				    var og = _el.attr('data-option-group');
				}
                if(el.type==='checkbox'){
                   var val = '';
                   var items = mw.$('input[name="'+el.name+'"]');
                   for(var i=0; i<items.length; i++){
                       var _val = items[i].value;
                       var val = items[i].checked==true ? (val==='' ? _val: val+", "+_val) : val;
                   }
                }
                else{val = el.value }
				var o_data = {
                    option_key: _el.attr('name'),
                    option_group: og,
                    option_value: val
                }
				if(mname != undefined){
					o_data.module = mname;
				}
                $.ajax({
                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function (data) {
                        if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()
                            if (window.mw != undefined) {
                                if (window.mw.reload_module != undefined) {
                                    window.mw.reload_module(refresh_modules11);
									window.mw.reload_module('#'+refresh_modules11);
                                }
                            }

                        }
                        if(mw.is.func(callback)){
                          callback.call(data);
                        }
                    }
          });
    },//save
    noop:function(){}
};

mw.options.form = function($selector, callback){

   
   


mw.$($selector+" .mw_option_field").bind("change", function(){
          mw.options.save(this);

    });
}

