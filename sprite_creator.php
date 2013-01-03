<br /><br /><br /><br /><br />

<div id="h" style="width: 300px;"></div>
<br /><br /><br /><br />
<div id="v" style="height: 300px"></div>

<input type="text" style="position: absolute;bottom: 200px;right: 100px;padding: 10px;width: 500px;" id="yyy" />
<script>

mw.require("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery.ui.all.css");

</script>
<style>.kkk{
  outline: 1px solid green;
}
</style>

<script>

$(document).ready(function(){

$(".ico").click(function(){
  $(".ico").removeClass("kkk");$(this).addClass("kkk");
})


$("#h").slider({
  min:-1130,
  max:0,
  slide:function(event, ui ){
    var v = $("#v").slider("value");
    $(".kkk").css("backgroundPosition", ui.value+"px "+ v + "px");
    var t = "." + $(".kkk")[0].className.replace(/kkk/g, '').replace(/ico/g, '').replace(/\s/g, '');
    $("#yyy").val(t + "{background-position:" + ui.value+"px "+ v + "px;}");
  }
})
$("#v").slider({
  orientation: "vertical",
  min:-152,
  max:0,
  slide:function(event,ui){
    var h = $("#h").slider("value");
    $(".kkk").css("backgroundPosition", h+"px "+ ui.value + "px");
    var t = "." + $(".kkk")[0].className.replace(/kkk/g, '').replace(/ico/g, '').replace(/\s/g, '');
    $("#yyy").val(t + "{background-position:" + h+"px "+ ui.value + "px;}");
  }
})

});

</script>


<br /><br /><br /><br /><br />