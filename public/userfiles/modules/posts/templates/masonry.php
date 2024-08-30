<?php

/*

type: layout

name: Masonry

description: Masonry

*/
?>


<?php  $rand = uniqid(); ?>
<div class="clearfix module-posts-template-masonry" id="posts-<?php print $rand; ?>">

    <?php if (!empty($data)): ?>


<script>mw.require("<?php print modules_url(); ?>posts/js/masonry.pkgd.min.js", true); </script>
<script>mw.moduleCSS("<?php print modules_url(); ?>posts/css/style.css"); </script>
<script>
    mw._masons = mw._masons || [];
    $(document).ready(function(){
        var m = mw.$('#posts-<?php print $rand; ?>');
        m.masonry({
          "itemSelector": '.masonry-item',
          "gutter":5
        });
        $(window).on('load', function(){
             m.masonry({
            "itemSelector": '.masonry-item',
            "gutter":5
          });
        });
        m[0].masonryWidth = m.width();
        mw._masons.push(m);
        if(typeof mw._masons_binded === 'undefined'){
            mw._masons_binded = true;
               setInterval(function(){
                 var l = mw._masons.length, i=0;
                 for( ; i<l; i++){
                   var _m = mw._masons[i];
                   if(_m[0].masonryWidth != _m.width() && mw.$(".masonry-item", _m[0]).length > 0){
                       _m.masonry({
                          "itemSelector": '.masonry-item',
                          "gutter":5
                       });
                       _m[0].masonryWidth = _m.width();
                   }
                 }
               }, 700);
        }

    });
</script>





    <?php
        $count = -1;
        foreach ($data as $item):
        $count++;
    ?>

    <div class="masonry-item" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <a class="" itemprop="url" href="<?php print $item['link'] ?>">
                <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> itemprop="image" src="<?php print thumbnail($item['image'], 290, 120); ?>" alt="<?php print addslashes($item['title']); ?> - <?php _e("image"); ?>" title="<?php print addslashes($item['title']); ?>" />
            </a>
        <?php endif; ?>
        <div class="masonry-item-container">
        <div class="module-posts-head">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
            <h3  itemprop="name"><a class="lead" href="<?php print $item['link'] ?>"  itemprop="url"><?php print $item['title'] ?></a></h3>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
            <small class="muted" itemprop="dateCreated"><?php _e("Posted on"); ?>: <?php print $item['created_at']; ?></small>
        <?php endif; ?>
        </div>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
            <p class="description" itemprop="description"><?php print $item['description'] ?></p>
        <?php endif; ?>


      <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
      <div class="blog-post-footer">
        <a href="<?php print $item['link'] ?>" itemprop="url" class="btn btn-default pull-fleft">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        <i class="icon-chevron-right"></i></a>
      </div>
      <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>



    <?php endif; ?>


</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
