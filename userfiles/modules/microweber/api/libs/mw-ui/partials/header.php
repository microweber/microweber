<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php if (is_file('grunt/plugins/ui/css/main_compiled.css')): ?>
        <link rel="stylesheet" id="main-css-style" href="grunt/plugins/ui/css/main.php">
    <?php elseif (is_file('assets/ui/plugins/css/main.css')): ?>
        <link rel="stylesheet" href="assets/ui/plugins/css/main_with_mw.css">
    <?php else: ?>
        <link rel="stylesheet" href="grunt/plugins/ui/css/main_with_mw.css">
    <?php endif; ?>

    <!-- MW UI plugins CSS -->
    <link rel="stylesheet" href="assets/ui/plugins/css/plugins.min.css"/>

    <!-- MW Admin CSS -->
    <?php if (!is_file('grunt/plugins/ui/css/main_compiled.css')): ?>
        <?php if (is_file('assets/ui/plugins/css/mw.css')): ?>
<!--            <link rel="stylesheet" href="assets/ui/plugins/css/mw.css">-->
        <?php else: ?>
<!--            <link rel="stylesheet" href="grunt/plugins/ui/css/mw.css">-->
        <?php endif; ?>
    <?php endif; ?>

    <!-- Other CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css"/>

    <title>Microweber CMS</title>

    <script src="assets/ui/plugins/js/jquery-3.4.1.min.js"></script>
</head>
<body class="">
<header class="position-sticky sticky-top bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-1">
            <ul class="nav">
                <li class="mx-1 mobile-toggle">
                    <button type="button" class="js-toggle-mobile-nav"><i class="mdi mdi-menu"></i></button>
                </li>

                <li class="mx-1 logo d-none d-md-block">
                    <h5 class="text-white mr-3 d-flex align-items-center h-100"><img src="assets/img/logo.svg" alt="Microweber" style="width: 170px;"/></h5>
                </li>

                <li class="mx-1 d-none d-md-block">
                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm-only-icon" data-bs-toggle="dropdown"><span class="d-none d-md-block">Add new</span> <i class="mdi mdi-plus"></i></button>
                    <div class="dropdown-menu ">
                        <a class="dropdown-item" href="#">Post</a>
                        <a class="dropdown-item" href="#">Product</a>
                    </div>
                </li>
            </ul>

            <ul class="nav">
                <!--                        <li class="mx-1 logo d-block d-md-none">
                                            <h5 class="text-white mr-md-3">
                                                <img src="assets/img/logo-mobile.svg" alt="Microweber" style="height: 40px;" />
                                            </h5>
                                        </li>-->

                <li class="mx-1"><a href="#" class="btn btn-link btn-rounded icon-left text-dark px-0"><span class="badge badge-success badge-pill mr-2">4</span> <i class="mdi mdi-shopping text-muted"></i> <span class="d-none d-md-block">New order</span></a>
                </li>
                <li class="mx-1"><a href="#" class="btn btn-link btn-rounded icon-left text-dark px-0"><span class="badge badge-success badge-pill mr-2">4</span> <i class="mdi mdi-comment-account text-muted"></i> <span
                                class="d-none d-md-block">New comment</span></a></li>
                <!--<li class="mx-1"><a href="#" class="btn btn-link btn-rounded icon-left text-dark px-0"><span class="badge badge-success badge-pill mr-2">4</span> <i class="mdi mdi-bell text-muted"></i> <span class="d-none d-md-block">New notification</span></a></li>-->
            </ul>

            <ul class="nav nav-last">
                <li class="mx-1">
                    <a href="#" class="btn btn-primary btn-rounded btn-sm-only-icon"><i class="mdi mdi-eye-outline"></i> <span class="d-none d-md-block ml-1">Live edit</span></a>
                </li>

                <li class="mx-1 language-selector">
                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon" data-bs-toggle="dropdown"><i class="flag-icon flag-icon-gb"></i></button>
                    <div class="dropdown-menu ">
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-us"></i> English</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<div class="main container my-3">
    <aside>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="mdi mdi-view-dashboard"></i> Dashboard</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" href="javascript:;"><i class="mdi mdi-earth"></i> Website</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Pages <span class="btn btn-primary btn-rounded btn-icon btn-sm add-new" onClick="javascript:return false;" data-bs-toggle="tooltip" title="New page"><i class="mdi mdi-plus"></i></span></a>
                    <a class="dropdown-item active" href="#">Posts <span class="btn btn-primary btn-rounded btn-icon btn-sm add-new" onClick="javascript:return false;" data-bs-toggle="tooltip" title="New post"><i
                                    class="mdi mdi-plus"></i></span></a>
                    <a class="dropdown-item" href="products.php">Products <span class="btn btn-primary btn-rounded btn-icon btn-sm add-new" onClick="javascript:return false;" data-bs-toggle="tooltip" title="New product"><i
                                    class="mdi mdi-plus"></i></span></a>
                    <a class="dropdown-item" href="#">Categories <span class="btn btn-primary btn-rounded btn-icon btn-sm add-new" onClick="javascript:return false;" data-bs-toggle="tooltip" title="New category"><i
                                    class="mdi mdi-plus"></i></span></a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle"><i class="mdi mdi-shopping"></i> <span class="badge-holder">Shop<span class="badge badge-success badge-sm badge-pill">4</span></span></a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item active" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="mdi mdi-view-grid-plus"></i> Modules <span class="badge badge-sm badge-success badge-pill float-right">4</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="mdi mdi-fruit-cherries"></i> Marketplace</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:;"><i class="mdi mdi-cog"></i> <span class="badge-holder">Settings<span class="badge badge-danger badge-sm badge-pill">4</span></span></a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:;"><i class="mdi mdi-account-multiple"></i> Users</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="mdi mdi-power"></i> Log out</a></li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:;"><i class="mdi mdi-file-document"></i> Pages</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="dashboard.php">Dashboard</a>
                    <a class="dropdown-item" href="categories.php">Categories</a>
                    <a class="dropdown-item" href="categories_no.php">No categories</a>
                    <hr/>
                    <a class="dropdown-item" href="post_new.php">New Post</a>
                    <a class="dropdown-item" href="posts_no.php">No Posts</a>
                    <hr/>
                    <a class="dropdown-item" href="products.php">Products</a>
                    <a class="dropdown-item" href="product_new.php">New Product</a>
                    <a class="dropdown-item" href="products_no.php">No Products</a>
                    <hr/>
                    <a class="dropdown-item" href="notifications.php">Notifications</a>
                    <a class="dropdown-item" href="notifications_no.php">No notifications</a>
                </div>
            </li>
        </ul>
    </aside>
