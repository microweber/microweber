
TempateFunctions = {
  contentHeight:function(){

    /**************************************
     Minimum height for the Main Container
    **************************************/

    var content = document.getElementById('content-holder'),
        footer = document.getElementById('footer'),
        header = document.getElementById('header');

    $(content).css('minHeight', $(window).height() - $(header).outerHeight(true) - $(footer).outerHeight(true));

  }
}


$(document).ready(function(){

    TempateFunctions.contentHeight();

});

$(window).bind('load resize', function(e){

    TempateFunctions.contentHeight();

});




