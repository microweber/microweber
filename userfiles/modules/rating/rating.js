mw.rating = {};
mw.rating.save = function(data){

    $.post( mw.settings.api_url+'rating/Controller/save', data)
        .done(function( data ) {
           // alert( "Data Loaded: " + data );
		   mw.notification.success( "Thank you for your rating");
        });


}




$(function () {
    return $(".starrr").starrr();
});

$(document).ready(function () {

    $('.starrr').on('starrr:change', function (e, value) {

        data = {};
        data.rel = $(this).data('rel');
        data.rel_id = $(this).data('rel-id');
        data.rating = value;
        mw.rating.save(data);
    });


});
