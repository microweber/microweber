<?php
$showSearch = false;

if (count($orders) > 0) {
    $showSearch = true;
}
if (isset($newOrders) && count($newOrders) > 0) {
    $showSearch = true;
}

if ($filteringResults) {
    $showSearch = true;
}

if ($showSearch):
?>
<div class="js-search-by d-inline-block">
    <div class="js-search-by-keywords">
        <form method="get">
            <div class="d-flex">
                <div class="input-group mb-0 prepend-transparent mx-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control form-control-sm" placeholder="<?php _e("Search"); ?>"/>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-magnify"></i></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
