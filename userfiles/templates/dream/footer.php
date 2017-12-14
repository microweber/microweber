<?php
$footer = get_option('footer', 'mw-template-dream');
if ($footer == '') {
    $footer = 'false';
}
?>
<?php if ($footer != 'true'): ?>
    <footer class="bg--dark footer-4 nodrop">
        <div class="container edit" field="dream_footer" rel="global">
            <div class="row">
                <div class="col-md-3 col-sm-4 allow-drop">
                    <module type="logo" id="footer-logo" template="footer"/>
                    <p><em>Free CMS & Website Builder</em></p>
                    <module type="menu" template="footer" name="footer_menu" id="footer_menu"/>
                </div>

                <div class="col-md-4 col-sm-8 allow-drop">
                    <h6>Recent News</h6>
                    <module type="posts" template="skin-5" id="recent-news-<?php print content_id(); ?>" limit="3" hide-paging="true"/>
                </div>

                <div class="col-md-4 col-md-offset-1 col-sm-12 allow-drop">
                    <h6>Subscribe</h6>
                    <p>Get monthly updates and free resources.</p>

                    <module type="newsletter" id="footer-newsletter"/>

                    <h6>Connect with Us</h6>
                    <module type="social_links" id="socials"/>
                </div>
            </div>
        </div>

        <div class="footer__lower">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-center-xs">
                        <span class="type--fine-print"><?php print powered_by_link(); ?> | <a href="<?php print admin_url() ?>">Admin</a></span>
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
<script src="{TEMPLATE_URL}assets/js/isotope.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/ytplayer.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/easypiechart.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/owl.carousel.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/lightbox.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/twitterfetcher.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/smooth-scroll.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/scrollreveal.min.js"></script>
<script src="{TEMPLATE_URL}assets/js/parallax.js"></script>
<script src="{TEMPLATE_URL}assets/js/scripts.js"></script>

</body>
</html>