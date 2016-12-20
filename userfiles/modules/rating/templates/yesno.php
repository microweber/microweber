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

<select  autocomplete="off" class="mw-rating-controller" data-rel-type="<?php print $rel_type ?>"
         data-rel-id="<?php print $rel_id ?>">
    <option value="0" <?php if(!$ratings) { ?> selected <?php } ?>><?php print $title ?></option>
    <option value="5" <?php if($ratings == 5) { ?> selected <?php } ?>>Yes</option>
    <option value="1" <?php if($ratings == 1) { ?> selected <?php } ?>>No</option>
</select>
