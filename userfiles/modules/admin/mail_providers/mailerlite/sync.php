<?php

$foundedEmails = [];
$formEntires = mw()->forms_manager->get_entires();
foreach ($formEntires as $formEntire)
{
    $decodedValues = json_decode($formEntire['form_values'], true);
    if (is_array($decodedValues)) {
        foreach ($decodedValues as $value) {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $foundedEmails[$value] = $decodedValues;
            }
        }
    }
}

$log = '';
foreach ($foundedEmails as $formEntry) {
    $log .= 'Sync... <br />';
    $subscribeUser = sync_mail_subscriber($formEntry);
    foreach ($subscribeUser as $subscribeLog) {
        $log .=  $subscribeLog . '<hr />';
    }
}

echo $log;
?>



<!--
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Type</th>
    </tr>
    </thead>
    <tbody>
    <tr class="table-active">
        <th>Active</th>
    </tr>
    </tbody>
</table>-->
