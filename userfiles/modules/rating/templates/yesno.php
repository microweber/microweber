<?php

/*

type: layout

name: Yes-No

description: Yes or No

*/

$title = 'Select';

if(isset($params['title'])){
    $title = $params['title'];
}


?>
<script type="text/javascript">
    mw.require('lib.js');
    mw.require('rating.js');
</script>

<select  autocomplete="off" class="mw-rating-controller" data-rel-type="<?php print $rel_type ?>"
         data-rel-id="<?php print $rel_id ?>">
    <option value="0" <?php if(!$ratings) { ?> selected <?php } ?>><?php print $title ?></option>
    <option value="5" <?php if($ratings == 5) { ?> selected <?php } ?>><?php _e('Yes'); ?></option>
    <option value="1" <?php if($ratings == 1) { ?> selected <?php } ?>><?php _e('No'); ?></option>
</select>
