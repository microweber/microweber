
<style>
#my-colors{
  width: 235px;
  max-height: 90px;
  border: 1px solid #ccc;
  overflow-x:hidden;
  overflow-y:auto;
}

#my-colors span{
  display: block;
  float: left;
  width: 20px;
  height: 20px;
  margin: 5px;
  cursor: pointer;
  box-shadow: 1px 1px 1px #ccc;
}

#main_holder{
  position: relative;
  padding: 10px;
  margin: 0 10px 10px;
  background: 12px;
  overflow: hidden;
  box-shadow: 2px 2px 1px #ccc;
}

#mwpicker{
  clear: both;
  position: relative;
width: 240px; height: 130px;
}

</style>


	<script type="text/javascript" src="<? print pathToURL(dirname(__FILE__)); ?>/jscolor.js?v=<?php print uniqid(); ?>"></script>
    <script type="text/javascript">

    visible = false;

    toggle_picker = function(){
      var p = $('#mwpicker');
      if(visible){
          visible = false;
          p.hide();
          parent.mw.$(".wysiwyg_external iframe").height(100);
      }
      else{
        visible = true;
        p.show();
        parent.mw.$(".wysiwyg_external iframe").height(360);
      }
    }


        var command = window.location.hash.replace("#", "");
        $(document).ready(function(){



        color_holder = mwd.getElementById('my-colors');
        document_colors = {};

        parent.mw.$(".edit *").each(function(){
          var css = window.getComputedStyle(this, null);
          ! document_colors[css.color] ? document_colors[css.color] = css.color : '';
          ! document_colors[css.backgroundColor] ? document_colors[css.backgroundColor] = css.backgroundColor : '';
        });


        for(var x in document_colors){
            var span = mwd.createElement('span');
            span.style.background = document_colors[x];
            color_holder.appendChild(span);
        }




          $(document.body).mouseenter(function(){
            parent.mw.wysiwyg.save_selected_element();
          });
          $(document.body).mouseleave(function(){
            parent.mw.wysiwyg.deselect_selected_element();
          });


          /*
          mw.$('#colorpicker').ColorPicker({
            flat: true,
            onChange:function(hsb, hex){
              parent.mw.wysiwyg[command](hex);
            },
            onSubmit:function(hsb, hex){
              parent.mw.wysiwyg[command]("transparent");
            }
          });
                 */


        var input = mwd.getElementById('colorpicker');

        picker = new jscolor.color(input);

        picker.showPicker();

        });
    </script>

<div id="main_holder">

    <div id="my-colors">

    </div>




    <input type="hidden" id="colorpicker" />

    <a href="javascript:;" onclick="toggle_picker();" class="ed_btn">More</a>

    <div id="mwpicker" style="display: none"></div>

</div>
