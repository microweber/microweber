@php
    /*

    type: layout

    name: Skin 3

    description: Skin 3

    */
@endphp


<?php

if ($tabs == false) {
    print lnotif(_e('Click to edit tabs', true));

    return;
}

if (isset($tabs) == false or count($tabs) == 0) {
    $tabs = $defaults;
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
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs df">
        <?php
        $count = 0;
        foreach ($tabs as $slide) {
            $count++;
            ?>
        <a class="btn btn-primary df mb-3 <?php if ($count == 1) { ?> active <?php } ?>" href="javascript:;"><?php print isset($slide['icon']) ? $slide['icon'] . ' ' : ''; ?><span class="mb-0"><?php print isset($slide['title']) ? $slide['title'] : 'Tab title 1'; ?></span></a>
        <?php } ?>
    </div>
    <div class="mw-ui-box">
        <?php
        $count = 0;
        foreach ($tabs as $key => $slide) {
            $count++;


            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }
            ?>
        <div class="mw-ui-box-content mw-ui-box-tab-content"
             style="<?php if ($count != 1) { ?> display: none; <?php } else { ?>display: block; <?php } ?>">
            <div class="edit  " field="tab-item-<?php print $edit_field_key ?>"
                 rel="module-{{ $params['id'] }}">
                <h6>  <?php print isset($slide['content']) ? $slide['content'] : 'Tab content ' . $count . '<P>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</P> ' ?></h6>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
