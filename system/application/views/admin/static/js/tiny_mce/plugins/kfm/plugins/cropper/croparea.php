<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js" language="javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.1/scriptaculous.js?load=effects,builder,dragdrop" language="javascript"></script>
<script type="text/javascript" src="cropper.js" language="javascript"></script>
<script type="text/javascript" language="javascript">
var imageid=<?php print $_GET['id'];?>;
var coordinates=null;
var dimensions=null;
Event.observe( window, 'load', function() {
	new Cropper.Img(
		'cropImage',
		{ onEndCrop: onEndCrop }
	);
} );
function onEndCrop( coords, dims ) {
	coordinates=coords;
	dimensions=dims;
}
</script>
<style type="text/css">
img{
	display:block;
	margin:0;
}
body{
	margin:0;
	padding:0;
}
</style>
</head>
<body>
<img src="<?php echo preg_replace('#plugins/cropper.*#','',$_SERVER['REQUEST_URI']); ?>get.php?id=<?php print $_GET['id'].'&'.rand(1,500).GET_PARAMS;?>" alt="Crop image" id="cropImage" width="<?php print $_GET['width'];?>" height="<?php print $_GET['height'];?>" />
</body>
</html>
