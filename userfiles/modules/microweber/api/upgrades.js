window.onmessage = function (e) {
 
//    if ( e.origin !== "http://html5demos.com" ) {
//        return;
//    }
    if (typeof e.data != 'undefined') {
		 if (typeof e.data.market_id != 'undefined') {
			  $.post(mw.settings.api_url + "mw_install_market_item", e.data)
            .done(function (data) {
               mw.notification.msg(data,5000);
            });
		 }
     }
    // document.getElementById("test").innerHTML = e.origin + " said: " + e.data;
};


mw.upgrades = mw.upgrades || {
    add: function ($id) {
        alert($id);
    }
}