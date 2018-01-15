<?php

/*

  type: layout
  content_type: static
  name: Home
  position: 2
  description: Home layout

*/

?>
<?php include TEMPLATE_DIR . 'header.php'; ?>

<div class="edit" rel="content" field="liteness_content">
    <module type="layouts" template="skin-2"/>
    <module type="layouts" template="skin-3"/>
    <module type="layouts" template="skin-4"/>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>