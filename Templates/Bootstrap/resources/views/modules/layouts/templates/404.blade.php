<?php

/*

type: layout

name: 404

position: 10

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    default-padding-top="p-t-50"
    default-padding-bottom="p-b-50"
    section-class="section"
    field-name="layout-404"
    :has-background="false"
    :has-spacers="false"
>
    <x-row>
        <x-col size="4" class="not_found_text align-self-center">
            <h1><?php _lang("Oops", "templates/new-world"); ?>!</h1>
            <p class="my-3"><?php _lang("A 404 error is a standard HTTP error
             message code that means the website you
              were trying to reach couldn't be found on the server", "templates/new-world"); ?>.
            </p>
            <module type="btn" button_size="px-6" button_text="Go back"/>
        </x-col>

        <x-col size="8" class="text-center not_found_img">
            <img src="<?php print template_url(); ?>img/sections/404_graphic.png" alt="<?php echo __('404 error illustration'); ?>"/>
        </x-col>
    </x-row>
</x-layout-section>
