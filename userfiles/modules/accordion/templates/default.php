<?php
if ($json == false) {
    print lnotif(_e('Click to edit accordion', true));

    return;
}

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
?>


<div id="mw-accordion-module-<?php print $params['id'] ?>" class="mw-accordion-box-wrapper">
    <ul class="accordion">
        <?php foreach ($json as $key => $slide): ?>
            <li class="<?php if ($key == 0): ?>active<?php endif; ?>">
                <div class="accordion__title">
                    <span class="h5"><span class="fa <?php print isset($slide['icon']) ? $slide['icon'] : ''; ?>"></span> <?php print isset($slide['title']) ? $slide['title'] : ''; ?></span>
                </div>

                <div class="accordion__content">
                    <div class="edit allow-drop" field="accordion-item-<?php print $key ?>" rel="module-<?php print $params['id'] ?>">
                        <div class="element"> <?php print isset($slide['content']) ? $slide['content'] : 'Accordion content' ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>