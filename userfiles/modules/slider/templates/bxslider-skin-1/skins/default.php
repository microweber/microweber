<div>
    <?php if (isset($slide['primaryText'])) {
        $primaryText = $slide['primaryText'];

    }
    if (isset($slide['url'])) {
        $url = $slide['url'];
    } else {
        $url = 'javascript:;';
    }
    ?>

    <a href="<?php print $url; ?>"><img src="<?php if (isset($slide['images'][0])) { ?><?php print $slide['images'][0]; ?><?php } ?>" title="<?php echo $primaryText; ?>"/></a>
</div>