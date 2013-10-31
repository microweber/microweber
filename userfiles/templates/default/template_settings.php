<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=greek,latin,cyrillic-ext,latin-ext,cyrillic" rel="stylesheet">


   

      <label class="mw-ui-label">Default Font</label>


      <input type="hidden"
             class="tpl-field"
             data-selector="body, a"
             value = "<?php if(isset($arr['font']) and isset($arr['font']['value'])){ print $arr['font']['value']; } ?>"
             name="font"
             id="font"
             data-property="font-family" />



<div title="Template Font" id="font_family" class="mw_dropdown mw_dropdown_type_navigation">
    <span class="mw_dropdown_val_holder">
        <span class="mw_dropdown_val" style="width: 130px;">Select</span>
    </span>
    <div class="mw_dropdown_fields" style="left: 0px;">
      <ul>
          <li value="Open Sans"><a style="font-family: Open Sans" href="#">Open Sans</a></li>
          <li value="Arial"><a style="font-family: Arial" href="#">Arial</a></li>
          <li value="Tahoma"><a style="font-family: Tahoma" href="#">Tahoma</a></li>
          <li value="Verdana"><a style="font-family: Verdana" href="#">Verdana</a></li>
          <li value="Georgia"><a style="font-family: Georgia" href="#">Georgia</a></li>
          <li value="Segoe UI, Arial, sans-serif"><a style="font-family: Segoe UI, Arial, sans-serif" href="#">Segoe UI</a></li>
          <li value="Times New Roman"><a style="font-family: Times New Roman" href="#">Times New Roman</a></li>
      </ul>
    </div>
</div>

<div class="vSpace"></div><hr>




      <label class="mw-ui-label">Site Background</label>


      <div style="padding-left:20px">
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
      </div>

      <div class="vSpace"></div>
      <div style="padding-left:20px">
        <label class="mw-ui-label-small">Images</label><hr>
        <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img1.jpg); "></span>
        <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img2.jpg); "></span>
        <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img3.jpg); "></span>
        <span class="mw-ui-btn mw-ui-btn-small" id="uploader">Upload Image</span>
      </div>

      <input type="hidden"
             class="tpl-field"
             data-selector="body"
             value = "<?php if(isset($arr['bodybg']) and isset($arr['bodybg']['value'])){ print $arr['bodybg']['value']; } ?>"
             name="bodybg"
             id="bodybg"
             data-property="background-image" />



      <div class="vSpace"></div><hr>

      <label class="mw-ui-label">Color Schemes</label>

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
             data-selector="h1,h2,h3,h4,h5,h6,.btn,h1 a,h2 a,h3 a,h4 a,h5 a,h6 a"
             value = "<?php if(isset($arr['headerbg']) and isset($arr['headerbg']['value'])){ print $arr['headerbg']['value']; } ?>"
             name="headerbg"
             id="headerbg"
             data-property="color" />







<style type="text/css">
    .pattern{
      float: left;
      display: block;
      border: 1px solid transparent;
      width: 27px;
      height: 27px;
      margin: 5px;
      background-color: transparent;
      background-repeat: no-repeat;
      background-position: center;
      background-size: 100% 100%;
      box-shadow: 0 0px 2px #ccc;
      cursor: pointer;
    }
    .pattern:hover, .pattern:focus{
        box-shadow: 1px 1px 2px #8A8A8A;
    }
    .pattern.active{
        border-color: rgba(44, 121, 204, 1);
    }

    #uploader{
      float: left;
      margin-left: 7px;
      margin-top: 7px;
    }

</style>
<script>
  mw.require("files.js");
  mw.require("<?php print INCLUDES_URL; ?>css/wysiwyg.css");
</script>
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

    var uploader = mw.files.uploader({
      filetypes:'images',
      multiple:false
    });
    mw.$("#uploader").append(uploader);

    $(uploader).bind("FileUploaded", function(a,b){
            mw.$(".pattern.bgi").removeClass("active");
            mw.$("#bodybg").val(b.src);
            mw.tpl.save();
    });
    mw.tools.dropdown();

    mw.$("#font_family").bind("change", function(){
        var val = $(this).getDropdownValue();
        mw.$("#font").val(val);
        mw.tpl.save();
    });

  mw.$("#mw_set_template_style").bind("change", function(){
	  
	   var val = $(this).val();
        mw.$("#import_css").val('url("'+val+'")');
		
		
		
         mw.tpl.save();
    });

    mw.$("#font_family").setDropdownValue(mw.$("#font").val());





});
</script>

   <select id="mw_set_template_style">
	  <option value="css/styles/spacelab.css">spacelab</option>
	  <option value="css/styles/superhero.css">superhero</option>
	  	  <option value="css/styles/amelia.css">superhero</option>

	  </select>
	  
	  <input type="text"
             class="tpl-field"
             data-selector="@import"
             value = "<?php if(isset($arr['import']) and isset($arr['import']['value'])){ print $arr['import']['value']; } ?>"
             name="import"
             id="import_css"
             />


<module type="content/layout_selector_custom_css" template=""<?php print template_name(); ?>