

mw.shipping_country = {
    delete_country : function(id){
        var q = "Are you sure you want to delete shipping to this country?";
        mw.tools.confirm(q, function(){
            var obj = {};
            obj.id = id;
            $.post(mw.shipping_country.url + "/shipping_to_country/delete",  obj, function(data){
              mw.$(".country-id-"+id).fadeOut();
              mw.reload_module('[data-parent-module="shop/shipping"]');
			   if(window.parent != undefined && window.parent.mw != undefined){
					window.parent.mw.reload_module('shop/shipping/gateways/country');
				}
            });
        });
    },
    add_country:function(){

    }
}