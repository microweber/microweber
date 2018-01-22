
<?php include template_dir() . "header.php"; ?>

<?php
$keywords = '';
if (isset($_GET['keywords'])) {
    $keywords = htmlspecialchars($_GET['keywords']);
}

$searchType = '';
if (isset($_GET['search-type'])) {
    $searchType = htmlspecialchars($_GET['search-type']);
}
?>

<section class="height-40">
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><?php _e('Search results for'); ?>: <span><?php print $keywords; ?></span></h3>
            </div>
        </div>
    </div>
</section>

<section class="bg--secondary">
    <div class="container">
        <div class="">
            <?php if ($searchType == 'shop' OR $searchType == ''): ?>
                <span><em><?php _e('Results found mentioning'); ?></em> &ldquo;<?php print $keywords; ?>&rdquo; <?php _lang("in Shop", "templates/dream"); ?></span>
                <div class="row">
                    <module type="shop/products" limit="18" keyword="<?php print $keywords; ?>" description-length="70"/>
                </div>
                <br/>
                <br/>
                <hr/>
            <?php endif; ?>

            <div class="clearfix"></div>

            <?php if ($searchType == 'blog' OR $searchType == ''): ?>
                <span><em><?php _e('Results found mentioning'); ?></em> &ldquo;<?php print $keywords; ?>&rdquo; <?php _lang("in Blog", "templates/dream"); ?></span>
                <br/>
                <br/>
                <module type="posts" limit="18" keyword="<?php print $keywords; ?>" description-length="70"/>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="space--even bg--white">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h4 class="edit" rel="content" field="content_new_search"><?php _lang("Didn't find what you were looking for?", "templates/dream"); ?></h4>
                <a class="btn btn--primary modal-trigger" href="#" data-modal-id="search-form">
                    <span class="btn__text"><?php _e('New Search Query'); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include template_dir() . "footer.php"; ?>
