<?php

/*

  type: layout
  content_type: static
  name: Download
  description: Download

*/

?>


<?php include THIS_TEMPLATE_DIR . "header.php"; ?>



<div class="main" >


  <div class="container">

    <div class="text-center" id="download-container">

        <a class="fbtn fbtn-large fitem-orange" title="Download" href="#" >DOWNLOAD  <small>v. 1.02</small></a>

        <p class="vpad"><a href="javascript:;" class="blue">Or <u>Download</u> the web installer</a></p>

    </div>

  </div>


</div>

<script>

$(document).ready(function(){

    var top = $(window).height() - mw.$("#header").height()
    mw.$("#content").css("minHeight", top);
    mw.$("#download-container").css("paddingTop", (top/2) - 90 - 45);

});

$(window).bind("resize", function(){
    var top = $(window).height() - mw.$("#header").height()
    mw.$("#content").css("minHeight", top);
    mw.$("#download-container").css("paddingTop", (top/2) - 90 - 45);
});

</script>




<?php include "footer.php"; ?>

