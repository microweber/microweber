<?php

/*

  type: layout
  content_type: static
  name: Menu - skin-2
  position: 2
  description: Menu - skin-2
  categories: Menu


*/

?>


<section class="header-background">
    <div class="container">
        <nav class="nav-pills d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">

            <module type="logo" id="header-logo-<?php print $params['id']; ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none"/>

            <module type="menu" name="header_menu" id="header_menu" template="navbar" class="d-flex flex-direction-row nav-holder"/>

        </nav>
    </div>
</section>
