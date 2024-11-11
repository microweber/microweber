<?php

/*

  type: layout
  content_type: static
  name: Menu - skin-5
  position: 5
  description: Menu - skin-5
  categories: Menu


*/

?>


<section class="header-background">

    <nav class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <module type="logo" id="header-logo-<?php print $params['id']; ?>" class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start"/>


                <module type="menu" name="header_menu" id="header_menu" template="navbar" class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0"/>


                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
                </form>

                <div class=" text-end">
                    <?php  include template_dir() . "partials/header/parts/profile_link.php"; ?>
                </div>
            </div>
        </div>
    </nav>
</section>
