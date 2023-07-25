<?php must_have_access(); ?>
<?php

$options = [
    'quickAdd' => true
];
print view('admin::layouts.partials.add-content-buttons', $options);


?>
