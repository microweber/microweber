

      <label class="mw-ui-label">Site Background</label>


      <label class="mw-ui-label-small">Patterns</label><hr>


      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern1.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern2.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern3.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern4.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern5.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern6.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern7.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern8.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern9.png); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern10.png); "></span>

      <div class="vSpace"></div>

      <label class="mw-ui-label-small">Images</label><hr>


      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img1.jpg); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img2.jpg); "></span>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img3.jpg); "></span>


      <input type="hidden"
             class="tpl-field"
             data-selector="body"
             value = "<?php if(isset($arr['bodybg']) and isset($arr['bodybg']['value'])){ print $arr['bodybg']['value']; } ?>"
             name="bodybg"
             id="bodybg"
             data-property="background-image" />



      <div class="vSpace"></div><hr>

      <label class="mw-ui-label">Header/Top Color</label>

      <span class="pattern bgc" style="background-color: transparent;text-decoration: line-through"></span>
      <span class="pattern bgc" style="background-color: #A0CE4E;"></span>
      <span class="pattern bgc" style="background-color: #9DB668;"></span>
      <span class="pattern bgc" style="background-color: #E9A825;"></span>
      <span class="pattern bgc" style="background-color: #67B7E1;"></span>
      <span class="pattern bgc" style="background-color: #F05858;"></span>
      <span class="pattern bgc" style="background-color: #E67FB9;"></span>
      <span class="pattern bgc" style="background-color: #9E9E9E;"></span>
      <span class="pattern bgc" style="background-color: #AB8B65;"></span>
      <span class="pattern bgc" style="background-color: #111111;"></span>
      <span class="pattern bgc" style="background-color: #EFECEC;"></span>


      <input type="hidden"
             class="tpl-field"
             data-selector="#header,#footer"
             value = "<?php if(isset($arr['headerbg']) and isset($arr['headerbg']['value'])){ print $arr['headerbg']['value']; } ?>"
             name="headerbg"
             id="headerbg"
             data-property="background-color" />





<style type="text/css">
    .pattern{
      float: left;
      display: block;
      border: 1px solid transparent;
      width: 20px;
      height: 20px;
      margin: 5px;
      background-color: transparent;
      background-repeat: no-repeat;
      background-position: center;
      background-size: 100% 100%;
      box-shadow: 0 0px 2px #ccc;
      cursor: pointer;
    }
    .pattern:hover, .pattern:focus{
        box-shadow: 1px 1px 2px #111;
    }
    .pattern.active{
        border-color: rgba(44, 121, 204, 1);
    }

</style>
<script type="text/javascript">

$(document).ready(function(){
    mw.$(".pattern.bgi").click(function(){
        if(!$(this).hasClass("active")){
            mw.$(".pattern.bgi").removeClass("active");
            $(this).addClass("active");
            mw.$("#bodybg").val(this.style.backgroundImage);
            mw.tpl.save();
        }
    });
    mw.$(".pattern.bgc").click(function(){
        if(!$(this).hasClass("active")){
            mw.$(".pattern.bgc").removeClass("active");
            $(this).addClass("active");
            mw.$("#headerbg").val(this.style.backgroundColor);
            mw.tpl.save();
        }
    });
});



</script>

