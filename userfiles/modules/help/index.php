<style>

body, html{
  overflow: hidden;
}

#footer{
  position: fixed !important;
  right: 20px;
}

</style>


<iframe src="http://microweber.com/frames/help.php" frameborder="0" id="helpframe" style="visibility: hidden;position: absolute;top: 48px;left:0;"></iframe>

<script>

$(document).ready(function(){
   $("#helpframe").width($(window).width()).height($(window).height()-48).visible();

});

$(window).resize(function(){
   $("#helpframe").width($(window).width()).height($(window).height()-48)
});

</script>