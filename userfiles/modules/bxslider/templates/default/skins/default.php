<?php
if ($slide['images'][0] == false) {
    $image = pixum(1000, 300);
} else {
    $image = $slide['images'][0];
}
?>
<a href="<?php if (isset($slide['url'])) {
    print $slide['url'];
} ?>"><img src="<?php print $image; ?>" title="<?php echo $slide['primaryText']; ?> - <?php echo $slide['secondaryText']; ?>" alt=""/></a>
