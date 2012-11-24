$(document).ready(function(){





$(window).resize(function(){
  set_main_height()
});


});


$(window).load(function(){
      set_main_height()
});


set_main_height = function(){
  mw.$("#mw-admin-container").css("minHeight", $(window).height()-41)
}

