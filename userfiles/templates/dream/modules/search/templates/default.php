<?php

/*

type: layout

name: Default

description: Default Search template

*/

?>

<div class="inline-search clearfix margin-bottom-30">
    <form action="<?php print site_url(); ?>search.php" method="get">
        <div class="input-with-icon">
            <i class="icon-Magnifi-Glass2"></i>
            <input type="search" placeholder="Start Searching..." id="keywords" name="keywords">

            <input type="hidden" name="search-type" value="<?php print $searchType; ?>"/>
        </div>
    </form>
</div>