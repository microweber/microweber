<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="grunt/plugins/ui/css/main.css">
    <link rel="stylesheet" id="main-css-style" href="grunt/plugins/ui/css/main.php">

    <!-- MW UI plugins CSS -->
    <link rel="stylesheet" href="assets/ui/plugins/css/plugins.min.css"/>

    <!-- MW Admin CSS -->
    <?php if (is_file('assets/ui/plugins/css/mw.css')): ?>
        <link rel="stylesheet" href="assets/ui/plugins/css/mw.css">
    <?php else: ?>
        <link rel="stylesheet" href="grunt/plugins/ui/css/mw.css">
    <?php endif; ?>

    <title>Microweber CMS</title>

    <script src="assets/ui/plugins/js/jquery-3.4.1.min.js"></script>
</head>
<body class="">


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="assets/ui/plugins/js/plugins.js"></script>
<script src="grunt/plugins/ui/js/ui.js"></script>

<?php include 'partials/template_colors.php'; ?>
</body>
</html>