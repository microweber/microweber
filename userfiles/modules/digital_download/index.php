<?php


$button_text = get_option('button_text', $params['id']);
if (!$button_text) {
    $button_text = 'Download';
}

$button_alignment = get_option('button_alignment', $params['id']);
if (!$button_alignment) {
    $button_alignment = 'center';
}

$require_email = get_option('require_email', $params['id']);
if (!$require_email) {
    $require_email = 'n';
}

$download_on_click = 'digitalDownloadClick'.md5($params['id']).'()';
?>

<script>
    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    function digitalDownloadClick<?php print md5($params['id']); ?>() {

        var email = '';
        if ('<?php print $require_email; ?>' == 'yes') {
            let emailInput = document.getElementById('<?php print md5($params['id']); ?>-email');
            if (emailInput) {
                email = emailInput.value;
                if (!validateEmail(email)) {
                    alert('Please enter a valid email address');
                    return;
                }
            }
        }

        $.ajax({
            url: '<?php print api_url('digital_download_get') . '?id=' . $params['id']; ?>&email=' + email,
            type: 'GET',
            success: function (data) {
                window.location.href = data.download_url;

                let downloadForm = document.getElementById('<?php print md5($params['id']); ?>-download-form');
                if (downloadForm) {
                    downloadForm.style.display = 'none';
                }
                let thankYouMessage = document.getElementById('<?php print md5($params['id']); ?>-thank-you-message');
                if (thankYouMessage) {
                    thankYouMessage.style.display = 'block';
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);

                alert('Error downloading file');
            }
        });

    }

</script>


<?php

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}
