<?php

/*

type: layout

name: My cool name

description: Home site layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="MagicTheme" class="mw_holder">
     <h2 class="edit no-drop"    rel="content"  data-field="title"  data-id="<? print POST_ID ?>"  >My post title</h2>
    <div class="edit"  rel="content"  data-field="content" data-id="<? print POST_ID ?>"  >
      <div class="row">
        <div class="column">
          <h2 class="element"><span>Simple Cool Text</span></h2>
          <div class="element">
            <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer tooasdasdasrinting and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.  Aldus PageMaker including versions of Lorem Ipsum. </p>
          </div>
        </div>
      </div>
      <div class="c">&nbsp;</div>
      <div class="row">
        <div class="column" style="width: 450px;">
          <h2 class="element"><span>My post text</span></h2>
        </div>
        <div class="column" style="width: 450px;">
          <div class="element">
            <p> Aliquam lectus lorem, euismod at lacinia in, tempus elementum odio. Ut id tortor urna, in pretium augue. Proin et tincidunt ipsum. Maecenas mauris tortor, gravida id vehicula in, ultricies non risus. Cras vel nisl augue, a suscipit enim. Aenean fermentum imperdiet lorem, in elementum lectus rutrum non. Quisque vel eros a ante hendrerit porta. Nam congue arcu in eros pretium posuere. Phasellus sagittis tristique nisl, quis consequat ligula ultrices mollis. In id neque orci. Ut id augue nec sapien pulvinar faucibus ut quis dui. Mauris bibendum dignissim mauris non rhoncus. Maecenas tempus sapien ut arcu porta accumsan. Curabitur ullamcorper nunc tellus, ac lacinia erat. Nam aliquet fringilla risus sed dapibus. </p>
          </div>
        </div>
      </div>
    </div>
    <div class="c">&nbsp;</div>
    <div id="cart_item">
      <module type="custom_fields" for="content" for_id="<? print POST_ID ?>"  />
      qty:
      <input name="qty" type="text"  />
    </div>
    <button onclick="mw.cart.add('#cart_item')">Add to cart</button>
    <module type="shop/cart" id="cart_inner" />
    <div class="c">&nbsp;</div>
   <module data-type="comments" id="comments_posts" data-content-id="<? print POST_ID ?>"  />
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
