<?php must_have_access(); ?>

<?php

$ignoreFields = array('terms','rel_id','rel_type','for','for_id','captcha','module_name','list_id','option_group');

$foundedFields = [];
$formEntires = mw()->forms_manager->get_entires();
if (!empty($formEntires)) {
    foreach ($formEntires as $formEntire) {
        $decodedValues = json_decode($formEntire['form_values'], true);
        if (is_array($decodedValues)) {
            foreach ($decodedValues as $key => $value) {
                if (in_array($key, $ignoreFields)) {
                    continue;
                }
                $foundedFields[md5($key)] = $key;
            }
        }
    }
}
?>

<script>
    $(document).ready(function () {

        $('.js-contact-form-map-fields-form').submit(function (e) {
            e.preventDefault();

            $.post(mw.settings.api_url + 'save_contact_form_fields', $(this).serialize(), function (data) {
                if (data > 0) {
                    mw.notification.success('Sucessfull mapping.');
                } else {
                    mw.notification.error('Can\'t save mapping.');
                }
            });

        });
    });
</script>

<?php
if (!empty($foundedFields)):
    ?>
<form class="js-contact-form-map-fields-form mb-3">

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Source Field</th>
        <th scope="col">Target Field <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="mdi mdi-content-save"></i> Save Mapping</button></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $mapFields = get_option('contact_form_map_fields','contact_form');
    $mapFields = json_decode($mapFields, true);

    foreach ($foundedFields as $fieldKey=>$fieldValue):
    ?>
    <tr class="table-active">
        <input type="hidden" name="contact_form_map_fields[<?php echo $fieldKey;?>][source]" value="<?php echo $fieldValue;?>">
        <th><?php echo $fieldValue;?></th>
        <th>

            <select name="contact_form_map_fields[<?php echo $fieldKey;?>][target]" class="form-control">
                <option>Select..</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'email'):?>selected="selected"<?php endif; ?> value="email">Email</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'name'):?>selected="selected"<?php endif; ?> value="name">Name</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'message'):?>selected="selected"<?php endif; ?> value="message">Message</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'last_name'):?>selected="selected"<?php endif; ?> value="last_name">Last name</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'company_name'):?>selected="selected"<?php endif; ?> value="company_name">Company Name</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'company_position'):?>selected="selected"<?php endif; ?> value="company_position">Company Position</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'country'):?>selected="selected"<?php endif; ?> value="country">Country</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'city'):?>selected="selected"<?php endif; ?> value="city">City</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'phone'):?>selected="selected"<?php endif; ?> value="phone">Phone</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'state'):?>selected="selected"<?php endif; ?> value="state">State</option>
                <option <?php if(!empty($mapFields[$fieldValue]) && $mapFields[$fieldValue] == 'zip'):?>selected="selected"<?php endif; ?> value="zip">ZIP</option>
            </select>
        </th>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</form>
<?php
else:
?>
No contact forms created or no entries found.
<?php
endif;
?>
