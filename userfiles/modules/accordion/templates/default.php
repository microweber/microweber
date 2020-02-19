<?php

/*

  type: layout

  name: Default

  description: Default template


*/

?>
<?php
if ($json == false) {
    print lnotif(_e('Click to edit accordion', true));

    return;
}

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
?>

<script>

    $(document).ready(function(){
        var root = $("#mw-accordion-module-<?php print $params['id'] ?>");
        $('.accordion__title', root).on('click', function(){
            $('li.active', root).removeClass('active');
            $(this).parent().addClass('active');
        })
    })

</script>


<div id="mw-accordion-module-<?php print $params['id'] ?>" class="mw-accordion-box-wrapper">
    <ul class="accordion">
        <?php foreach ($json as $key => $slide): ?>
        <?php

        $edit_field_key = $key;
        if(isset($slide['id'])){
            $edit_field_key = $slide['id'];
        }

         ?>


            <li class="<?php if ($key == 0): ?>active<?php endif; ?>">
                <div class="accordion__title">
                    <span class="h5"><?php print isset($slide['icon']) ? $slide['icon'] . ' ' : ''; ?><?php print isset($slide['title']) ? $slide['title'] : ''; ?></span>
                </div>

                <div class="accordion__content">
                    <div class="edit allow-drop" field="accordion-item-<?php print $edit_field_key ?>" rel="module-<?php print $params['id'] ?>">
                       <div class="element"> <?php print isset($slide['content']) ? $slide['content'] : 'Accordion content' ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
