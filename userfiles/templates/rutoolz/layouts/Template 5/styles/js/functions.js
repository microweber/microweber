$(document).ready(function(){

    $(".submit").click(function(){
       $(this).parents("form").find("input[type=submit]").click();
       return false;
    });
    var boxMax = 0;
    $(".box-content").each(function(){
    	if ($(this).height() > boxMax) { boxMax = $(this).height(); }
    });
    $(".box-content").css("height", boxMax);


});



