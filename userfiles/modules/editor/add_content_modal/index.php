<?php must_have_access(); ?>

<a href="<?php print route('admin.content.builder.index') ?>">Add post</a><?php

$options = [
    'quickAdd' => true
];
print view('admin::layouts.partials.add-content-buttons', $options);


?>
