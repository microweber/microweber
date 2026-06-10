<?php

/*

type: layout

name: Feature 65

position: 65

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

    .feature-65 .resume-title {
        font-size: 26px;
        font-weight: 700;
        margin-top: 20px;
        margin-bottom: 20px;
        color: #45505b;
    }

    .feature-65 .resume-item {
        padding: 0 0 20px 20px;
        margin-top: -2px;
        border-left: 2px solid var(--mw-primary-color);
        position: relative;
    }

    .feature-65 .resume-item h4 {
        line-height: 18px;
        font-size: 18px;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--mw-primary-color);
        margin-bottom: 10px;
    }

    .feature-65 .resume-item .feature-65-small-box {
        font-size: 16px;
        background: #f7f8f9;
        padding: 5px 15px;
        display: inline-block;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .feature-65 .resume-item ul {
        padding-left: 20px;
    }

    .feature-65 .resume-item ul li {
        padding-bottom: 10px;
    }

    .feature-65 .resume-item:last-child {
        padding-bottom: 0;
    }

    .feature-65 .resume-item::before {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50px;
        left: -9px;
        top: 0;
        background: #fff;
        border: 2px solid var(--mw-primary-color);
    }
</style>


<section class="section feature-65 <?php print $layout_classes; ?> ">

    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="mw-layout-container container no-element edit"
         field="layout-feature-skin-65-{{ $params['id'] }}" rel="module">

        <div class="section-title">
            <h2 data-mwplaceholder="<?php _e('Enter title here'); ?>">Resume</h2>
            <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
        </div>

        <div class="row">
            <div class="col-lg-6 cloneable element">
                <h3 class="resume-title">Sumary</h3>
                <div class="resume-item safe-mode cloneable element pb-0">
                    <h4>Brandon Johnson</h4>
                    <p><em>Innovative and deadline-driven Graphic Designer with 3+ years of experience designing and developing user-centered digital/print marketing material from initial concept to final, polished deliverable.</em></p>
                    <ul>
                        <li>Portland par 127,Orlando, FL</li>
                        <li>(123) 456-7891</li>
                        <li>alice.barkley@example.com</li>
                    </ul>
                </div>

                <h3 class="resume-title">Education</h3>
                <div class="resume-item safe-mode cloneable element">
                    <h4>Master of Fine Arts &amp; Graphic Design</h4>
                    <h5 class="feature-65-small-box background-color-element element">2015 - 2016</h5>
                    <p><em>Rochester Institute of Technology, Rochester, NY</em></p>
                    <p>Qui deserunt veniam. Et sed aliquam labore tempore sed quisquam iusto autem sit. Ea vero voluptatum qui ut dignissimos deleniti nerada porti sand markend</p>
                </div>
                <div class="resume-item safe-mode cloneable element">
                    <h4>Bachelor of Fine Arts &amp; Graphic Design</h4>
                    <h5 class="feature-65-small-box background-color-element element">2010 - 2014</h5>
                    <p><em>Rochester Institute of Technology, Rochester, NY</em></p>
                    <p>Quia nobis sequi est occaecati aut. Repudiandae et iusto quae reiciendis et quis Eius vel ratione eius unde vitae rerum voluptates asperiores voluptatem Earum molestiae consequatur neque etlon sader mart dila</p>
                </div>
            </div>
            <div class="col-lg-6 cloneable element">
                <h3 class="resume-title">Professional Experience</h3>
                <div class="resume-item safe-mode cloneable element">
                    <h4>Senior graphic design specialist</h4>
                    <h5 class="feature-65-small-box background-color-element element">2019 - Present</h5>
                    <p><em>Experion, New York, NY </em></p>
                    <ul>
                        <li>Lead in the design, development, and implementation of the graphic, layout, and production communication materials</li>
                        <li>Delegate tasks to the 7 members of the design team and provide counsel on all aspects of the project. </li>
                        <li>Supervise the assessment of all graphic materials in order to ensure quality and accuracy of the design</li>
                        <li>Oversee the efficient use of production project budgets ranging from $2,000 - $25,000</li>
                    </ul>
                </div>
                <div class="resume-item safe-mode cloneable element">
                    <h4>Graphic design specialist</h4>
                    <h5 class="feature-65-small-box background-color-element element">2017 - 2018</h5>
                    <p><em>Stepping Stone Advertising, New York, NY</em></p>
                    <ul>
                        <li>Developed numerous marketing programs (logos, brochures,infographics, presentations, and advertisements).</li>
                        <li>Managed up to 5 projects or tasks at a given time while under pressure</li>
                        <li>Recommended and consulted with clients on the most appropriate graphic design</li>
                        <li>Created 4+ design presentations and proposals a month for clients and account managers</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>

</section>
