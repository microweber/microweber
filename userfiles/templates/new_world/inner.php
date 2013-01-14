<?php

/*

type: layout
content_type: post
name: Post inner layout

description: Post inner layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<article id="post-<? print POST_ID ?>" class="post">
  <h2 class="edit no-drop"    rel="content"  data-field="title"  data-id="<? print POST_ID ?>"  >My post title</h2>
  <div class="edit"  rel="content"  data-field="content" data-id="<? print POST_ID ?>"  >
    <div>
      <p>You are able to edit this text directly here. <br />
        Microweber is the best drag and drop content management system (CMS) with live edit functionality. Explore all functions and play with the modules on the top in the live edin mode. You also have full control of your website using the admin panel. You are able to make new pages, posts, add products, manage comments, manage users, orders and much more. Try to change your design and choose the best look for your website, blog or online shop.</p>
      <p>Be Free to Express Yourself on Internet as Never Before ...</p>
    </div>
  </div>
  <div id="cart_item">
    <module type="custom_fields" for="content" for_id="<? print POST_ID ?>"  />
    qty:
    <input name="qty" type="text"  />
  </div>
  <button onclick="mw.cart.add('#cart_item')">Add to cart</button>
  <module type="shop/cart" id="cart_inner" />
</article>
<module data-type="comments" id="comments_posts" data-content-id="<? print POST_ID ?>"  />
<? include   TEMPLATE_DIR.  "footer.php"; ?>
