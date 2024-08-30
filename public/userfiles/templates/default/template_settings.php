<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=greek,latin,cyrillic-ext,latin-ext,cyrillic" rel="stylesheet">
<script>
          $(document).ready(function(){

          });
</script>
<script>
  mw.require("files.js");
  mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
</script>
<script type="text/javascript">
$(document).ready(function(){
    mw.$(".bgi").click(function(){
        if(!$(this).hasClass("active")){
            mw.$(".bgi").removeClass("active");
            $(this).addClass("active");
            mw.$("#bodybg").val(!this.className.contains("STYLERESET") ? this.style.backgroundImage : "reset");
            mw.tpl.save();
        }
    });
    mw.$(".bgc").click(function(){
        if(!$(this).hasClass("active")){
            mw.$(".bgc").removeClass("active");
            $(this).addClass("active");
            mw.$("#headerbg").val(!this.className.contains("STYLERESET") ? this.style.backgroundColor : "reset");
            mw.tpl.save();
        }
    });

    mw.$(".bgctextcolor").click(function(){
        if(!$(this).hasClass("active")){
            mw.$(".bgctextcolor").removeClass("active");
            $(this).addClass("active");
            mw.$("#textcolor").val(!this.className.contains("STYLERESET") ? this.style.backgroundColor : "reset");
            mw.tpl.save();
        }
    });

    mw.$(".bgclinkcolor").click(function(){
        if(!$(this).hasClass("active")){
            mw.$(".bgclinkcolor").removeClass("active");
            $(this).addClass("active");
            mw.$("#linkcolor").val(!this.className.contains("STYLERESET") ? this.style.backgroundColor : "reset");
            mw.tpl.save();
        }
    });


    var uploader = mw.files.uploader({
      filetypes:'images',
      multiple:false,
        element: mw.$("#uploader")
    });


    $(uploader).on("FileUploaded", function(a,b){
            mw.$(".pattern.bgi").removeClass("active");
            mw.$("#bodybg").val("url(" + b.src + ");");
            mw.tpl.save();
    });
    mw.tools.dropdown();

    mw.$("#font_family").on("change", function(){
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
<style >
.pattern {
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
.pattern:hover, .pattern:focus {
	box-shadow: 1px 1px 2px #8A8A8A;
}
.pattern.active {
	border-color: rgba(44, 121, 204, 1);
}
#uploader {
	float: left;
	margin-left: 7px;
	margin-top: 7px;
}
.STYLERESET {
	color: #666666;
	text-decoration: underline;
	font-size: 11px;
	cursor: pointer;
	display: inline-block;
	position: relative;
    clear: both;
    float: left;
}
.pattern + .STYLERESET {
	top: 8px;
	left: 5px;
}
</style>

    <label class="mw-ui-label">Default Font</label>
    <input type="hidden"
             class="tpl-field"
             data-selector="body, a"
             value = "<?php if(isset($data['font']) and isset($data['font']['value'])){ print $data['font']['value']; } ?>"
             name="font"
             id="font"
             data-property="font-family" />
    <div title="Template Font" id="font_family" class="mw-dropdown mw-dropdown_type_navigation"> <span class="mw-dropdown-value"> <span class="mw-dropdown-val" style="width: 130px;">Select</span> </span>
      <div class="mw-dropdown-content" style="left: 0px;">
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



    <?php

	$color_cheme = false;
	if(isset($data['import']) and isset($data['import']['value'])){
	$color_cheme = $data['import']['value'];
	}

	?>

   <?php /* <label class="mw-ui-label">Color scheme</label>
    <select id="mw_set_template_style" class="mw-ui-field">
      <option value="reset">None</option>
      <option <?php if(strstr($color_cheme,'spacelab')) : ?> selected="selected" <?php endif; ?> value="<?php print template_url(); ?>css/styles/spacelab.css">Spacelab</option>
      <option <?php if(strstr($color_cheme,'superhero')) : ?> selected="selected" <?php endif; ?> value="<?php print template_url(); ?>css/styles/superhero.css">Superhero</option>
      <option <?php if(strstr($color_cheme,'amelia')) : ?> selected="selected" <?php endif; ?> value="<?php print template_url(); ?>css/styles/amelia.css">Amelia</option>
    </select>
    <input type="hidden"
             class="tpl-field"
             data-selector="@import"
             value = "<?php print $color_cheme; ?>"
             name="import"
             id="import_css"
             />*/ ?>

    <label class="mw-ui-label">Site Background</label>
    <div style="padding-left:20px">
      <label class="mw-ui-label-small">Patterns</label>
      <hr>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern1.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern2.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern3.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern4.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern5.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern6.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern7.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern8.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern9.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern10.png); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/pattern11.png); "></span> <br />
<span class="bgi STYLERESET" style="">Reset</span> </div>

    <div style="padding-left:20px">
      <label class="mw-ui-label-small">Images</label>
      <hr>
      <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img1.jpg); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img2.jpg); "></span> <span class="pattern bgi" style="background-image: url(<?php print TEMPLATE_URL; ?>img/patterns/img3.jpg); "></span> <span class="mw-ui-btn mw-ui-btn-small" id="uploader">Upload Image</span> </div>
    <input type="hidden"
             class="tpl-field"
             data-selector="body"
             value = "<?php if(isset($data['bodybg']) and isset($data['bodybg']['value'])){ print $data['bodybg']['value']; } ?>"
             name="bodybg"
             id="bodybg"
             data-property="background-image" />

    <hr>
    <label class="mw-ui-label">Text Color</label>
    <span class="pattern bgctextcolor" style="background-color: #A0CE4E;"></span> <span class="pattern bgctextcolor" style="background-color: #9DB668;"></span> <span class="pattern bgctextcolor" style="background-color: #E9A825;"></span> <span class="pattern bgctextcolor" style="background-color: #67B7E1;"></span> <span class="pattern bgctextcolor" style="background-color: #F05858;"></span> <span class="pattern bgctextcolor" style="background-color: #E67FB9;"></span> <span class="pattern bgctextcolor" style="background-color: #9E9E9E;"></span> <span class="pattern bgctextcolor" style="background-color: #AB8B65;"></span> <span class="pattern bgctextcolor" style="background-color: #111111;"></span> <span class="pattern bgctextcolor" style="background-color: #EFECEC;"></span> <span class="bgctextcolor STYLERESET" style="">Reset</span>
    <input type="hidden"
             class="tpl-field"
             data-selector="body,p"
             value = "<?php if(isset($data['textcolor']) and isset($data['textcolor']['value'])){ print $data['textcolor']['value']; } ?>"
             name="textcolor"
             id="textcolor"
             data-property="color" />

    <hr>
    <label class="mw-ui-label">Link Color</label>
    <span class="pattern bgclinkcolor" style="background-color: #A0CE4E;"></span> <span class="pattern bgclinkcolor" style="background-color: #9DB668;"></span> <span class="pattern bgclinkcolor" style="background-color: #E9A825;"></span> <span class="pattern bgclinkcolor" style="background-color: #67B7E1;"></span> <span class="pattern bgclinkcolor" style="background-color: #F05858;"></span> <span class="pattern bgclinkcolor" style="background-color: #E67FB9;"></span> <span class="pattern bgclinkcolor" style="background-color: #9E9E9E;"></span> <span class="pattern bgclinkcolor" style="background-color: #AB8B65;"></span> <span class="pattern bgclinkcolor" style="background-color: #111111;"></span> <span class="pattern bgclinkcolor" style="background-color: #EFECEC;"></span> <span class="bgclinkcolor STYLERESET" style="">Reset</span>
    <input type="hidden"
             class="tpl-field"
             data-selector=".container a"
             value = "<?php if(isset($data['linkcolor']) and isset($data['linkcolor']['value'])){ print $data['linkcolor']['value']; } ?>"
             name="linkcolor"
             id="linkcolor"
             data-property="color" />

    <hr>
    <label class="mw-ui-label">Title color</label>
    <span class="pattern bgc" style="background-color: #A0CE4E;"></span> <span class="pattern bgc" style="background-color: #9DB668;"></span> <span class="pattern bgc" style="background-color: #E9A825;"></span> <span class="pattern bgc" style="background-color: #67B7E1;"></span> <span class="pattern bgc" style="background-color: #F05858;"></span> <span class="pattern bgc" style="background-color: #E67FB9;"></span> <span class="pattern bgc" style="background-color: #9E9E9E;"></span> <span class="pattern bgc" style="background-color: #AB8B65;"></span> <span class="pattern bgc" style="background-color: #111111;"></span> <span class="pattern bgc" style="background-color: #EFECEC;"></span> <span class="bgc STYLERESET" style="">Reset</span>
    <input type="hidden"
             class="tpl-field"
             data-selector="h1,h2,h3,h4,h5,h6,.btn,h1 a,h2 a,h3 a,h4 a,h5 a,h6 a, a.lead"
             value = "<?php if(isset($data['headerbg']) and isset($data['headerbg']['value'])){ print $data['headerbg']['value']; } ?>"
             name="headerbg"
             id="headerbg"
             data-property="color" />

<?php /*
   <select id="mw_set_template_style">
	  <option value="css/styles/spacelab.css">spacelab</option>
	  <option value="css/styles/superhero.css">superhero</option>
	  	  <option value="css/styles/amelia.css">superhero</option>

	  </select>

	  <input type="text"
             class="tpl-field"
             data-selector="@import"
             value = "<?php if(isset($data['import']) and isset($data['import']['value'])){ print $data['import']['value']; } ?>"
             name="import"
             id="import_css"
             />*/ ?>
<div class="mw_clear"></div>
<br />
<module type="content/views/layout_selector_custom_css" template="<?php print template_name(); ?>" />

