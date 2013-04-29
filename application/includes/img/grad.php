<?php 

$expires= 60*60 * 60 * 24 * 14;
header('Pragma: public');
  $etag = '"'.md5(serialize($_GET)).'"';

header('Cache-Control: max-age=' . $expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT'); 
header("Content-type: image/svg+xml");
header('Last-Modified:'. gmdate('D, d M Y H:i:s', time() - $expires) . ' GMT');
  header('ETag:'.$etag);
 ?>
<?php echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'; ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="100%" height="100%" version="1.1" preserveAspectRatio="xMinYMin none" xmlns="http://www.w3.org/2000/svg">
	<defs>
		<linearGradient id="round-gradient-box" x1="0%" y1="0%" x2="0%" y2="100%">
			<stop offset="0%" style="stop-color:#<?php echo $_GET['top']; ?>;stop-opacity:1"/>
			<stop offset="100%" style="stop-color:#<?php echo $_GET['bot']; ?>;stop-opacity:1"/>
		</linearGradient>
	</defs>
	<rect x="0" y="0" width="100%" height="100%" style="fill:url(#round-gradient-box)"/>
</svg>
<? exit(); ?>