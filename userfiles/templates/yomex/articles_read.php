<style>
.faqholder ul{
  padding-left: 16px;
}


</style>

<?php $post_more = $this->core_model->getCustomFields($table = 'table_content', $id = $post['id']); ?>
<?php include (ACTIVE_TEMPLATE_DIR."offers_heading_nav.php"); ?>
<div class="holder">
  <div class="hleft">
    <div class="list-offers gr">
    <a class="seeall back right" href="javascript:void(0);">Върни се обратно</a>
      <h2 class="gtitle"> <?php print ($post['content_title']); ?>

      </h2>

    </div>
        <div class="box list-offers faqholder"><?php print ($post['content_body']); ?></div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."articles_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder -->