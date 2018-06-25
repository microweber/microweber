<?php
$footer = get_option('footer', 'mw-template-dream');
if ($footer == '') {
    $footer = 'false';
}

$is_live =  mw()->user_manager->session_get('editmode') == true;
?>
<?php if ($footer != 'true' || $is_live): ?>
    <footer class="bg--dark footer-4 nodrop mwjs-dream-footer" style="display:<?php print ($footer == 'true' and $is_live) ? 'none' : 'block';  ?>;">
        <div class="container edit" field="dream_footer" rel="global">
            <div class="row">
                <div class="col-md-3 col-sm-4 allow-drop">
                    <module type="logo" id="footer-logo" template="footer"/>
                    <p><em><?php _e("Free CMS & Website Builder") ?></em></p>
                    <module type="menu" template="footer" name="footer_menu" id="footer_menu"/>
                </div>

                <div class="col-md-4 col-sm-8 allow-drop">
                    <h6>Recent News</h6>
                    <module type="posts" template="skin-5" id="recent-news-<?php print content_id(); ?>" limit="3" hide-paging="true"/>
                </div>

                <div class="col-md-4 col-md-offset-1 col-sm-12 allow-drop">
                    <h6><?php _e("Subscribe") ?></h6>
                    <p><?php _lang("Get monthly updates and free resources.", "templates/dream"); ?></p>

                    <module type="newsletter" id="footer-newsletter"/>

                    <h6><?php _lang("Connect with Us", "templates/dream"); ?></h6>
                    <module type="social_links" id="socials"/>
                </div>
            </div>
        </div>

        <div class="footer__lower">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-center-xs">
                        <span class="type--fine-print"><?php print powered_by_link(); ?></span>
                    </div>
                    <div class="col-sm-6 text-right text-center-xs">
                        <a href="#top" class="inner-link top-link">
                            <i class="interface-up-open-big"></i>
                        </a>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </div>
    </footer>
<?php endif; ?>

</div>



<script src="{TEMPLATE_URL}assets/dist/main.min.js"></script>

</body>
</html>