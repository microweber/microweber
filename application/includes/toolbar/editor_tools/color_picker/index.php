    <link rel="stylesheet" href="<? print pathToURL(dirname(__FILE__)); ?>/css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="<? print pathToURL(dirname(__FILE__)); ?>/css/layout.css" />
	<script type="text/javascript" src="<? print pathToURL(dirname(__FILE__)); ?>/js/colorpicker.js"></script>
    <script type="text/javascript">
        var command = window.location.hash.replace("#", "");
        $(document).ready(function(){

          $(document.body).mouseenter(function(){
            parent.mw.wysiwyg.save_selected_element();
          });
          $(document.body).mouseleave(function(){
            parent.mw.wysiwyg.deselect_selected_element();
          });
          $('#colorpicker').ColorPicker({
            flat: true,
            onChange:function(hsb, hex){
              parent.mw.wysiwyg[command](hex);
            }
          });
        });
    </script><div id="colorpicker"></div>
