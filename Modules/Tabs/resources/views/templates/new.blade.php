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
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs justify-content-center float-none">
        <?php
        $count = 0;
        foreach ($tabs as $slide) {
            $count++;
            ?>
            <a class="btn btn-outline-primary px-5  <?php if ($count == 1) { ?> active <?php } ?> " href="javascript:;"><?php print isset($slide['icon']) ? $slide['icon'] . ' ' : ''; ?><?php print isset($slide['title']) ? $slide['title'] : ''; ?></a>
        <?php } ?>
    </div>
    <div class="py-5">
        <?php
        $count = 0;
        foreach ($tabs as $key => $slide) {
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
                    <div class="element"> <h6><?php print isset($slide['content']) ? $slide['content'] : 'Tab content ' . $count ?></h6>
                        <br>
                        <p>
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
