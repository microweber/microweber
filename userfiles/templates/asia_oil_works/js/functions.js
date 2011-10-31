

$(document).ready(function(){


$(".dd").hover(function(){
   $(this).find("ol").show();
}, function(){
  $(this).find("ol").hide();
});


$(".dd ol").append("<li class='dd_last'>&nbsp;</li>")
$(".dd").hover(function(){
   $(this).addClass("dd_active");
}, function(){
  $(this).removeClass("dd_active");
});

$(".dd ol a").click(function(){
  var val = $(this).html();
   $(this).parents(".dd").find(".dd_val").html(val);
   $(this).parents("ol").hide();
});


$(document.body).mousedown(function(){
   if($(".dd_active").length==0){
        $(this).find("ol").hide();
   }
});

var c = $("#hcontent");
var s = $("#hsidebar");

c.height()<s.height()?c.height(s.height()):'';


$("#companies tr td:first-child").css({
  borderLeft: "none",
  paddingLeft: "10px"
});

$(".action-submit").click(function(){
  $(this).parents("form").submit();
  return false;
});

$("#results tr:even").addClass("ich");


$("#results td:nth-child(2n+2)").css({
  borderLeft: "1px dotted #DBDBDB",
  paddingLeft: "15px"
});
$("#results th:nth-child(2n+2)").css({
  paddingLeft: "15px"
});


});