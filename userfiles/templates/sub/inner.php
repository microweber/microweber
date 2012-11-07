<? include TEMPLATE_DIR. "header.php"; ?>

<section id="river">
  <article id="post-<? print POST_ID ?>" class="post">
    <h2 class="edit no-drop"    rel="content"  data-field="title"  data-id="<? print POST_ID ?>"  >My post title</h2>
    <div class="edit"  rel="content"  data-field="description" data-id="<? print POST_ID ?>"  >
      <p>My post description</p>
    </div>
    <div class="edit"  rel="content"  data-field="content" data-id="<? print POST_ID ?>"  >
      <p>My post content</p>
    </div>
    <div id="cart_item">
      <module type="custom_fields" for="content" for_id="<? print POST_ID ?>"  />
     qty: <input name="qty" type="text"  />
    </div>
    <button onclick="mw.cart.add('#cart_item')">Add to cart</button>
     <module type="shop/cart" id="cart_inner" /> 
  </article>
  <module data-type="comments" id="comments_posts" data-content-id="<? print POST_ID ?>"  />
</section>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
