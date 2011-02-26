<?php

require('Formbuilder/Formbuilder.php');

// At this stage, we want to pass in the POST value
// containing the form. In this example we simply
// pass POST directly, but DO NOT use POST without
// proper security in place.

$form_data = isset($_POST['ul']) ? $_POST['ul'] : false;

$form = new Formbuilder($form_data);
$for_db = $form->store();

// The store() method returns an array that should be
// used to store the values in the database. This array
// is also what's passed to the class when rendering
// or editing the form.

print_r($for_db);

/*
Outputs:
Array
(
    [form_structure] => a:4:{i:0;a:3:{s:5:"class";s:10:"input_text";s:8:"required";s:5:"false";s:6:"values";s:4:"Name";}i:1;a:3:{s:5:"class";s:10:"input_text";s:8:"required";s:4:"true";s:6:"values";s:15:"E-mail Address?";}i:2;a:4:{s:5:"class";s:8:"checkbox";s:8:"required";s:5:"false";s:5:"title";s:11:"Choose any:";s:6:"values";a:4:{i:2;a:2:{s:5:"value";s:3:"PHP";s:7:"default";s:4:"true";}i:3;a:2:{s:5:"value";s:6:"jQuery";s:7:"default";s:4:"true";}i:4;a:2:{s:5:"value";s:3:"XML";s:7:"default";s:4:"true";}i:5;a:2:{s:5:"value";s:5:"Aspen";s:7:"default";s:5:"false";}}}i:3;a:4:{s:5:"class";s:5:"radio";s:8:"required";s:4:"true";s:5:"title";s:11:"Choose one:";s:6:"values";a:2:{i:2;a:2:{s:5:"value";s:3:"Yes";s:7:"default";s:4:"true";}i:3;a:2:{s:5:"value";s:2:"No";s:7:"default";s:5:"false";}}}}
    [form_hash] => bb7d5c1fa425235aa666c1a78b3873e7732709af
)
 */

// Save the two fields above into the database, and provide them
// back to the formbuilder class when rendering/editing.

?>