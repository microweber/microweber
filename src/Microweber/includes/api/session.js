mw.session = {
  checkPause:false,
  check:function(callback){
    if(!mw.session.checkPause){
        mw.session.checkPause = true;
        $.post(mw.settings.api_url + "is_logged", function(data){
          if(data != false){
            callback.call(undefined, true);
          }
          else{
            callback.call(undefined, false);
          }
          mw.session.checkPause = false;
        });
    }
  },
  logRequest:function(){
    var modal = mw.tools.modal.init({
       html: "<h3 style='margin:0;'>"+mw.msg.session_expired+".</h3> <p style='margin:0;'>"+mw.msg.login_to_continue+".</p> <br> <div id='session_popup_login'></div>",
       id:"session_modal",
       name:"session_modal",
       overlay:true,
       width:400,
       height:300,
       template:'mw_modal_basic',
       callback:function(){
         mw.load_module("users/login", '#session_popup_login', false, {template:'popup'});
       }
    });
  },
  checkInit:function(){
    setInterval(function(){
      mw.session.check(function(is_logged){
        if(is_logged){
          mw.$("#session_modal").remove();
          mw.$(".mw_overlay").remove();
        }
        else{
          mw.session.logRequest();
        }
      });
    }, 30000);
  }
}

