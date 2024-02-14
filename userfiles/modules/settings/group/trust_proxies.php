<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="<?php print $config['module_class'] ?>">
    <div class="form-group">
        <label class="form-label"><?php _e("Configuring Trusted Proxies"); ?></label>
        <?php $trust_proxies = get_option('trust_proxies', 'website'); ?>
<textarea name="trust_proxies" class="mw_option_field form-control" data-option-group="website" placeholder="127.0.0.1" rows="5"><?php print $trust_proxies; ?></textarea>
        <small class="text-muted">


            <?php _e("If you are using a proxy server, you can specify the IP address of the server here. This will allow Microweber to trust the proxy server and get the correct IP address of the visitor. Set 1 IP address per line"); ?>
            <?php _e("You can get the list of Cloudflare IPs here:"); ?> <a href="https://www.cloudflare.com/ips-v4/" target="_blank">https://www.cloudflare.com/ips-v4/</a>


        </small>

    </div>

</div>
