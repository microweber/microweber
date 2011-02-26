<?php

require('Formbuilder/Formbuilder.php');

// At this stage, we simulate getting an array of the
// form_structure and hash from our database. This is
// how the form data would have been saved using
// the $form->store() method.
include('fake-form-db-vals.php');

$form = new Formbuilder($fake_db_vals);
$form->render_xml();

?>