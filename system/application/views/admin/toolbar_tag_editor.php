
<script type="text/javascript">
function mw_html_tag_editor($mw_tag_edit_value){
	//tag_($jquery_this.get(0).tagName);
	mw_sidebar_nav('#mw_sidebar_css_editor_holder');
	$('#mw_css_editor_element_id').val($mw_tag_edit_value);
	mw_html_tag_editor_show_styles_for_tag();
	 $('*[mw_tag_edit="'+$mw_tag_edit_value+'"]').each(function(index) {
			//$(this).hide('aaa'); 	
			
		//	$(this).hide('aaa'); 
											 
	 });
	 $('#mw_html_css_editor :input').bind('change keydown keyup click',function(){
	  mw_html_tag_editor_apply_styles()
	});
	 
	 
	 $( ".mw_slider_generated" ).slider( "destroy" );
	 $( ".mw_slider_generated" ).remove();
$( "#mw_html_css_editor .mw_slider" ).each(function() {
		var $input = $(this);
		var $slider = $('<div class="mw_slider_generated" for="' + $input.attr('name') + '"></div>');
		//var step = $input.attr('step');

		$input.after($slider).hide();
		//$input.after($slider);

		$slider.slider({
			//min: $input.attr('min'),
			//max: $input.attr('max'),

			
			min: 1,
			max:  parseInt($('*[name="'+$input.attr('name')+'"]').val())+250,
			value: parseInt($('*[name="'+$input.attr('name')+'"]').val()),
			step: 1,
			change: function(e, ui) {
				//alert(ui.value);
				//alert($(this).attr('for'));
				 $('*[name="'+$input.attr('name')+'"]').val(ui.value);
				 $('*[name="'+$input.attr('name')+'"]').change();
				//alert($(this).val());
				//$(this).val(ui.value);
			}
		});
	});
		
	 
	 
	  /*$( "#mw_html_css_editor .mw_slider" ).slider( "destroy" );
	 $( "#mw_html_css_editor .mw_slider" ).slider({
			
			range: "max",
			min: 1,
			max: 1000,
			slide: function( event, ui ) {
				//$( "#amount" ).val( "$" + ui.value );
				mw_html_tag_editor_apply_styles()
			}
		});*/
	 
}

