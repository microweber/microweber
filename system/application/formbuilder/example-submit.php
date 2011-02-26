<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>jQuery FormBuilder Demo (Submission)</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body>

<?php

require('Formbuilder/Formbuilder.php');

// At this stage, we simulate getting an array of the
// form_structure and hash from our database. This is
// how the form data would have been saved using
// the $form->store() method.
include('fake-form-db-vals.php');

$form = new Formbuilder($fake_db_vals);
$results = $form->process();

print '<pre>';
var_dump($results);
print '</pre>';

?>

	</body>
</html>