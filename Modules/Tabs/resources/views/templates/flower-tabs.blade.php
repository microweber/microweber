<?php

/*

type: layout

name: Flower tabs

description: Flower

*/
?>

<style>
    .flower-tabs-button {
        border-radius: 100px 0 100px 100px!important;
        background-color: #FED1DC;
        color: #FF2359;
        padding: 30px 65px;
        margin: 0 20px;
        border-color: #FED1DC!important;
    }

    .flower-tabs-button.active {
       background-color: #FF2359;
        color: #ffffff;

    }
</style>

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

<div id="mw-tabs-module-{{ $params['id'] }}"
     class="mw-tabs-box-wrapper mw-module-tabs-skin-default">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs justify-content-center float-none">
        <?php
        $count = 0;
        foreach ($json as $slide) {
            $count++;
            ?>
            <a class="flower-tabs-button btn btn-outline-primary my-xl-0 my-3 <?php if ($count == 1) { ?> active <?php } ?> " href="javascript:;"><?php print isset($slide['icon']) ? $slide['icon'] . ' ' : ''; ?><?php print isset($slide['title']) ? $slide['title'] : ''; ?></a>
        <?php } ?>
    </div>
    <div class="py-5">
        <?php
        $count = 0;
        foreach ($json as $key => $slide) {
            $count++;
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }
            ?>
            <div class="column mw-ui-box-tab-content pt-3 text-center"
                 style="<?php if ($count != 1) { ?> display: none; <?php } else { ?>display: block; <?php } ?>">
                <div class="edit  " field="tab-item-<?php print $edit_field_key ?>"
                     rel="module-{{ $params['id'] }}">
                    <div class="element">
                        <h6>
                            <?php print isset($slide['content']) ? $slide['content'] : 'Tab content ' . $count . '<P>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</P> ' ?></h6>

                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