function mw_html_tag_editor_show_styles_for_tag(){
	$element = $('#mw_css_editor_element_id').val();
	$tag_name = $('*[mw_tag_edit="'+$element+'"]').get(0).tagName;
	$element_obj = $('*[mw_tag_edit="'+$element+'"]');
	//$styles = $('*[mw_tag_edit="'+$element+'"]').css2();
	$('#module_info').html('');
	var attr = ['font-family','font-size','font-weight','font-style','color',
	        'text-transform','text-decoration','letter-spacing','word-spacing',
	        'line-height','text-align','vertical-align','direction','background-color',
	        'background-image','background-repeat','background-position',
	        'background-attachment','opacity','width','height','top','right','bottom',
	        'left','margin-top','margin-right','margin-bottom','margin-left',
	        'padding-top','padding-right','padding-bottom','padding-left',
	        'border-top-width','border-right-width','border-bottom-width',
	        'border-left-width','border-top-color','border-right-color',
	        'border-bottom-color','border-left-color','border-top-style',
	        'border-right-style','border-bottom-style','border-left-style','position',
	        'display','visibility','z-index','overflow-x','overflow-y','white-space',
	        'clip','float','clear','cursor','list-style-image','list-style-position',
	        'list-style-type','marker-offset'];
	
	var len = attr.length, obj = {};
    for (var i = 0; i < len; i++) {
       
	   $old_val = $element_obj.css(attr[i]);
	   if($old_val != ''){
	  //  $old_val_split =  $old_val.split(" ");
	  $dim = false;
	  $check_px = $old_val.indexOf("px") ;
	  if($check_px > 0){
		 $dim = 'px;' 
	  }
	  
	  $check_px = $old_val.indexOf("PX") ;
	  if($check_px > 0){
		 $dim = 'px;' 
	  }
	  
	  $check_px = $old_val.indexOf("%") ;
	  if($check_px > 0){
		 $dim = '%;' 
	  }
	  
	    $check_px = $old_val.indexOf("em") ;
	  if($check_px > 0){
		 $dim = 'em;' 
	  }
	  
	  $check_px = $old_val.indexOf("pt") ;
	  if($check_px > 0){
		 $dim = 'pt;' 
	  }
	  if($dim != false){
		  $vvvvv = parseInt($old_val);
		  $('input[name="'+attr[i]+'"]').val($vvvvv);
		  
	   $('input[name="'+attr[i]+'"]').attr('dimensions',$dim);
		} else {
			$vvvvv = ($old_val);
		  $('input[name="'+attr[i]+'"]').val($vvvvv);
		   $('input[name="'+attr[i]+'"]').attr('dimensions','');
	  // $('input[name="'+attr[i]+'"]').attr('dimensions',$dim);
		}


		//alert($old_val_split);
		//var old_val_splits=old_val_split.length;
/*for(var i=0; i<len; i++) {
	var value = arr[i];
	alert(i =") "+value);
}*/
 //$('#module_info').append($old_val_split + "<br />");
// $('#module_info').append(attr[i] + "<br />");

//$('input[name="'+attr[i]+'"]').val($old_val_split[0]);
/*var old_val_split_len=$old_val_split.length;
for(var j=0; j<old_val_split_len; j++) {
	var value11 = $old_val_split[j];
	$('#module_info').append(value11 + "<br />");
	
}*/
//$('#module_info').append(attr[i] + "<hr />");
//$('#module_info').append($old_val_split + "<br />");



	   }
	 //$('input[name="'+attr[i]+'"]').val($old_val);  
	   
	}
 
	
	
	
	$tag_name = $tag_name.toLowerCase();
	$c = '.mw_edit_tag_'+$tag_name;
	
	//alert();
	
	$('#mw_edit_tag_table tr').hide();
	$($c).show();
	//$('#mw_edit_tag_table *').hasClass($c).html('asdsadasd');
	
 
	
	
	
	
	//alert($tag_name);
	
	
	 
	
}

function mw_html_tag_editor_apply_styles(){
	$element = $('#mw_css_editor_element_id').val();
	var $inputs = $('#mw_html_css_editor :input');
    // not sure if you wanted this, but I thought I'd add it.
    // get an associative array of just the values.
    var values = {};
	 var cssObj = {
     
    }
    $inputs.each(function() {
       // values[this.name] = $(this).val();
	   //$css_attr = $(this).attr('css_attr');
	   $css_attr = this.name;
	   $dims = $(this).attr('dimensions');
	   if( $dims != undefined &&  $dims != ''){
		 //alert( $dims);   
		 $vvv =  $(this).val();
		 $vvv = $vvv + $dims;
		$vvv = $vvv.replace(";", "");
		 //alert( $vvv);
		 $('*[mw_tag_edit="'+$element+'"]').css($css_attr,  $vvv );
	   } else {
		    $('*[mw_tag_edit="'+$element+'"]').css($css_attr, $(this).val());
	   }
	  // cssObj.$css_attr = $(this).val();
	   //alert( $css_attr);
	  
    });
	
	
	
	/* var cssObj = {
      'background-color' : '#ddd',
      'font-weight' : '',
      'color' : 'rgb(0,40,244)'
    }*/
    // $('*[mw_tag_edit="'+$element+'"]').css(cssObj);
	
}
</script>
<input name="mw_css_editor_element_id" id="mw_css_editor_element_id" value="" type="hidden" />
<div id="mw_html_css_editor">
 
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mw_edit_tag_table">
  <tr class="mw_edit_tag_img">
    <td>Float</td>
    <td>
    <select name="float">
    <option value="">None</option>
    <option value="left">Left</option>
    <option value="right">Right</option>
    </select>
    </td>
  </tr>
  <tr class="mw_edit_tag_img">
    <td>Width</td>
    <td>
 <input name="width" class="mw_slider" type="text" />
    </td>
  </tr>
  <tr class="mw_edit_tag_img">
    <td>Height</td>
    <td>
 <input name="height" class="mw_slider" type="text" />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>





css ed


</div>