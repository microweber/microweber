<script type="text/javascript">



function mw_html_tag_delete(){
	
	
	var r=confirm("Are you sure?");
if (r==true)
  {


	$element = $('#mw_css_editor_element_id').val();
	
	//alert($element);
	

	$element_obj = $('*[mw_tag_edit="'+$element+'"]' ,'.edit');
	$element_obj.fadeOut()
	$element_obj.remove()

  }
else
  {
 
  }
}

function mw_html_tag_editor($mw_tag_edit_value){
	//tag_($jquery_this.get(0).tagName);
	
	
	
	
//	if($mw_tag_edit_value != undefined){
//		$mw_tag_edit_value11 = $('#mw_css_editor_element_id').val();
//		if($mw_tag_edit_value == $mw_tag_edit_value11){
//			//alert(1);
//			return false;
//		}
//		
//	}
	
	
//	alert(123);

$('#mw_sidebar_module_edit_holder').hide();
mw_sidebar_nav('#mw_sidebar_css_editor_holder');
	
	mw_sidebar_nav('#mw_sidebar_css_editor_holder');
	
	if($mw_tag_edit_value == undefined){
		
		$mw_tag_edit_value = $('#mw_css_editor_element_id').val();
		$('#mw_css_editor_element_id').val($mw_tag_edit_value);
		
		
	} else {
		$('#mw_css_editor_element_id').val($mw_tag_edit_value);
	}
	
	$("div[module_id='"+$mw_tag_edit_value+"']",'.edit').attr('mw_tag_edit', $mw_tag_edit_value);
	
	
	
	
	
	mw_html_tag_editor_show_styles_for_tag();
	 $('*[mw_tag_edit="'+$mw_tag_edit_value+'"]', '.edit').each(function(index) {
			//$(this).hide('aaa'); 	
			
		//	$(this).hide('aaa'); 
											 
	 });
	 
	  $('#mw_html_css_editor .css_property').die('change')
	 $('#mw_html_css_editor .css_property').live('change',function(){
	  mw_html_tag_editor_apply_styles()
	  mw_slider_name = $(this).attr('for');
	  
	  
	  $('[mw_slider_name="'+mw_slider_name+'"]').slider('option', 'value', parseInt($(this).val()));
	//   $('[mw_slider_name="'+mw_slider_name+'"]').slider('option', 'max', parseInt($(this).val())+100);

	});
	 
	 	 
	 
	 $( ".mw_slider_generated" ).slider( "destroy" );
	 $( ".mw_slider_generated" ).remove();
$( "#mw_html_css_editor .mw_slider" ).each(function() {
		var $input = $(this);
		var $slider = $('<div class="mw_slider_generated" for="' + $input.attr('name') + '" mw_slider_name="' + $input.attr('name') + '"></div>');
		//var step = $input.attr('step');
		$what = $input.attr('name');
		$input.addClass('mw_slider_value');
		$input.attr('for', $what);
		$inputv = $input.val();
		
	 
		
		$input.before($slider);
		//$input.after($slider);
		
		var $inputv = $(this).val();
		if($inputv == undefined || $inputv == ''){
			
		$min = 1;
		$max = 50;
		 $value1 = 0;
		
		
		} else { 
		
		$min = 1;
		$max = 50 + parseInt($inputv);
		$value1 =  parseInt($inputv);
		
		}

if($input.attr('name') == 'height' || $input.attr('name') == 'width'){
	$min = 0;
		$max = 4000;
		 $value1 = 0;
	
}

		$slider.slider({
			//min: $input.attr('min'),
			//max: $input.attr('max'),

			
			min: $min,
			//max:100,
			max:  $max,
			value: $value1,
			step: 1,
			change: function(e, ui) {
				//alert(ui.value);
				//alert($(this).attr('for'));
				
			 
				
				$what = $input.attr('name');
				
				$('#mw_'+$what+'_val', '#mw_html_css_editor').html('');
				 
				 if($what=='height'){
					 $('*[name="width"]', '#mw_html_css_editor').val('auto');
					 $('#mw_width_val', '#mw_html_css_editor').html('auto');
					 $('#mw_height_val', '#mw_html_css_editor').html(ui.value);
				 }
				 
				 if($what=='width'){
					 $('*[name="height"]', '#mw_html_css_editor').val('auto');
					  $('#mw_height_val', '#mw_html_css_editor').html('auto');
					  $('#mw_width_val', '#mw_html_css_editor').html(ui.value);
				 }
				 
				 
				 
				 $('*[name="'+$input.attr('name')+'"]', '#mw_html_css_editor').val(ui.value);
				// $('*[name="'+$input.attr('name')+'"]', '#mw_html_css_editor').change();
				  mw_html_tag_editor_apply_styles()
	 
				 
				 
				 
				// height: auto;

				 
				 
				//alert($(this).val());
				//$(this).val(ui.value);
			}
		});
	});
		
	 
	 
	   
//	  
//	   $('#mw_html_css_editor .mw_color_picker').ColorPicker({
//	onSubmit: function(hsb, hex, rgb, el) {
//		$(el).val(''+hex);
//		$(el).ColorPickerHide();
//		mw_html_tag_editor_apply_styles()
//	},
//	onBeforeShow: function () {
//		$v1vv= this.value;
//		  $v1vv = $v1vv.replace("#", "");
//		
//		$(this).ColorPickerSetColor($v1vv);
//	},
//	
//	
//	
//	//color: $(this).val().replace("#", ""),
//	onShow: function (colpkr) {
//		$(colpkr).fadeIn(500);
//		return false;
//	},
//	onHide: function (colpkr) {
//		$(colpkr).fadeOut(500);
//		return false;
//	},
//	onChange: function (hsb, hex, rgb) {
//		$(this).css('backgroundColor', '#' + hex);
//	}
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//})
//.bind('keyup', function(){
//	$(this).ColorPickerSetColor(''+this.value);
//});
	 
	 
	 
 
	 
	 
	 
	 
//	 $('.mw_color').colorpicker({
//                size: 20,
//                label: 'Color: ',
//                hide: false
//            });
	 
	 

	 
	 
	 var availableTags_fonts = [
			"Arial",
			"Verdana, Geneva, sans-serif",
			"Tahoma",
			"Helvetica",
			"Times"
		];
		$( ".mw_ac_fonts" ).autocomplete({
			minLength: 0,
			close: function(event, ui) {
				mw_html_tag_editor_apply_styles()
				},
			source: availableTags_fonts
		});
		
		
 
		  
		   $('.mw_ac_fonts').live('click',function(){ 
												  
       $(this).autocomplete("search", "")
	    //$(this).trigger('keydown.autocomplete');
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
	
	//alert($element);
	
	
	




	
	
 
	try{
// $tag_name = $('*[mw_tag_edit="'+$element+'"]:last' ,'.edit').get(0).nodeName;

 $tag_name = $('*[mw_tag_edit="'+$element+'"]:last' ,'.edit').nodeName;
}
catch(e){
	
	  if (window.console != undefined) {
							console.log('cant get styles for element ' +e.message  );	
							
						}
	
 return false;
}
	if($tag_name == undefined){
	$tag_name = 'DIV';	
	}
	//$tag_name = $('*[mw_tag_edit="'+$element+'"]:last' ,'.edit').get(0).nodeName;
	
	$el123 = $('*[mw_tag_edit="'+$element+'"]:last' ,'.edit');
	
	
	$element_obj = $('*[mw_tag_edit="'+$element+'"]:last' ,'.edit');
	//$styles = $('*[mw_tag_edit="'+$element+'"]').css2();
	//$('#module_info').html('');
//	var attr = ['font-family','font-size','font-weight','font-style','color',
//	        'text-transform','text-decoration','letter-spacing','word-spacing',
//	        'line-height','text-align','vertical-align','direction','background-color',
//	        'background-image','background-repeat','background-position',
//	        'background-attachment','opacity','width','height','top','right','bottom',
//	        'left',
//			
//			'margin-top','margin-right','margin-bottom','margin-left',
//	        'padding-top','padding-right','padding-bottom','padding-left',
//			
//			
//			
//	        'border-top-width','border-right-width','border-bottom-width',
//	        'border-left-width','border-top-color','border-right-color',
//	        'border-bottom-color','border-left-color','border-top-style',
//	        'border-right-style','border-bottom-style','border-left-style','position',
//	        'display','visibility','z-index','overflow-x','overflow-y','white-space',
//	        'clip','float','clear','cursor','list-style-image','list-style-position',
//	        'list-style-type','marker-offset'];



	var attr = ['font-family','font-size','font-weight','font-style','color',
	        'text-transform','text-decoration','letter-spacing','word-spacing',
	        'line-height','text-align','vertical-align','direction','background-color',
	        'background-image','background-repeat','background-position',
	        'background-attachment','opacity','width','height','top','right','bottom',
	        'left',
			
			'margin',
	        'padding',
			
			
			
	        'border-top-width','border-right-width','border-bottom-width',
	        'border-left-width','border-top-color','border-right-color',
	        'border-bottom-color','border-left-color','border-top-style',
	        'border-right-style','border-bottom-style','border-left-style','position',
	        'display','visibility','z-index','overflow-x','overflow-y','white-space',
	        'clip','float','clear','cursor','list-style-image','list-style-position',
	        'list-style-type','marker-offset'];
	
	
	
	
	 
	 
	 var len=attr.length;
for(var i=0; i<len; i++) {
	var value1 = attr[i];
	$('input[name="'+value1+'"]', '#mw_html_css_editor').val('');
	 $('select[name="'+value1+'"] option', '#mw_html_css_editor').removeAttr('selected');
	 
	 
	 
	 
	 $('a[css_name="'+value1+'"]' , '#mw_html_css_editor').removeClass('active');
									
									
	 
	 
	 
	 
	  $check_is_color = value1.indexOf("color") ;
								  if($check_is_color != -1){
									//   $dim = '#' ;
									
									$('input[name="'+value1+'"]', '#mw_html_css_editor').css('background-color','' );
									
								  }
	 
	 
	 
	 
	 
}



	
	
	
	var styles={};
	    $element_obj.each(function(index)
    {
				   var styletag=$element_obj.attr('style');
				   
				   
				   
				//   alert(styletag);
					  if (window.console != undefined) {
							console.log('element styles ' +styletag  );	
							
						}
				   
				   
				   if(styletag != undefined && styletag != ''){
					   
							  styletag =   styletag.replace("http:", "http_");
							   styletag =   styletag.replace("https:", "https_");
							   
							   
						   
						   var stylestemp=styletag.split(';');
						   var styles={};
						   var c='';
						   for (var x in stylestemp) {
									 c=stylestemp[x].split(':');
									 styles[$.trim(c[0])]=$.trim(c[1]);
									 
									 
									 
									 $old_val = $.trim(c[1]);
									  $old_key =$.trim(c[0]);
									  
									 if($old_key != undefined && $old_key != NaN && $old_key != ''&& $old_key != '_mw_dirty'){ 
											   if (window.console != undefined) {
												//	console.log('element get style ' +$old_key + $old_val  );	
													
												}
									  
									   $old_key = $old_key.toLowerCase();
							 
								   
								   if($old_val != undefined && $old_val != NaN && $old_val != ''){
									   
									   
									   
									   
									   
								  //  $old_val_split =  $old_val.split(" ");
								  $dim = false;
								  $dim_before = false;
								  
								  
									
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
								  
								  
								  
								  
								  
								  $check_rgb = $old_val.indexOf("rgb") ;
								  
							 
								   if($check_rgb != -1){
								  $check_rgb_val = ($old_val)  ;
									 $check_rgb_val = $check_rgb_val.replace("rgb(", "");
									  $check_rgb_val = $check_rgb_val.replace(")", "");
									
									
									//alert( $check_rgb_val);
									 if (window.console != undefined) {
									 console.log('rgb '+$check_rgb_val );
									 }
									
									check_rgb_val1 =	$check_rgb_val.split() + ","
								//	 $old_val = '#'+RGBtoHex(check_rgb_val1[0],check_rgb_val1[1],check_rgb_val1[2]);
									// $dim = '#' 
							//
								  }
								  
								    $check_is_bg = $old_key.indexOf("background-image") ;
								  if($check_is_bg != -1){
									//   $dim = '#' ;
									
									 
								   $old_val =   $old_val.replace( "http_","http:");
							   $old_val =   $old_val.replace( "https_","https:");
								  
								  
									  $old_val = "url('"+ $old_val +"')";
									
								  }
								  
								  
								   $check_is_color = $old_key.indexOf("color") ;
								  if($check_is_color != -1){
									//   $dim = '#' ;
									
									$('input[name="'+$old_key+'"]', '#mw_html_css_editor').css('background-color',$old_val );
									
								  }
								  
								 
							
								  
								 $('select[name="'+$old_key+'"] option[value=""]', '#mw_html_css_editor').removeAttr('selected');
								  
								  
								  
								  
								  
								  $check_padding = $old_key.indexOf("padding") ;
								  if($check_padding != -1){
									 // alert($old_val);
									 $('#mw_padding_val' ).html($old_val);
								  }
								  
								  
								  
								    $check_margin = $old_key.indexOf("margin") ;
								  if($check_margin != -1){
									 //  alert($old_val);
									 $('#mw_margin_val' ).html($old_val);
								  }
								  
								  if($old_key == 'padding' || $old_key == 'margin'){
									//alert($old_key);  
								  }
								  
	 
									
									  $check_box_shadow = $old_key.indexOf("box-shadow") ;
									    if($check_box_shadow != -1){
											
											check_box_shadow1_v = $old_val.replace("  ", " ")
											check_box_shadow1 =	check_box_shadow1_v.split(" "); 
											
											
											if(check_box_shadow1[0] != undefined){
												 $box_shadow_horizontals = check_box_shadow1[0].indexOf("px") ;
												if(check_box_shadow1[0] != undefined && $box_shadow_horizontals != -1){
													 box_shadow_horizontal = $('#mw_html_css_editor *[name="box-shadow-horizontal"]').val(check_box_shadow1[0]);
												}
												
												 
												
												
											}
											
											if(check_box_shadow1[1] != undefined){
											 $box_shadow_verticals = check_box_shadow1[1].indexOf("px") ;
											if(check_box_shadow1[1] != undefined && $box_shadow_verticals != -1 ){
												 box_shadow_vertical = $('#mw_html_css_editor *[name="box-shadow-vertical"]').val(check_box_shadow1[1]);
											}
											}
											if(check_box_shadow1[2] != undefined){
											 $box_shadow_blurs = check_box_shadow1[2].indexOf("px") ;
											if(check_box_shadow1[2] != undefined && $box_shadow_blurs != -1 ){
												 box_shadow_blur = $('#mw_html_css_editor *[name="box-shadow-blur"]').val(check_box_shadow1[2]);
											}
											}
											if(check_box_shadow1[3] != undefined){
											 $box_shadow_spreads = check_box_shadow1[3].indexOf("px") ;
											if(check_box_shadow1[3] != undefined && $box_shadow_spreads != -1){
												 box_shadow_spread = $('#mw_html_css_editor *[name="box-shadow-spread"]').val();
											}
											}
											if(check_box_shadow1[4] != undefined){
											$box_shadow_color_check_1 = check_box_shadow1[4].indexOf("#") ;
											$box_shadow_color_check_2 = check_box_shadow1[4].indexOf("rgb") ;
											if(check_box_shadow1[4] != undefined && ($box_shadow_color_check_1 != -1 || $box_shadow_color_check_2 != -1)){
												vhex = rgbConvert(check_box_shadow1[4]) 
										 
												 box_shadow_color = $('#mw_html_css_editor *[name="box-shadow-color"]').val();
											}
											}
									    
								  }
								  
								  
									
							 
									
									
									
									
									
									
									
									
									
									
									
									
									
									
								  
								  
								  if($dim != false){
									  $vvvvv = parseInt($old_val);
									  $('input[name="'+$old_key+'"]', '#mw_html_css_editor').val($vvvvv);
									  
								   $('input[name="'+$old_key+'"]', '#mw_html_css_editor').attr('dimensions',$dim);
									} else {
										$vvvvv = ($old_val);
									  $('input[name="'+$old_key+'"]', '#mw_html_css_editor').val($vvvvv);
									   $('input[name="'+$old_key+'"]', '#mw_html_css_editor').attr('dimensions','');
									   
									   
									   	 $('a[css_value_active="'+$vvvvv+'"]' , '#mw_html_css_editor').addClass('active');

									   
									   
									   
								  // $('input[name="'+attr[i]+'"]').attr('dimensions',$dim);
									}
							 if($dim != false){
								 
								 
								 
							 }
							
							$('input[name="'+$old_key+'"]', '#mw_html_css_editor').attr('value_from_document',$vvvvv);
							
							$('select[name="'+$old_key+'"] option[value="'+$vvvvv+'"]', '#mw_html_css_editor').attr('selected', 'selected');
							
							
							
							
								   }
						   
						   }
						   } //if old key
			   } else {
				   $('input.css_property', '#mw_html_css_editor').val('');
	 $('option.css_property', '#mw_html_css_editor select').removeAttr('selected');
	 $('option:first', '#mw_html_css_editor select.css_property').attr('selected', 'selected');
				   
				   
			   }
     //  alert(styles.width);
    });
	//alert($tag_name);
	
	if(($tag_name == 'A') || ($tag_name == 'a')){
		$('#link_editor_holder').show();
		
		
		$href_attr = $el123.attr('href');
		$href_attr_taget = $el123.attr('target');
		$('#mw_edit_link').val($href_attr);
		$('#mw_edit_link_window').val($href_attr_taget);
	 
	 
		
 
		
	} else {
		$('#link_editor_holder').hide();
	}
	
	if(($tag_name == 'IMG') || ($tag_name == 'img')){
		$('#image_editor_holder').show();
		//   $('.css_editor_tab_size', '#mw_html_css_editor').show();
//		   $('.css_editor_tab_size_inside', '#mw_html_css_editor').show();
//		    $('.css_editor_tab_border', '#mw_html_css_editor').show();
//		   
//		   
//		   
//		     $('.css_editor_tab_text', '#mw_html_css_editor').hide();
//			 $('.css_editor_tab_background', '#mw_html_css_editor').hide();
//			 
			 
			 
	}
	
	//
//		  $('.css_editor_tab_text_inside', '#mw_html_css_editor').hide();
//		  
//		  
//		  $('.css_editor_tab_background_inside', '#mw_html_css_editor').hide();
//
//		   $('.css_editor_tab_size_inside', '#mw_html_css_editor').hide();
//		   $('.css_editor_tab_border_inside', '#mw_html_css_editor').hide();
	
	
	
	
	
	if(($tag_name == 'P') || ($tag_name == 'SPAN') || ($tag_name == 'CODE') || ($tag_name == 'STRONG') || ($tag_name == 'LI') || ($tag_name == 'UL') ||  ($tag_name == 'A')){
		//  $('.css_editor_tab_text', '#mw_html_css_editor').show();
//		   $('.css_editor_tab_text_inside', '#mw_html_css_editor').show();
//		  
//		  
//		  $('.css_editor_tab_background', '#mw_html_css_editor').show();
//
//		   $('.css_editor_tab_size', '#mw_html_css_editor').hide();
//		   $('.css_editor_tab_border', '#mw_html_css_editor').hide();
	}
	
	if(($tag_name == 'DIV') || ($tag_name == 'FORM') || ($tag_name == 'TABLE') ){
		//  $('.css_editor_tab_text', '#mw_html_css_editor').show();
//		  $('.css_editor_tab_text_inside', '#mw_html_css_editor').show();
//		  
//		  
//		  $('.css_editor_tab_background', '#mw_html_css_editor').show();
//
//		   $('.css_editor_tab_size', '#mw_html_css_editor').show();
//		   $('.css_editor_tab_border', '#mw_html_css_editor').show();
	}
	
	$('.css_editor_tab_effects', '#mw_html_css_editor').show();
	
	
	if(($tag_name == 'IMG') || ($tag_name == 'img')){
		//alert($tag_name);
		$check_attr = $el123.css('height');
		
		if($check_attr == undefined || $check_attr == ''){
			$el123.css('height', 'auto');
		}
		
		$check_attr = $el123.css('width');
		if($check_attr == undefined || $check_attr == ''){
			$el123.css('width', 'auto');
		}
		
		
		$src_attr = $el123.attr('src');
		  $('#image_editor_holder').show();
		$('#image_editor_holder_src').attr('src', $src_attr);
		
		
		
		
					
					
					
		
		
		//$el123.css('width', 'auto');
 		height1 = $el123.height();
		width1 = $el123.width();
		 
		 
		$('.mw_img_size').val(width1+'x'+height1);			
		
		
		  
				 if ($el123.parent().is("a")) {
				 link1= $el123.parent().attr("href" );
				 $('#mw_edit_image_link').val(link1);
				}  else {
					 $('#mw_edit_image_link').val('');
				}
				
					
				 
		 
		//$('input[name="height"]', '#mw_html_css_editor').val(height1);
		$('#mw_height_val', '#mw_html_css_editor').html(height1);
		
		//$('input[name="width"]', '#mw_html_css_editor').val(width1);
		$('#mw_width_val', '#mw_html_css_editor').html(width1);
		$('#image_editor_holder').show();
		
		
		
		 

	} else {
		$('.mw_img_size').val('');		
		 $('#mw_edit_image_link').val('');
	 $('#image_editor_holder').hide();	
	}
	
	
	
	
	
	 
 
	
	
	
	$tag_name = $tag_name.toLowerCase();
	$c = '.mw_edit_tag_'+$tag_name;
	
	
	
	//alert();
	
	//$('.mw_edit_tag_table tr', '#mw_html_css_editor').hide();
	//$($c).show();
	//$('.mw_edit_tag_table *').hasClass($c).html('asdsadasd');
	
 
	
	
	
	//alert($tag_name);
	
	
	 
	
}

function getFirstRange() {
            var sel = rangy.getSelection();
            return sel.rangeCount ? sel.getRangeAt(0) : null;
        }

$(".mw_make_element").live('click',function (e) {
//	 rangy.init();


$el1 = $(this).attr('make_element');
$make_element_attr = $(this).attr('make_element_attr');
$make_element_attr_val = $(this).attr('make_element_attr_val');


    e.preventDefault();
			//event.preventDefault(); // this prevents the original href of the link from being opened
			e.stopPropagation(); // this prevents the click from triggering click events up the DOM from this element
			
								   var range = getFirstRange();
            if (range) {
                var el = document.createElement($el1);
				  $new_el_id  = "mw_element_"+Date.now();
				  el.id = $new_el_id;
             try {
				 
				 
				 
                    range.surroundContents(el);
					
					
					
					if($make_element_attr != undefined && $make_element_attr != ''){
				
				 $("#"+$new_el_id, '.edit').attr($make_element_attr, $make_element_attr_val);
				 }
                } catch(ex) {
                    if ((ex instanceof rangy.RangeException || Object.prototype.toString.call(ex) == "[object RangeException]") && ex.code == 1) {
						 if (window.console != undefined) {
				 			console.log("Unable to surround range because range partially selects a non-text node. See DOM Level 2 Range spec for more information.\n\n" + ex);	
				 		}
                       
                    } else {
						 if (window.console != undefined) {
				 			console.log("Unexpected errror: " + ex);	
				 		}
                       
                    }
                }
            }
			return false;
 
    });

function mw_html_tag_editor_apply_style_for_element($css_property, $new_value, $is_toggle){
	 
	 
	 
	 		$('a[css_value_active="'+$new_value+'"]' , '#mw_html_css_editor').addClass('active');

	 
	if($is_toggle != undefined){
	
	$old = $('*[name="'+$css_property+'"].css_property' , '#mw_html_css_editor').val();
	
	

	
	
	
	
	
				if($old == $new_value){
					
				$new_value = $is_toggle;
						$('a[css_name="'+$css_property+'"]' , '#mw_html_css_editor').removeClass('active');

				
				}
	}
	
	
	
	
	
	
	$('.css_property input[name="'+$css_property+'"]').val( $new_value);
	
 	 $('select[name="'+$css_property+'"] option', '#mw_html_css_editor').removeAttr('selected');
	 $('select[name="'+$css_property+'"] option', '#mw_html_css_editor').removeAttr('selected');
	$('select[name="'+$css_property+'"] option[value="'+ $new_value+'"]', '#mw_html_css_editor').attr('selected', 'selected');


 mw_html_tag_editor_apply_styles()



	
}

function mw_html_tag_remove_styles(){
	$element = $('#mw_css_editor_element_id').val();
		  $('*[mw_tag_edit="'+$element+'"]' ,'.edit').removeAttr("style");
		   $('*[mw_tag_edit="'+$element+'"] *' ,'.edit').removeAttr("style");
	  $('*[mw_tag_edit="'+$element+'"]' ,'.edit').children().removeAttr("style");
	  	//$('*[mw_tag_edit="'+$element+'"]').parent().removeAttr("style");
}

function mw_html_tag_editor_apply_custom_element_style($style_name, $style_url){
		$element = $('#mw_css_editor_element_id').val();
		
		url = $style_url;
 
 
 
 if (!$("link[href='"+url+"']").length){

 

var link = document.createElement('link');
link.rel = 'stylesheet';
link.type = 'text/css';
link.href = url;
document.getElementsByTagName('head')[0].appendChild(link);
}



var regEx = /^mw-style/;
var elm = $("#"+$element, '.edit');

var classes = elm.attr('class').split(/\s+/); //it will return  foo1, foo2, foo3, foo4

for (var i = 0; i < classes.length; i++) {
  var className = classes[i];
	
  if (className.match(regEx)) {
	elm.removeClass(className);
  }
}




		
		$("#"+$element, '.edit').addClass("mw-custom-style");
		$("#"+$element, '.edit').addClass("mw-style-"+$style_name);
 $(".row",'.edit').equalWidths().equalHeights();
 $(".column",'.edit').height('auto');

	
}
function mw_html_tag_editor_apply_styles(){
	$element = $('#mw_css_editor_element_id').val();
	var $inputs = $('#mw_html_css_editor .css_property').not('.css_fx');
	

	

					 
				    	// var randomCssClass = "rangyTemp";
						   
				
						//   classApplier.toggleSelection();
					 
					//	   classApplier.toggleSelection();
					   
					/*   var range = rangy.getSelection();
	 
					    
					     if (range != null && range != '') {
							  window.mw_last_hover++;
							 	$element =  window.mw_last_hover++;	
 
	$('#mw_css_editor_element_id').val($element);
							 
							 	randomCssClass = 'rcc_'+$element;
	
		
					  var classApplier = rangy.createCssClassApplier(randomCssClass, true);
				
	 
					 if (window.console != undefined) {
				 			console.log('createCssClassApplier  ' + randomCssClass);	
				 		}
							 
							 
							  

							 
							 
					    	  		   classApplier.applyToSelection();
									   
									    $sel123 =   $("." + randomCssClass);
							   */
//							   var el = $("." + randomCssClass);
//							   var range = rangy.createRange();
//							   range.selectNodeContents(el);
//							   var sel = rangy.getSelection();
//							   sel.setSingleRange(range);
							    //classApplier.applyToSelection();
							  
							    // Now use jQuery to add the CSS colour and remove the class
							   //$sel123.attr( "mw_tag_edit",$element );
							  
						 
							   //var sel = rangy.getSelection();
							     //sel.refresh(true);
							    
							     
								 
						    
					     
	
    // classApplier.toggleSelection();
    // not sure if you wanted this, but I thought I'd add it.
    // get an associative array of just the values.
    var values = {};
	 var cssObj = {
     
    }
	
	var cssstr = '';
	var cssstr_spans = '';
	var cssstr_fx_shadow = '';
	
    $inputs.each(function() {
       // values[this.name] = $(this).val();
	   //$css_attr = $(this).attr('css_attr');
	  
					  
					 // if ( $(this).is(':visible')){
							 $css_attr = this.name;
								   $dims = $(this).attr('dimensions');
								   if( $dims != undefined &&  $dims != ''){
									 //alert( $dims);   
									 $vvv =  $(this).val();
									 
									 $val1234 = $(this).val();
									if($val1234 != undefined && $val1234 != ''){
										
										 $vvv = $vvv + $dims;
									$vvv = $vvv.replace(";", "");
									 //alert( $vvv);
									// $('*[mw_tag_edit="'+$element+'"]').css($css_attr,  $vvv );
									cssstr= cssstr+ ' "'+$css_attr+'":"'+$vvv+ '",';
									cssstr_spans= cssstr_spans+ ' "'+$css_attr+'":"'+$vvv+ '",';
									 if($css_attr == 'font-size'){
										// $('*[mw_tag_edit="'+$element+'"]').css('line-height',  $vvv );
										cssstr= cssstr+ ' '+'"line-height"'+':"'+$vvv+ '",';
										cssstr_spans= cssstr_spans+ ' '+'"line-height"'+':"'+$vvv+ '",';
									 }
									 
									 
									  if($css_attr == 'border-radius'){
										// $('*[mw_tag_edit="'+$element+'"]').css('line-height',  $vvv );
										cssstr= cssstr+ ' '+'"-webkit-border-radius"'+':"'+$vvv+ '",';
										cssstr= cssstr+ ' '+'"-moz-border-radius"'+':"'+$vvv+ '",';
										
										
										cssstr= cssstr+ ' '+'"-moz-background-clip"'+':"padding",';
										cssstr= cssstr+ ' '+'"-webkit-background-clip"'+':"padding-box",';
										cssstr= cssstr+ ' '+'"background-clip"'+':"padding-box",';
										 
										 
										
										 
										
				 
										
									 }
									 
								 
									
				
									
									
									  if($css_attr == 'background-image'){
										// $('*[mw_tag_edit="'+$element+'"]').css('line-height',  $vvv );
										//cssstr= cssstr+ ' '+'"line-height"'+':"'+$vvv+ '",';
									 }
									 
									 
									 
									 
									}
									 
									 
									 
									
									 
									 
									 
									 
								   } else {
									//	$('*[mw_tag_edit="'+$element+'"]').css($css_attr, $(this).val());
									$val1234 = $(this).val();
									if($val1234 != undefined && $val1234 != ''){
										
									$val1234 =	$.trim($val1234)
										if($val1234 != ''){
									if($css_attr == 'height' || $css_attr == 'width'){
										
									} else {
																			cssstr_spans= cssstr_spans+ '"'+$css_attr+'":"'+$val1234+ '",';

									}
									cssstr= cssstr+ '"'+$css_attr+'":"'+$val1234+ '",';

										}
									}
									
									
									
								   }
								  // cssObj.$css_attr = $(this).val();
								   //alert( $css_attr);
								  
					  
					   
				//}
	  
    });



	 
									
							 //cssstr = cssstr.replace(/(^,)|(,$)/g, "")
		
				//cssstr= ''+cssstr+  ' "border" : "1px"';	
				//var cssstr=cssstr.split(',').join(',');
			//	var cssstr = cssstr.substring(0, cssstr.length - 1);
				
				
			 
//var strLen = cssstr.length;
//cssstr = cssstr.slice(0,strLen-1);


 
 
 box_shadow_horizontal = $('#mw_html_css_editor *[name="box-shadow-horizontal"]').val();
 box_shadow_vertical = $('#mw_html_css_editor *[name="box-shadow-vertical"]').val();
 
 box_shadow_blur = $('#mw_html_css_editor *[name="box-shadow-blur"]').val();
  box_shadow_spread = $('#mw_html_css_editor *[name="box-shadow-spread"]').val();
  box_shadow_color = $('#mw_html_css_editor *[name="box-shadow-color"]').val();
 //alert(box_shadow_horizontal);
 if(box_shadow_horizontal != '' && box_shadow_vertical != ''){
	$shadow = '';
	
 
	
	if(box_shadow_horizontal != ''){
			$shadow = $shadow + box_shadow_horizontal+'px ';
		
	}
	if(box_shadow_vertical != ''){
			$shadow = $shadow + box_shadow_vertical+'px ';
		
	}
	if(box_shadow_vertical != ''){
			$shadow = $shadow + box_shadow_vertical+'px ';
		
	}
	if(box_shadow_blur != ''){
			$shadow = $shadow + box_shadow_blur+'px ';
		
	}
	if(box_shadow_spread != ''){
			$shadow = $shadow + box_shadow_spread+'px ';
		
	}
	if(box_shadow_color != ''){
		box_shadow_color = rgbConvert(box_shadow_color);
			$shadow = $shadow + box_shadow_color;
		
	}
	
	
	
//	$shadow = box_shadow_horizontal+'px '+box_shadow_vertical+'px '+box_shadow_blur+'px '+box_shadow_spread+'px ' + box_shadow_color ;
	if($shadow != ''){
    	cssstr= cssstr+ ' '+'"-webkit-box-shadow"'+':"'+$shadow+ '",';
    	cssstr= cssstr+ ' '+'"-moz-box-shadow"'+':"'+$shadow+ '",';
    	cssstr= cssstr+ ' '+'"box-shadow"'+':"'+$shadow+ '",';
    	cssstr= cssstr.replace(/pxpx/gi, "px")
	}
 
 }
 
  transforms_cssOut_v = $('#transforms_cssOut').val();
 
 
   if(transforms_cssOut_v != ''){
					 
					 
					 
					 
					 
					 
				 
				 

				 }


				
									 
				var cssstr = eval("({" + cssstr + " ' _mw_dirty':'1' })");
				var cssstr_spans = eval("({" + cssstr_spans + " ' _mw_dirty':'1' })");
				
				
				
				// $('*[mw_tag_edit="'+$element+'"]').children('.rangyTemp').css('');
				// $('*[mw_tag_edit="'+$element+'"]').children('.rangyTemp').removeClass('rangyTemp');
				
				
				
				 //$('*[mw_tag_edit="'+$element+'"]').css(cssstr);
				 
				 
				  $('.module > [mw_tag_edit="'+$element+'"]', '.edit').attr('mw_tag_edit', ''); 
				
				 //cssstr_1 = cssstr;
				 //cssstr_1 = cssstr_1.replace(/<(\/?)strong>|<strong( [^>]+)>/gi, '<$1span style="font-weight:bold;"$2>');
			   if (window.console != undefined) {
									 console.log($element+ ' apply css '+cssstr );
									  console.log('apply css spans '+cssstr_spans );
									 }
									 
				 	  $('span[mw_tag_edit="'+$element+'"]', '.edit').css(cssstr_spans);
					    $('[id="#'+$element+'"]', '.edit').css(cssstr_spans);
					   
					   $('[mw_tag_edit="'+$element+'"]').each(function() {
 $(this).css(cssstr);  
});

				 
	 // $('*[mw_tag_edit="'+$element+'"]', '.edit').not('span').css(cssstr); 
	 // $('*[mw_tag_edit="'+$element+'"]').children().css(cssstr);
	  
	//$spans =  $('*[mw_tag_edit="'+$element+'"]').parent().find('span');
//	
//	$spans.each(function() {
//						  
//			 has =   $(this).attr('mw_tag_edit');
//			 
//			 if(has != undefined || has != ''){
//				 
//				 st =   $(this).attr('style');
//				 
//				  if(st == undefined || st == ''){
//				    if (window.console != undefined) {
//									 console.log('has spans with style '+has + st );
//									 
//									 }	
//									 
//			//						 var el = this;
////            var range = rangy.createRange();
////            range.collapseToPoint(el, 0);
////            range.normalizeBoundaries();
//
// 
//			
//			
//									// $(this).replaceWith(function() {
//									 // return $(this).contents();
//									//});
//									 
//									 
//									// $(this).parent().replaceWith( $(this).contents() ); 
//									// $(this).unwrap();
//
//									 
//				  }
//				 
//			 }
//			 
//			 
//
//									 
//									 
//						  
//	 });
 

	  
 //$('*[mw_tag_edit="'+$element+'"]').children().has('span[mw_tag_edit]').css('border','5px solid #ccc');

	// console.log($('*[mw_tag_edit="'+$element+'"]').children('span[mw_tag_edit]'));  
	
	
	
	/* var cssObj = {
      'background-color' : '#ddd',
      'font-weight' : '',
      'color' : 'rgb(0,40,244)'
    }*/
    // $('*[mw_tag_edit="'+$element+'"]').css(cssObj);
	
}
 
 
 
 
  $(document).ready(function() {
							 
							 
			$(".mw_editor_accordeon").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
		$(".mw_editor_accordeon li").removeClass('ui-corner-top').addClass('ui-corner-left');
		
		
						 
	 
		 
		
		$("#mw_html_css_editor").live("mouseenter", function(event) {
		//	$element = $('#mw_css_editor_element_id').val();
		//	$(".mw_outline").remove();
		//	mw.outline.init('*[mw_tag_edit="'+$element+'"]', '#DCCC01');		   
	 
			
			
			
		})
		
		
		   
		
		
		$(".ui-accordion-header", "#mw_html_css_editor" ).click(function(){
        //  $(this).blur();
        });
		
		
		$( ".mColorPickerTrigger" ).hide();
		
		
/*	 	$('.mw_color').mColorPicker({
               imageFolder: '<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/color_picker/images/'
			   
           });*/
	 
	 
	  //$('.mw_color').addClass('mColorPickerTrigger');
	  $('.mw_color').show();
	  $( ".mw_tag_editor_input_font_color" ).hide(); 
	 
	 
		
		
			 
  });
  </script>
  
  
  
  
  
  
  
  
  
  
  <script type="text/javascript">
  
  
  
   upl_options = { 
       beforeSubmit: function(a,f,o) {
            o.dataType = 'json';
            $('#uploadOutput').html('Submitting...');
        },
        success: function(data) {
		//	alert(data);
				$element = $('#mw_css_editor_element_id').val();
		  $('*[mw_tag_edit="'+$element+'"]' ,'.edit').attr("src", data.url);
			 $('#mw_img_size_set').val(data.width+'x'+data.height);
			
			
           // var $out = $('#uploadOutput');
//            $out.html('Form success handler received: <strong>' + typeof data + '</strong>');
//            if (typeof data == 'object' && data.nodeType)
//                data = elementToString(data.documentElement, true);
//            else if (typeof data == 'object')
//                data = objToString(data);
//            $out.append('<div><pre>'+ data +'</pre></div>');
        }
    }; 				
  
  
  
  
  
  function mw_do_the_image_upload(){
	   upl_options = { 
       beforeSubmit: function(a,f,o) {
            o.dataType = 'json';
            $('#uploadOutput').html('Submitting...');
        },
		     clearForm: true  ,      // clear all form fields after successful submit 
         resetForm: true,
        success: function(data) {
		//	alert(data);
				$element = $('#mw_css_editor_element_id').val();
		  $('*[mw_tag_edit="'+$element+'"]' ,'.edit').attr("src", data.url);
		  $('#image_editor_holder_src').attr("src", data.url);
		  
		  
			 $('#mw_img_size_set').val(data.width+'x'+data.height);
			
			
           // var $out = $('#uploadOutput');
//            $out.html('Form success handler received: <strong>' + typeof data + '</strong>');
//            if (typeof data == 'object' && data.nodeType)
//                data = elementToString(data.documentElement, true);
//            else if (typeof data == 'object')
//                data = objToString(data);
//            $out.append('<div><pre>'+ data +'</pre></div>');
        }
    }; 				
	
	
	  $('#uploadForm').ajaxSubmit(upl_options); 
  }
  
  
$(document).ready(function() {
						   
	 	   $('#mw_html_css_editor .css_property').die('change')
	 $('#mw_html_css_editor .css_property').live('change',function(){
	  mw_html_tag_editor_apply_styles()
	  mw_slider_name = $(this).attr('for');
	  
	  
	  $('[mw_slider_name="'+mw_slider_name+'"]').slider('option', 'value', parseInt($(this).val()));
	//   $('[mw_slider_name="'+mw_slider_name+'"]').slider('option', 'max', parseInt($(this).val())+100);

	});
	
	
	
	
	
	  $('#mw_html_css_editor .style-item').die('click')
	 $('#mw_html_css_editor .style-item').live('click',function(){
	
	$style = $(this).attr('data-style-name');
	$style_url = $(this).attr('data-style-url');
	
	mw_html_tag_editor_apply_custom_element_style($style, $style_url);
	//alert($style);
	//  mw_html_tag_editor_apply_styles()
	//  mw_slider_name = $(this).attr('for');
	  
	  
	 // $('[mw_slider_name="'+mw_slider_name+'"]').slider('option', 'value', parseInt($(this).val()));
	//   $('[mw_slider_name="'+mw_slider_name+'"]').slider('option', 'max', parseInt($(this).val())+100);

	});
	
	
			// upl_options = { 
//       beforeSubmit: function(a,f,o) {
//            o.dataType = 'json';
//            $('#uploadOutput').html('Submitting...');
//        },
//        success: function(data) {
//		//	alert(data);
//				$element = $('#mw_css_editor_element_id').val();
//		  $('*[mw_tag_edit="'+$element+'"]').attr("src", data.url);
//			 $('#mw_img_size_set').val(data.width+'x'+data.height);
//			
//			
//           // var $out = $('#uploadOutput');
////            $out.html('Form success handler received: <strong>' + typeof data + '</strong>');
////            if (typeof data == 'object' && data.nodeType)
////                data = elementToString(data.documentElement, true);
////            else if (typeof data == 'object')
////                data = objToString(data);
////            $out.append('<div><pre>'+ data +'</pre></div>');
//        }
//    }; 							   
//						   
//						   
//    $('#uploadForm').ajaxForm(upl_options);

//$('#uploadForm').submit(function() { 
//        // inside event callbacks 'this' is the DOM element so we first 
//        // wrap it in a jQuery object and then invoke ajaxSubmit 
//        $(this).ajaxSubmit(upl_options); 
// 
//        // !!! Important !!! 
//        // always return false to prevent standard browser submit and page navigation 
//        return false; 
//    }); 

 $("#mw_edit_link, #mw_edit_link_window").live("click blur", function(){
  //$(this).after("<p>Another paragraph!</p>");
  	$elementz = $('#mw_css_editor_element_id').val();
		element = $('a[mw_tag_edit="'+$elementz+'"]:first' ,'.edit');
		  
		  link1= $("#mw_edit_link").val();
			 
					element.attr("href",link1 );
					
						  link1_target= $("#mw_edit_link_window").val();
			 if(link1_target != ''){
					element.attr("target",link1_target );
			 }
				 
			

		  
		  
		  
});

$("#mw_edit_image_link").live("blur", function(){
  //$(this).after("<p>Another paragraph!</p>");
  	$elementz = $('#mw_css_editor_element_id').val();
		element = $('*[mw_tag_edit="'+$elementz+'"]:first' ,'.edit');
		  
		  link1= $("#mw_edit_image_link").val();
				 if (!element.parent().is("a")) {
				  element.wrap("<a>")
				}  
				element.parent().attr("href",link1 );

		  
		  
		  
});




	 var available_img_sizes = [
			"Original",
			"128x128",
			 
			"192x192",
			"250x250",
			"320x250",
			"400x250"
		];
		$( ".mw_img_size" ).autocomplete({
			minLength: 0,
			close: function(event, ui) {
				 
				 
				 	$v = $('.mw_img_size').val();			
					
					if($v == 'Original'){
						$('*[name="width"]', '#mw_html_css_editor').val('auto');
						$('*[name="height"]', '#mw_html_css_editor').val('auto');
					} else {
					
							$v =$v.split("x");		
							if($v[0] != undefined){
							$('*[name="width"]', '#mw_html_css_editor').val($v[0]);
							}
							if($v[1] != undefined){
							$('*[name="height"]', '#mw_html_css_editor').val($v[1]);
							}
				
					}
				mw_html_tag_editor_apply_styles()
				 
				 
				 
				 
				 
				 
				 
				 
				 
				},
				
				change: function(event, ui) { 
				
				
					$v = $('.mw_img_size').val();			
					
					if($v == 'Original'){
						$('*[name="width"]', '#mw_html_css_editor').val('');
						$('*[name="height"]', '#mw_html_css_editor').val('');
					} else {
					
							$v =$v.split("x");		
							if($v[0] != undefined){
							$('*[name="width"]', '#mw_html_css_editor').val($v[0]);
							}
							if($v[1] != undefined){
							$('*[name="height"]', '#mw_html_css_editor').val($v[1]);
							}
				
					}
				mw_html_tag_editor_apply_styles()
				
				
				
				
				},
				
			source: available_img_sizes
		});
		
		
 
		  
		   $('.mw_img_size').live('click',function(){ 
												   
												   
												  
       $(this).autocomplete("search", "")
	   
	   
	   
	   
	   
	   
	    //$(this).trigger('keydown.autocomplete');
    });
		   
		   
		   
		   
 
				 
				 
				 
		   
		   
		   
	 
		 
		   
		   
		   
		   
});





























//
// Author: codefuture.co.uk
// Version: 0.1
// Date: 1-Jan-11
//
// Copyright (c) 2010 codefuture.co.uk
//
////////////////////////////////////////////////////////////////////////////////////
var NOT_SUPPORTED_TYP = 0;
var AGENT_STYLE = "";
function findPrefixe(){
var properties = [
'transform',
'WebkitTransform',
'MozTransform',
'msTransform',
'OTransform'
];
var p;
while (p = properties.shift()) {
if (typeof document.getElementById('transforms-box').style[p] != 'undefined') {
AGENT_STYLE = p;
NOT_SUPPORTED_TYP = 1;
}
}
if (NOT_SUPPORTED_TYP === 0) document.getElementById('transforms-box').innerHTML = 'CSS3 transform is not<br/>supported by your browser!';;
}
function setTransform(){
if (AGENT_STYLE === "")findPrefixe();
var radiusBox = document.getElementById('transforms-box');
var scaleInput = document.getElementById('scale-input').value;
var rotateInput = document.getElementById('rotate-input').value;
var tranInput1 = document.getElementById('tran1-input').value;
var tranInput2 = document.getElementById('tran2-input').value;
var skewInput1 = document.getElementById('skew1-input').value;
var skewInput2 = document.getElementById('skew2-input').value;
if (NOT_SUPPORTED_TYP === 1) radiusBox.style[AGENT_STYLE] = " scale(" + scaleInput + ") rotate(" + rotateInput +"deg) translate(" + tranInput1 + "px, " + tranInput2 + "px) skew(" + skewInput1 + "deg, " + skewInput2 + "deg)";
outCSS();
}
function outCSS(){
var scaleInput = document.getElementById('scale-input').value;
var rotateInput = document.getElementById('rotate-input').value;
var tranInput1 = document.getElementById('tran1-input').value;
var tranInput2 = document.getElementById('tran2-input').value;
var skewInput1 = document.getElementById('skew1-input').value;
var skewInput2 = document.getElementById('skew2-input').value;
var clearCss = 0;
transformCss = "transform:";
if(scaleInput != 1){
transformCss += " scale(" + scaleInput + ")";
clearCss = 1;
}
if(rotateInput != 0){
transformCss += " rotate(" + rotateInput +"deg)";
clearCss = 1;
}
if(tranInput1 != 0 || tranInput2 != 0){
transformCss += " translate(" + tranInput1 + "px, " + tranInput2 + "px)";
clearCss = 1;
}
if(skewInput1 != 0 || skewInput2 != 0){
transformCss += " skew(" + skewInput1 + "deg, " + skewInput2 + "deg)";
clearCss = 1;
}
transformCss += ";\n";
text = "-moz-" + transformCss;
text += "-webkit-" + transformCss;
text += "-o-" + transformCss;
text += "-ms-" + transformCss;
text += transformCss;
if(clearCss) document.getElementById('transforms_cssOut').value = text;
else document.getElementById('transforms_cssOut').value = "";


mw_html_tag_editor_apply_styles()
}

</script>