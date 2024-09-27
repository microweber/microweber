<div class="row">

    <div class="col-6">
        <img src="{{$product->thumbnail(900,900, true)}}" />
    </div>
    <div class="col-6 p-5 align-self-center">

        <a href="{{content_link($product->id)}}">
            <h5 class="pb-3">{{$product->title}}</h5>
        </a>

        <div class="row justify-content-center">
            <div class="col-6 col-md-12 price-holder">
                <p>
                    <span class="price"><?php print currency_format($product->price); ?></span>
                </p>
            </div>

            <div class="d-flex mt-5">
                <?php if ($product->inStock == true): ?>
                <a href="javascript:;" onclick="mw.cart.add_item('{{$product->id}}','{{$product->price}}', '{{$product->title}}')" class="btn btn-outline-primary"><i class="mw-micon-Shopping-Cart"></i> <?php _lang("Add to cart", 'templates/shopmag') ?></a>
                <?php else: ?>
                    <span class="text-danger p-1"><i class="material-icons" style="font-size: 18px;">remove_shopping_cart</i> <?php _lang("Out of Stock", 'templates/shopmag') ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="content_id" value="{{$product->id}}"/>
</div>
