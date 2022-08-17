<?php

/*

type: layout
content_type: static
name: Contact Us

description: Contact us layout
position: 7
*/


?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
    <div class="container">

        <h2 class="element section-title">
            <div class="mw-row">
                <div class="mw-col" style="width:40%">
                    <div class="mw-col-container">
                        <div class="element">
                            <hr class="visible-desktop column-hr">
                        </div>
                    </div>
                </div>
                <div class="mw-col" style="width:20%">
                    <div class="mw-col-container">
                        <h2 align="center" class="edit element" field="title" rel="module"><?php _e('Page Title'); ?></h2>
                    </div>
                </div>
                <div class="mw-col" style="width:40%">
                    <div class="mw-col-container">
                        <div class="element">
                            <hr class="visible-desktop column-hr">
                        </div>
                    </div>
                </div>
            </div>
        </h2>
        <div class="edit" field="content" rel="content">
            <module type="google_maps" />
            <div class="element page-post-content"><?php _e('This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative, Make Web.'); ?></div>

            <div class="mw-row">
                <div class="mw-col" style="width: 50%;">
                    <div class="mw-col-container">
                        <module type="contact_form" class="contact-form" id="contact-form" />
                    </div>
                </div>
                <div class="mw-col" style="width: 50%;">
                    <div class="mw-col-container" style="padding-left: 40px;">
                        <div>
                            <h3><?php _e('Address'); ?></h3>
                            <hr>
                            <p> 10 "Professor Georgi Zlatarski" , bl. B, fl. 3,<br />
                                Sofia 1700,<br />
                                Bulgaria </p>
                            <ul class="contact-list">
                                <li> <span class="contact-list contact-icon"><img src="<?php print thumbnail(TEMPLATE_URL."img/contact_phone_ico.png", 30, 30); ?>" /></span><span><?php _e('Phone:'); ?> +1 (310) 123 4567<br />
    								</span> </li>
                                <li><span class="contact-list contact-icon"><img src="<?php print thumbnail(TEMPLATE_URL."img/contact_email_ico.png", 30, 30); ?>" /></span><span>help@microweber.com</span></li>
                            </ul>
                        </div>
                        <h3>Follow Us</h3>
                        <hr>
                        <div class="social-icons"> <a href="https://www.facebook.com/microweber"><img src="<?php print thumbnail(TEMPLATE_URL."img/mw.soc.fb.png", 30, 30); ?>" /></a> <a href="https://www.twitter.com/microweber"><img src="<?php print thumbnail(TEMPLATE_URL."img/mw.soc.tw.png", 30, 30); ?>" /></a> <a href="https://plus.google.com"><img src="<?php print thumbnail(TEMPLATE_URL."img/mw.soc.googplus.png", 30, 30); ?>" /></a> <a href="https://plux.google.com"><img src="<?php print thumbnail(TEMPLATE_URL."img/mw.soc.yt.png", 30, 30); ?>" /></a><br>
                        </div>
                    </div>
                </div>

            </div>



        </div>
    </div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
