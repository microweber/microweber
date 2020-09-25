<?php must_have_access(); ?>
<script  type="text/javascript">
    $(document).ready(function(){

        mw.options.form('.<?php print $config['module_class'] ?>', function(){
            mw.clear_cache();
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");

        });
    });
</script>
<h3>UI Colors</h3>


<?php
$vars = [
    'white' => '#fff',
    'black' => '#000',
    'silver' => '#bcbfc2',

    'primary' => '#4592ff',
    'secondary' => '#eeefef',
    'success' => '#3dc47e',
    'info' => '#e1f1fd',
    'warning' => '#ffc107',
    'danger' => '#ff4f52',
    'light' => '#f8f9fa',
    'dark' => '#2b2b2b',

    'body-bg' => '#fff',
    'body-color' => '#212529'
];




//$data = json_decode($settings, true);



//
//foreach ($data as $datum) {
//
//
//
//
//}


?>

