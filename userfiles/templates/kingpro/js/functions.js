

$(document).ready(function(){


$(mwd.body).click(function(e){
    var target = e.target;
    if(!$(target).hasClass("mw-search-field")){
        mw.$(".mw-search-results").hide();
    }
});

mw.$(".mw-search-field").blur(function(e){
  setTimeout(function(){
      mw.$(".mw-search-results").hide();
  }, 222);
});


});   // end document ready