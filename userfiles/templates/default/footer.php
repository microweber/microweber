


<div id="footer">

    <div class="container">
        <div class="row">
            <div class="span5" id="footer-social">
                 <h3 class="pull-left">Be Social</h3>
                 <a href="http://facebook.com/Microweber" target="_blank" class="icon fb"></a>
                 <a href="http://twitter.com/Microweber" target="_blank" class="icon tw"></a>
                 <a href="http://youtube.com/Microweber" target="_blank" class="icon yt"></a>
            </div>
        </div>
        <hr>
        <div id="footer-bottom">
          <address class="pull-left">
              <span rel="footer" field="copyright" class="edit">Copyright &copy; <span class="unselectable" contentEditable="false"><?php print date('Y'); ?></span>, All rights reserved
              </span>
          </address>
          <span class="muted pull-right">Powered by <a class="muted" title="Microweber - Drag and Drop Content Management System" href="http://microweber.com" target="_blank">Microweber</a> (<a class="MW" href="http://microweber.com" target="_blank">MW</a>) <a href="http://microweber.com" target="_blank">Make Web</a></span>
        </div>
    </div>
</div>



<script>
$(window).load(function(){
   $("body").prepend('<span id="RENDERED" style="position:fixed;top:50%;right:-220px;padding:5px 10px;display:block;background:#C8D4EA;box-shadow:0 0 5px #000;width:180px;">Rendered in '+((new Date().getTime()-START)/1000) + 'sec.</span>');
   setTimeout(function(){  $("#RENDERED").animate({right:0}, 500, function(){
     setTimeout(function(){    $("#RENDERED").animate({right:-220}, 500);
          }, 1000);    });    }, 1000); });
</script>
</body>
</html>