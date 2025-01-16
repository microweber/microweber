<?php

/*

type: layout

name: horizontal centered tabs links

description: Horizontal

*/
?>


<?php

if ($json == false) {
    print lnotif(_e('Click to edit tabs', true));

    return;
}

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

?>
<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-btn-nav-tabs a',
            tabs: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-box-tab-content'
        });
    });


</script>


<style>

    .mw-module-tabs-skin-horizontal-links {
        position: sticky;
        top: 0;
        z-index: 9;
        backdrop-filter: saturate(180%) blur(20px);
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a {
        color: var(--mw-heading-color);
        display: inline-block;
        line-height: 50px;
        position: relative;
        transition: .3s;
    }

    .mw-module-tabs-skin-horizontal-links ul {
        list-style: none;
    }

    .mw-module-tabs-skin-horizontal-links ul > li {
        position: relative;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a span:after {
        position: absolute;
        content: '';
        bottom: 10px;
        left: 0;
        height: 2px;
        width: 70%;
        background-color: var(--mw-primary-color);
        transition: .5s;
        opacity: 0;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a:hover, .mw-module-tabs-skin-horizontal-links ul > li a.active {
        color: var(--mw-primary-color);
        opacity: .8;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a:hover span:after, .mw-module-tabs-skin-horizontal-links ul > li a.active span:after {
        width: 100%;
        opacity: 1;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a {
        font-size: var(--mw-paragraph-size);
        font-weight: var(--mw-font-weight);


    }

</style>

<div id="mw-tabs-module-{{ $params['id'] }}"
     class="row mw-tabs-box-wrapper mw-module-tabs-skin-horizontal-links">
    <div class="mw-ui-btn-nav merry-navs-btn-pricing mw-ui-btn-nav-tabs d-flex flex-wrap mx-auto mt-5 gap-2">
        <?php
        $count = 0;
        foreach ($json as $slide) {
            $count++;
            ?>
            <ul class="ps-0">
                <li>
                     <a class="btn btn-link my-xl-0 my-3 <?php if ($count == 1) { ?> active <?php } ?> " href="javascript:;"><?php print isset($slide['icon']) ? $slide['icon'] . ' ' : ''; ?><span><?php print isset($slide['title']) ? $slide['title'] : ''; ?></span></a>
                </li>
            </ul>
        <?php } ?>
    </div>
    <div>
        <?php
        $count = 0;
        foreach ($json as $key => $slide) {
            $count++;
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }
            ?>
            <div class="column mw-ui-box-tab-content pt-3" style="<?php if ($count != 1) { ?> display: none; <?php } else { ?>display: block; <?php } ?>">
                <div class="edit  " field="tab-item-<?php print $edit_field_key ?>" rel="module-{{ $params['id'] }}">
                    <div class="element">
                        <h6> <?php print isset($slide['content']) ? $slide['content'] : '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p> ' ?></h6>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
