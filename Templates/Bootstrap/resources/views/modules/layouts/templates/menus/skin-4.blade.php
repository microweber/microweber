<?php

/*

  type: layout
  content_type: static
  name: Menu - skin-4
  position: 4
  description: Menu - skin-4
  categories: Menu


*/

?>


<section class="header-background">
    <nav class="p-3 bg-dark nav-pills text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <module type="logo" id="header-logo-<?php print $params['id']; ?>" class="px-3 d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none"/>



                <module type="menu" name="header_menu" id="header_menu" template="navbar" class="text-white  px-3 nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0"/>





                <div class="text-end d-flex">
                    <module type="btn" button_text="login" button_style="btn btn-outline-light me-2"/>
                    <module type="btn" button_text="Sign-up" button_style="btn btn-warning"/>
                </div>


            </div>
        </div>
    </nav>
</section>
