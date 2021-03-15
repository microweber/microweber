<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php

$pageId = $params['content-id'];

$filters = [];
$getProducts = \MicroweberPackages\Product\Models\Product::where('parent', $pageId)->get();
if (!empty($getProducts)) {
    foreach ($getProducts as $product) {
        $customFields = $product->customField()->with('fieldValue')->get();
        foreach ($customFields as $customField) {
            $customFieldValues = $customField->fieldValue()->get();
            if (empty($customFieldValues)) {
                continue;
            }
            $filterOptions = [];
            foreach ($customFieldValues as $customFieldValue) {
                $filterOptions[] = [
                    'id'=>$customFieldValue->id,
                    'value'=>$customFieldValue->value,
                ];
            }
            $filters[$customField->name_key] = [
                'type'=>$customField->type,
                'name'=>$customField->name,
                'options'=>$filterOptions
            ];
        }
    }
}

$currencySymbol = mw()->shop_manager->currency_symbol();
$priceBetween = '0,10000';
if (isset($_GET['priceBetween'])) {
    $priceBetween = $_GET['priceBetween'];
}

$minPrice = $priceBetween;
$maxPrice = false;

if (strpos($priceBetween, ',') !== false) {
    $priceRange = explode(',', $priceBetween);
    $minPrice = $priceRange[0];
    $maxPrice = $priceRange[1];
}

$minPrice = intval($minPrice);
$maxPrice = intval($maxPrice);
?>

<script>
    $(function() {
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 300,
            values: [ <?php echo $minPrice;?>, <?php echo $maxPrice;?> ],
            slide: function( event, ui ) {

                $('.js-shop-products-price-between').val(ui.values[ 0 ] +','+ ui.values[ 1 ]);

                $( "#amount" ).val( "<?php echo $currencySymbol;?>" + ui.values[ 0 ] + " - <?php echo $currencySymbol;?>" + ui.values[ 1 ] );
            }
        });
        $( "#amount" ).val( "<?php echo $currencySymbol;?>" + $( "#slider-range" ).slider( "values", 0 ) +
            " - <?php echo $currencySymbol;?>" + $( "#slider-range" ).slider( "values", 1 ) );
    });
</script>

<div class="js-shop-product-filter">


    <form method="get" action="" class="form-horizontal">
        <div class="row">

        <div class="col-md-12">
        <p>
            <label for="amount">Price range:</label>
            <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>

        <input type="hidden" class="js-shop-products-price-between" value="<?php echo $priceBetween; ?>" name="priceBetween" />

         <div id="slider-range" class="mb-4"></div>
        </div>


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
        <div class="col-md-6">
            <select class="form-control" name="orderBy">
                <option value="id,desc" <?php if ($orderBy=='id,desc'):?> selected="selected" <?php endif;?>>Sort: Newest</option>
                <option value="id,asc" <?php if ($orderBy=='id,asc'):?> selected="selected" <?php endif;?>>Sort: Oldest</option>
            </select>
        </div>


        <?php
        $limit = '';
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
        }
        ?>
        <div class="col-md-6">
            <select class="form-control" name="limit">
                <option value="1" <?php if ($limit==1):?> selected="selected" <?php endif;?>>Limit: 1</option>
                <option value="2" <?php if ($limit==2):?> selected="selected" <?php endif;?>>Limit: 2</option>
                <option value="3" <?php if ($limit==3):?> selected="selected" <?php endif;?>>Limit: 3</option>
                <option value="5" <?php if ($limit==5):?> selected="selected" <?php endif;?>>Limit: 5</option>
                <option value="10" <?php if ($limit==10):?> selected="selected" <?php endif;?>>Limit: 10</option>
                <option value="15" <?php if ($limit==15):?> selected="selected" <?php endif;?>>Limit: 15</option>
                <option value="20" <?php if ($limit==20):?> selected="selected" <?php endif;?>>Limit: 20</option>
            </select>
        </div>


            <?php
            foreach($filters as $filterKey=>$filter):
            ?>
            <div class="col-md-12 pb-3">

                <?php
                if ($filter['type'] == 'dropdown' || $filter['type'] == 'radio'):
                ?>
                <b><?php echo $filter['name']; ?></b>

                <?php
                foreach($filter['options'] as $options):
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="custom_field[<?php echo $filterKey; ?>]" value="<?php echo $options['value']; ?>" id="defaultCheck<?php echo $options['id']; ?>">
                    <label class="form-check-label" for="defaultCheck<?php echo $options['id']; ?>">
                        <?php echo $options['value']; ?>
                    </label>
                </div>
                <?php
                endforeach;
                ?>
            <?php
            endif;
            ?>
            </div>
            <?php
            endforeach;
            ?>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block mt-2"><i class="fa fa-filter"></i> Filter</button>
            </div>
        </div>
    </form>

</div>
