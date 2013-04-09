// JavaScript Document
if(mw.content == undefined){
mw.content = {};
}
mw.content.publish = function($id) {
	
	master = {};
	master.id= $id;
	
	
	  $.ajax({
        type: 'POST',
        url: mw.settings.site_url + 'api/content_set_published',
        data: master,
        datatype: "json",
        async: true,
        beforeSend: function() {

        },
        success: function(data) {
			$('.mw-set-content-publish').hide();
 $('.mw-set-content-unpublish').fadeIn();

          mw.askusertostay = false;
		    mw.notification.success("Content is Published.");
        }
      });
	
}
mw.content.unpublish = function($id) {
	
	master = {};
	master.id= $id;
	
	
	  $.ajax({
        type: 'POST',
        url: mw.settings.site_url + 'api/content_set_unpublished',
        data: master,
        datatype: "json",
        async: true,
        beforeSend: function() {

        },
        success: function(data) {
  $('.mw-set-content-unpublish').hide();
$('.mw-set-content-publish').fadeIn();

          mw.askusertostay = false;
		    mw.notification.warning("Content is Unpublished.");
        }
      });
	
}