<script type="text/javascript">
$(document).ready(function(){
  $(".chapter-link:empty").remove();


    //$(".chapter-link").css("backgroundColor", darkColor);
    $(".chapter-content").css("backgroundColor", lightColor);

});
</script> 
<?php $more = $this->core_model->getCustomFields('table_content', $post['id']);
	 if(!empty($more)){
		ksort($more);
	 }  ?>
<?php if(!empty($more)): ?>
 <?php // p($more); ?>
<?php $i = 1;
foreach($more as $k => $v): ?>
<?php if((stristr($k, 'content_body_') == true) and (stristr($k, '_title') == false)) : ?>
<?php // var_dump($k); ?>
<?php //var_dump($v); ?>
  <a href="#" class="chapter-link"><?php print html_entity_decode( $more['content_body_'.$i.'_title']);  ?></a>

    <?php // print ( $more[$k]);  ?>

   <div class="chapter-content"> <?php print html_entity_decode( $v);  ?> </div>


<?php //print html_entity_decode( $more['content_body_'.$i]);  ?>
<?php $i++; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
