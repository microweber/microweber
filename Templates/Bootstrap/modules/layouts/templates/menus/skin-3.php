<?php

/*

  type: layout
  content_type: static
  name: Menu - skin-3
  position: 3
  description: Menu - skin-3
  categories: Menu


*/

?>


<section class="header-background">
    <div class="container">
        <nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <module type="logo" id="header-logo-<?php print $params['id']; ?>" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none"/>
            <module type="menu" name="header_menu" id="header_menu" template="navbar" class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0"/>


            <div class="col-md-3 text-end d-flex">
                <module type="btn" button_text="login" button_style="btn btn-outline-primary me-2"/>
                <module type="btn" button_text="Sign-up" button_style="btn btn-primary"/>
            </div>
        </nav>
    </div>
</section>
