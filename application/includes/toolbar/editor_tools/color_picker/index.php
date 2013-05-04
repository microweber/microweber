
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
  box-shadow: 0px 0px 2px #ccc;
}
#my-colors span:hover{
  box-shadow: 0px 0px 2px #999;
}

#main_holder{
  position: relative;
  padding: 10px;
  margin: 0 10px 10px;
  background: white;
  overflow: hidden;
  box-shadow: 0px 0px 6px #CCCCCC;
}

#mwpicker{
  clear: both;
  position: relative;
  width: 240px; height: 130px;
}







</style>


	<script type="text/javascript" src="<?php print pathToURL(dirname(__FILE__)); ?>/jscolor.js?v=<?php print uniqid(); ?>"></script>
    <script>
        parent.mw.require('external_callbacks.js');
        mw.require('color.js');
    </script>
    <script type="text/javascript">


        _command = window.location.hash.replace("#", "");

        _hide_selection = ['fontColor', 'fontbg'];



        $(document).ready(function(){


        if(_hide_selection.indexOf(_command)!=-1){
          $(parent.mwd.body).addClass('hide_selection');
        }


        color_holder = mwd.getElementById('my-colors');
        document_colors = {};

        parent.mw.$(".edit *").each(function(){
          var css = window.getComputedStyle(this, null);
          ! document_colors[css.color] ? document_colors[css.color] = css.color : '';
          ! document_colors[css.backgroundColor] ? document_colors[css.backgroundColor] = css.backgroundColor : '';
        });


        for(var x in document_colors){
            var span = mwd.createElement('span');
            var color = mw.color.rgbToHex(document_colors[x]);
            if(color != 'transparent'){
              span.style.background = color;
              span.setAttribute('onclick', '_do("'+color.replace(/#/g, '')+'");');
            }
            else{
              span.style.background = color;
              $(span).addClass("transparent");
              span.setAttribute('onclick', '_do("'+'transparent'+'");');
            }


            color_holder.appendChild(span);

        }




          $(document.body).mouseenter(function(){
            parent.mw.wysiwyg.save_selected_element();
          });
          $(document.body).mouseleave(function(){
            parent.mw.wysiwyg.deselect_selected_element();
          });





        var input = mwd.getElementById('colorpicker');

        picker = new jscolor.color(input);

        picker.showPicker();

        });

        _do = function(val){
          parent.mw.iframecallbacks[_command](val);
        }


    </script>

<div id="main_holder">

<label class="mw-ui-label">Colors used in this page</label>

    <div id="my-colors">

    </div>




    <input type="hidden" id="colorpicker" onchange="_do(this.value);" />
    <div class="vSpace"></div>
<label class="mw-ui-label">Custom color</label>



    <div id="mwpicker"></div>

</div>
