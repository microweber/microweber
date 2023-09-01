<?php must_have_access(); ?>


<?php
/*<a href="<?php print route('admin.content.builder.index') ?>">Add post</a><?php
*/

?>
<?php

$options = [
    'quickContentAdd' => true
];


if(isset($_GET['show_edit_content_button']) and isset($_GET['content_id'])){
    $options['showEditContentButtonForContentId'] = intval($_GET['content_id']);
}

print view('admin::layouts.partials.add-content-buttons', $options);


?>
