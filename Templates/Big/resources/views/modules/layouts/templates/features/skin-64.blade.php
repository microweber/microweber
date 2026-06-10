<?php

/*

type: layout

name: Feature 64

position: 64

categories: Features

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<style>
    .feature-64 .progress {
        height: 60px;
        display: block;
        background: none;
        border-radius: 0;
    }

    .feature-64 .progress .skill {
        padding: 10px 0;
        margin: 0;
        text-transform: uppercase;
        display: block;
        font-weight: 600;
        color: #45505b;
    }

    .feature-64 .progress .skill .val {
        float: right;
        font-style: normal;
    }

    .feature-64 .progress-bar-wrap {
        background: #f2f3f5;
    }

    .feature-64 .progress-bar {
        width: 1px;
        transition: 0.9s;
        background-color: #0563bb;
    }


</style>


<section class="section feature-64 <?php print $layout_classes; ?> ">

    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="mw-layout-container container no-element edit"
         field="layout-feature-skin-64-{{ $params['id'] }}" rel="module">

        <div class="section-title text-center">
            <h2 data-mwplaceholder="<?php _e('Enter title here'); ?>">Skills</h2>
            <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
        </div>

        <div class="row skills-content">

            <div class="col-lg-6">

               <module type="skills" />

            </div>

            <div class="col-lg-6">

              <module type="skills" />

            </div>

        </div>
    </div>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>

</section>
