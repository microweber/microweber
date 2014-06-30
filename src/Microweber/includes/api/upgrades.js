window.onmessage = function (e) {

//    if ( e.origin !== "http://html5demos.com" ) {
//        return;
//    }

    if (typeof e.data != 'undefined') {
       // alert(e.origin + e.data.download);

        $.post( mw.settings.api_url+"mw_install_market_item", e.data)
            .done(function( data ) {
                alert( "Data Loaded: " + data );
            });








    }


    // document.getElementById("test").innerHTML = e.origin + " said: " + e.data;
};


mw.upgrades = mw.upgrades || {
    add: function ($id) {
        alert($id);
    }
}