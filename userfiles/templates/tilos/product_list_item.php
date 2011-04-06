
<div class="product_item product_item_slide"> <a class="product" href="<? print post_link($the_post['id']) ?>"> <span class="img" style="background-image: url('<? print thumbnail($the_post['id'], 250);  ?>')">&nbsp;</span>
<strong>  <editable  post="<? print $the_post['id'] ?>" field="content_title"><? print $the_post['content_title'] ?></editable> </strong>
  <span class="best_seller">&nbsp;</span> </a>
  <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>

  <a href="<? print post_link($the_post['id']) ?>" class="lbuy right">Details</a> </div>
