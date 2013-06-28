

$(document).ready(function(){


$(mwd.body).bind("click", function(e){
    var target = e.target;
    if(!mw.tools.hasParentsWithClass(target, "mw-search-autocomplete")){
        mw.$(".mw-search-results").hide();
    }
});




});   // end document ready