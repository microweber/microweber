<?php

/*

type: layout

name: Blog posts

position: 3

*/

?>

<div class="nodrop safe-mode edit" field="layout-skin-4-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="box-container latest-items">
            <h2 class="element section-title">
                <small class="safe-element">What's new</small>
                <span class="safe-element">From the Blog</span></h2>
            <module type="posts" limit="3" hide-paging="true" data-show="thumbnail,title,read_more,description">
        </div>
    </div>
</div>