// JavaScript Document

mw.options = {
    save:function(el){

                mw.extend(el);
                var modal = el.getModal().container;

                var refresh_modules11 = el.attr('data-refresh');
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = el.attr('data-reload');
				}
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = '#' + modal.attr('data-settings-for-module');
				}
                var a = ['data-module-id','data-settings-for-module','data-refresh','option-group','data-option-group'], i=0, l=a.length, og='';
         		var mname = modal.attr('data-type');

                for( ;i<l;i++){
                  var og = og === '' ? el.attr(a[i]) : og;
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
                $.ajax({
                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function (data) {
                        if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()
                            if (window.mw != undefined) {
                                if (window.mw.reload_module !== undefined) {
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
    }
};

mw.options.form = function($selector, callback){
    mw.$($selector+" .mw_option_field").bind("change", function(){
          mw.options.save(this);
    });
}

