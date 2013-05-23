<iframe src="http://microweber.com/frames/help.php" frameborder="0" id="helpframe" style="visibility: hidden;position: absolute;top: 0;left:0;"></iframe>

<script>

$(document).ready(function(){
   $("#helpframe").width($(window).width()).height($(window).height()).visible();

});

$(window).resize(function(){
   $("#helpframe").width($(window).width()).height($(window).height())
});

</script>