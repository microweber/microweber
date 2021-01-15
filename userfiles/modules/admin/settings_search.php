<?php return; ?>

<script>
    $(document).ready(function (e) {
        $('.js-search-settings').on('change', function () {
            var url = $(this).find('option:selected').data('url');
            if (typeof url !== 'undefined') {
                window.location.href = url;
            } else {
                alert('This module can no be opened!');
            }
        });
    });
</script>
<select class="selectpicker js-search-settings" data-live-search="true" data-style="btn-sm" data-size="5">
    <optgroup label="Website Settings">
        <option data-tokens="currency general" data-url="<?php print admin_url() ?>view:shop/action:options/?group=general">General</option>
        <option data-tokens="updates" data-url="<?php print admin_url() ?>view:content/action:settings?group=updates">Updates</option>
        <option data-tokens="e-mail email" data-url="<?php print admin_url() ?>view:content/action:settings?group=email">E-mail</option>
        <option data-tokens="template" data-url="<?php print admin_url() ?>view:content/action:settings?group=template">Template</option>
        <option data-tokens="google analytics head footer tags robots.txt cleanup database licenses clear cache compile https ssl developer mode live edit settings statistics reload" data-url="<?php print admin_url() ?>view:content/action:settings?group=advanced">Advanced</option>
        <option data-tokens="files manager" data-url="<?php print admin_url() ?>view:content/action:settings?group=files">Files</option>
        <option data-tokens="login register social networks e-mail email notifications captcha" data-url="<?php print admin_url() ?>view:content/action:settings?group=login">Login & Register</option>
        <option data-tokens="language translate translation" data-url="<?php print admin_url() ?>view:content/action:settings?group=language">Language</option>
        <option data-tokens="privacy policy settings comments contact form newsletter" data-url="<?php print admin_url() ?>view:content/action:settings?group=privacy">Privacy Policy</option>
    </optgroup>
    <optgroup label="Shop Settings">
        <option>Tent</option>
        <option>Flashlight</option>
        <option>Toilet Paper</option>
    </optgroup>
</select>