
 mw.content = mw.content || {
      publish : function($id) {
            var master = {};
            master.id= $id;
            $(mwd.body).addClass("loading");
            mw.drag.save();
            $.ajax({
                    type: 'POST',
                    url: mw.settings.site_url + 'api/content/set_published',
                    data: master,
                    datatype: "json",
                    async: true,
                    beforeSend: function() {

                    },
                    success: function(data) {
                      $(mwd.body).removeClass("loading");
            			$('.mw-set-content-publish').hide();
                        $('.mw-set-content-unpublish').fadeIn();
                        mw.askusertostay = false;
            		    mw.notification.success("Content is Published.");
                    },
                    error:function(){
                        $(mwd.body).removeClass("loading");
                    },
                    complete:function(){
                      $(mwd.body).removeClass("loading");
                    }
            });
      },
      unpublish : function($id) {
      	var master = {};
      	master.id= $id;
        $(mwd.body).addClass("loading");

        mw.drag.save();
      	$.ajax({
              type: 'POST',
              url: mw.settings.site_url + 'api/content/set_unpublished',
              data: master,
              datatype: "json",
              async: true,
              beforeSend: function() {

              },
              success: function(data) {
                $(mwd.body).removeClass("loading");
                  $('.mw-set-content-unpublish').hide();
                  $('.mw-set-content-publish').fadeIn();
                  mw.askusertostay = false;
      		      mw.notification.warning("Content is Unpublished.");
              },
              error:function(){
                  $(mwd.body).removeClass("loading");
              },
              complete:function(){
                $(mwd.body).removeClass("loading");
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
          else{
              var doc = mw.tools.parseHtml(data.content);
              var all = doc.querySelectorAll('[contenteditable]'), l=all.length, i=0;
              for( ; i<l; i++ ){
                all[i].removeAttribute('contenteditable');
              }
              data.content = doc.body.innerHTML;
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
          $(mwd.body).addClass("loading");
          mw.trigger('adminSaveStart');
          $.ajax({
              type: 'POST',
			  url: mw.settings.api_url + 'save_content_admin',
              data: data,
              datatype: "json",
              async: true,
              success: function(data) {
                $(mwd.body).removeClass("loading");
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
                $(mwd.body).removeClass("loading");
                if(typeof e.onError === 'function'){
                  e.onError.call(data);
                }
              },
              complete:function(){
                $(mwd.body).removeClass("loading");
              }
            });
      }
};


mw.post = mw.post || {
  del:function(a, callback){
    var arr = $.isArray(a) ? a : [a];
    var obj = {ids:arr}
    $.post(mw.settings.api_url + "content/delete", obj, function(data){
      typeof callback === 'function' ? callback.call(data) : '';
    });
  },
  publish:function(id, c){
    var obj = {
      id:id
    }
    $.post(mw.settings.api_url + 'content/set_published', obj, function(data){
        if(typeof c === 'function'){
          c.call(id, data);
        }
    });
  },
  unpublish:function(id, c){
    var obj = {
      id:id
    }
    $.post(mw.settings.api_url + 'content_set_unpublished', obj, function(data){
        if(typeof c === 'function'){
          c.call(id, data);
        }
    })
  },
  set:function(id, state, e){
    if(typeof e !== 'undefined'){
      e.preventDefault();
      e.stopPropagation();
    }
    if(state == 'unpublish'){
       mw.post.unpublish(id, function(data){
         mw.notification.warning(mw.msg.contentunpublished);
       });
    }
    else if(state == 'publish'){
        mw.post.publish(id, function(data){
            mw.notification.success(mw.msg.contentpublished);
            mw.$(".manage-post-item-" + id).removeClass("content-unpublished").find(".post-un-publish").remove();
            if(typeof e !== 'undefined'){
              $(e.target.parentNode).removeClass("content-unpublished");
              $(e.target).remove();
            }
       });
    }
  }
}