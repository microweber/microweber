<?php

/*

  type: layout
  content_type: dynamic
  name: Blog
  position: 5
  description: Blog
  tag: blog

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="mw-wrapper content-holder">
    <div class="edit" rel="page" field="content">
        <module content-id="<?php print PAGE_ID; ?>" type="posts" template="clean" />
    </div>
</div>

<?php include TEMPLATE_DIR. "footer.php"; ?>
