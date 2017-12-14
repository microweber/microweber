<?php

/*

type: layout

name: Search with button

description: Skin 1

*/

?>

<div class="inline-search clearfix margin-bottom-30">
    <form class="form--merge" action="<?php print site_url(); ?>search.php" method="get">
        <input class="col-md-8 col-sm-7" type="search" id="keywords" name="keywords" placeholder="Search keywords, places, businesses etc.">
        <button class="col-md-4 col-sm-5 btn btn--primary vpe" type="submit">Start Exploring</button>

        <input type="hidden" name="search-type" value="<?php print $searchType; ?>"/>
    </form>
</div>

