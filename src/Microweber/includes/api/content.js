mw.content = mw.content || {
      publish : function($id) {
            master = {};
            master.id= $id;
      	  $.ajax({
              type: 'POST',
              url: mw.settings.site_url + 'api/content/set_published',
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
      },
      unpublish : function($id) {
      	master = {};
      	master.id= $id;
      	  $.ajax({
              type: 'POST',
              url: mw.settings.site_url + 'api/content/set_unpublished',
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
      },
      save:function(data, e){
          var master = {};
          var calc = {};
          var e = e || {};
      //   data.subtype === 'category'
          if(data.content == "" || typeof data.content === 'undefined'){
           // calc.content = false;
          }
          if(data.title == "" || typeof data.title === 'undefined'){
            calc.title = false;
          }
          if(!mw.tools.isEmptyObject(calc)){
            if(typeof e.onError === 'function'){
                e.onError.call(calc);
            }
            return false;
          }
          if(typeof data.content_type === "undefined" || data.content_type == ""){
            data.content_type = "post";
          }
          if(typeof data.id === "undefined" || data.id == ""){
            data.id = 0;
          }
          master.title = data.title;
          master.content = data.content;
          $.ajax({
              type: 'POST',
              url: mw.settings.site_url + 'api/save_content',
              data: data,
              datatype: "json",
              async: true,
              success: function(data) {
                if(typeof data === 'object' && typeof data.error != 'undefined'){
                   if(typeof e.onError === 'function'){
                      e.onError.call(data);
                   }
                }
                else{
                   if(typeof e.onSuccess === 'function'){
                      e.onSuccess.call(data);
                    }
                }
              },
              error:function(data){
                if(typeof e.onError === 'function'){
                  e.onError.call(data);
                }
              }
            });
      }
};