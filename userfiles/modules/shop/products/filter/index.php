<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$currencySymbol = mw()->shop_manager->currency_symbol();

$price = 0;
if (isset($_GET['priceBetween'])) {
    $price = $_GET['priceBetween'];
}

$minPrice = $price;
$maxPrice = false;

if (strpos($price, ',') !== false) {
    $price = explode(',', $price);
    $minPrice = $price[0];
    $maxPrice = $price[1];
}

$minPrice = intval($minPrice);
$maxPrice = intval($maxPrice);

?>

<script>
    SHOP_PRODUCTS_MIN_PRICE = 0;
    SHOP_PRODUCTS_MAX_PRICE = 100;
    $( function() {
        $( "#slider-range" ).slider({
            range: true,
            min: SHOP_PRODUCTS_MIN_PRICE,
            max: SHOP_PRODUCTS_MAX_PRICE,
            values: [ <?php echo $minPrice;?>, <?php echo $maxPrice;?> ],
            slide: function( event, ui ) {

                $('.js-shop-products-price-between').val(ui.values[ 0 ] +','+ ui.values[ 1 ]);

                $( "#amount" ).val( "<?php echo $currencySymbol;?>" + ui.values[ 0 ] + " - <?php echo $currencySymbol;?>" + ui.values[ 1 ] );
            }
        });
        $( "#amount" ).val( "<?php echo $currencySymbol;?>" + $( "#slider-range" ).slider( "values", 0 ) +
            " - <?php echo $currencySymbol;?>" + $( "#slider-range" ).slider( "values", 1 ) );
    } );
</script>


<div class="js-shop-product-filter">

    <form method="get" action="">

    <p>
        <label for="amount">Price range:</label>
        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
    </p>

        <input type="hidden" class="js-shop-products-price-between" name="priceBetween" />

    <div id="slider-range" class="mb-4"></div>


    <?php
    $currentUrl = mw()->url_manager->current();
    /*if (strpos($currentUrl, '?') !== false) {
        $currentUrl = $currentUrl . '&';
    } else {
        $currentUrl = $currentUrl . '?';
    }*/
    $currentUrl = strtok($currentUrl, '?');
    ?>


        <?php
        $orderBy = '';
        if (isset($_GET['orderBy'])) {
            $orderBy = $_GET['orderBy'];
        }
        ?>
        <div class="form-group mb-4">
            <span>Sort</span>
            <select class="form-control" onchange="location = this.value;">
                <option value="<?php echo $currentUrl; ?>">Select</option>
                <option value="<?php echo $currentUrl; ?>?orderBy=id,desc" <?php if ($orderBy=='id,desc'):?> selected="selected" <?php endif;?>>Newest</option>
                <option value="<?php echo $currentUrl; ?>?orderBy=id,asc" <?php if ($orderBy=='id,asc'):?> selected="selected" <?php endif;?>>Oldest</option>
            </select>
        </div>


        <?php
        $limit = '';
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
        }
        ?>
        <div class="form-group mb-4">
            <span>Limit</span>
            <select class="form-control" onchange="location = this.value;">
                <option value="<?php echo $currentUrl; ?>">Select</option>
                <option value="<?php echo $currentUrl; ?>?limit=5" <?php if ($limit==5):?> selected="selected" <?php endif;?>>5</option>
                <option value="<?php echo $currentUrl ?>?limit=10" <?php if ($limit==10):?> selected="selected" <?php endif;?>>10</option>
                <option value="<?php echo $currentUrl; ?>?limit=15" <?php if ($limit==15):?> selected="selected" <?php endif;?>>15</option>
                <option value="<?php echo $currentUrl; ?>?limit=20" <?php if ($limit==20):?> selected="selected" <?php endif;?>>20</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mb-4"><i class="fa fa-filter"></i> Filter</button>



    </form>
</div>