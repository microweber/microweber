<!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <title><?php _e('Login'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">




    <script>
        mwAdmin = true;
        admin_url = '<?php print admin_url(); ?>';
    </script>


    <?php print mw_admin_header_scripts() ;    ?>



</head>

<body class="is_admin loading">

<module type="users/login" template="admin" />



<?php print mw_admin_footer_scripts() ;    ?>


</body>
</html>
