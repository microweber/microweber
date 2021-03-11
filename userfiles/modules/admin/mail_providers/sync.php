<?php must_have_access(); ?>

<?php
$foundedEmailsUnique = [];
$formEntires = mw()->forms_manager->get_entires();
if (!empty($formEntires)) {
    foreach ($formEntires as $formEntire) {
        $decodedValues = json_decode($formEntire['form_values'], true);
        if (is_array($decodedValues)) {
            foreach ($decodedValues as $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $decodedValues['email'] = $value;
                    $decodedValues['md5_email'] = md5($value);
                    $foundedEmailsUnique[$value] = $decodedValues;
                }
            }
        }
    }
}
?>

<?php
if (empty($foundedEmailsUnique)):
?>
No emails found.
<?php
return;
endif;
?>

<?php
$foundedEmails = [];
foreach ($foundedEmailsUnique as $email) {
    $foundedEmails[] = $email;
}
?>

<script>
$(document).ready(function () {

    email_subscribers = <?php echo json_encode($foundedEmails);?>;

    $.each(email_subscribers, function(key, value) {
        $.post(mw.settings.api_url + 'sync_mail_subscriber', value).done(function (data) {
            console.log(data);
            var status = '';
            $.each(data.providers, function(key, value) {
                if (value.success) {
                    status += "<div class='text-success'>";
                    status += "<i class='mdi mdi-check'></i>";
                    status += value.name + ": <?php _e('Subscribed');?><br />";
                    status += "</div>";
                } else {
                    status += "<div class='text-danger'>";
                    status += "<i class='mdi mdi-information'></i>";
                    status += value.name + ": " + value.log + "<br />";
                    status += "</div>";
                }
            });
            $('.js-tr-mail-sync-' + value.md5_email).find('.status').html(status);
            $('.js-tr-mail-sync-' + value.md5_email).addClass('table-info');
        });
    });

});
</script>

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Email</th>
        <th scope="col">Subscribed</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($foundedEmails as $formEntry): ?>
    <tr class="js-tr-mail-sync-<?php echo $formEntry['md5_email'];?>">
        <th><?php echo $formEntry['email']; ?></th>
        <th class="status">
            Loading..
        </th>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
