// JavaScript Document

mw.options = {};

mw.options.form = function($selector){
   
   
   
   

mw.$(""+$selector+" .mw_option_field").bind("change", function () {

                var refresh_modules11 = $(this).attr('data-refresh');

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).attr('data-reload');
				}

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
                    var refresh_modules11 = '#'+refresh_modules11;
				}

				 var mname = $(this).parents('.module:first').attr('data-type');


				var og = $(this).attr('data-module-id');
				if(og == undefined){
				    var og = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module')
				}
				
				
				if(og == undefined){
				    var og = $(this).attr('data-refresh');
				}


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
				if(mname != undefined){
					o_data.module = mname;
				}


                $.ajax({

                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function () {
                        if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()

                            if (window.mw != undefined) {
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

   
   
   
   
   
}

