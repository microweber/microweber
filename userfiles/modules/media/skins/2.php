<script type="text/javascript">

$(document).ready(function() {		
	
	//Execute the slideShow
	slideShow<? print $params['module_id'] ?>();

});

function slideShow<? print $params['module_id'] ?>() {

	//Set the opacity of all images to 0
	$('#gallery<? print $params['module_id'] ?> a').css({opacity: 0.0});
	
	//Get the first image and display it (set it to full opacity)
	$('#gallery<? print $params['module_id'] ?> a:first').css({opacity: 1.0});
	
	//Set the caption background to semi-transparent
	$('#gallery<? print $params['module_id'] ?> .caption').css({opacity: 0.7});

	//Resize the width of the caption according to the image width
	$('#gallery<? print $params['module_id'] ?> .caption').css({width: $('#gallery<? print $params['module_id'] ?> a').find('img').css('width')});
	
	//Get the caption of the first image from REL attribute and display it
	$('#gallery<? print $params['module_id'] ?> .content').html($('#gallery<? print $params['module_id'] ?> a:first').find('img').attr('rel'))
	.animate({opacity: 5.7}, 400);
	
	//Call the gallery<? print $params['module_id'] ?> function to run the slideshow, 6000 = change to next image after 6 seconds
	setInterval('gallery<? print $params['module_id'] ?>()',6000);
	
}

function gallery<? print $params['module_id'] ?>() {
	
	//if no IMGs have the show class, grab the first image
	var current = ($('#gallery<? print $params['module_id'] ?> a.show')?  $('#gallery<? print $params['module_id'] ?> a.show') : $('#gallery<? print $params['module_id'] ?> a:first'));

	//Get next image, if it reached the end of the slideshow, rotate it back to the first image
	var next = ((current.next().length) ? ((current.next().hasClass('caption'))? $('#gallery<? print $params['module_id'] ?> a:first') :current.next()) : $('#gallery<? print $params['module_id'] ?> a:first'));	
	
	//Get next image caption
	var caption = next.find('img').attr('rel');	
	
	//Set the fade in effect for the next image, show class has higher z-index
	next.css({opacity: 0.0})
	.addClass('show')
	.animate({opacity: 1.0}, 1000);

	//Hide the current image
	current.animate({opacity: 0.0}, 1000)
	.removeClass('show');
	
	//Set the opacity to 0 and height to 1px
	$('#gallery<? print $params['module_id'] ?> .caption').animate({opacity: 0.0}, { queue:false, duration:0 }).animate({height: '1px'}, { queue:true, duration:500 });	
	
	//Animate the caption, opacity to 0.7 and heigth to 100px, a slide up effect
	$('#gallery<? print $params['module_id'] ?> .caption').animate({opacity: 0.7},1000 ).animate({height: '100px'},1500 );
	
	//Display the content
	$('#gallery<? print $params['module_id'] ?> .content').html(caption);
	
	
}

</script>
<style type="text/css">
 
#gallery<? print $params['module_id'] ?> {
	position:relative;
	height:360px
}
#gallery<? print $params['module_id'] ?> a {
	float:left;
	position:absolute;
}
#gallery<? print $params['module_id'] ?> a img {
	border:none;
}
#gallery<? print $params['module_id'] ?> a.show {
	z-index:500
}
#gallery<? print $params['module_id'] ?> .caption {
	z-index:600;
	background-color:#000;
	color:#ffffff;
	height:100px;
	width:100%;
	position:absolute;
	bottom:0;
}
#gallery<? print $params['module_id'] ?> .caption .content {
	margin:5px
}
#gallery<? print $params['module_id'] ?> .caption .content h3 {
	margin:0;
	padding:0;
	color:#1DCCEF;
}
</style>
 
<h1>JQuery Photo Slider / Semi Transparent</h1>
<div id="gallery<? print $params['module_id'] ?>"> 







 <?php $i = 1; if(!empty($media)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], $size);
	 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
//p($thumb);
?>
<a href="#"   <? if($i == 1) : ?>  class="show" <?php endif; ?>><center><img src="<? print  $orig; ?>" alt="<?php print addslashes($pic['media_name']); ?>"  height="360" title=""   rel="<?php print addslashes($pic['media_description']); ?>"/></center></a>


 
    <?php $i++; endforeach; ?>
    <?php endif; ?>
    
    
    
 
  <div class="caption">
    <div class="content"></div>
  </div>
</div>
<div class="clear"></div>
<br>
<br>

 
