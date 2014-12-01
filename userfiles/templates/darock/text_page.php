<?php

/*

type: layout
content_type: static
name: About Page
position: 1
is_default: true
description: About Page

*/


?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<section id="content"> 
  <div class="container">
    <div class="box-container">
        <h2 class="edit" field="title" rel="content">Lorem Ipsum</h2>
        <div class="edit richtext" field="content" rel="content">

            <p><img src="<?php print TEMPLATE_URL; ?>img/about_me.jpg"></p>
            <p>Information about me</p>
            <p>Nunc eu felis fringilla enim luctus tincidunt. Sed sit amet gravida lorem, tincidunt varius erat. Fusce mi purus, tincidunt ultricies ultrices vel, auctor ut diam. Pellentesque non scelerisque leo, nec cursus lectus.</p>
            <p>Donec suscipit turpis ac leo dictum ornare sed ac dolor. Suspendisse ultrices dui vel posuere tincidunt. Vivamus gravida, massa nec varius lobortis, lacus magna dignissim dolor, nec vestibulum nunc odio non nulla.</p>
        </div>
    </div>
  </div>
</section>


<?php include TEMPLATE_DIR. "footer.php"; ?>
