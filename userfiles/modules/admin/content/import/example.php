<?php



include_once 'class.csv_bv.php';

$csv = & new csv_bv('test.csv', ';', '"' , '\\'); 	// The last three fields are optional. If the last field is ommitted
												// the MS Excel standard is assumed, i.e. a double quote is used
												// to escape a double quote ("").
$csv->SkipEmptyRows(FALSE); // Will skip empty rows. TRUE by default. (Shown here for example only).
$csv->TrimFields(TRUE); // Remove leading and trailing \s and \t. TRUE by default.

while ($arr_data = $csv->NextLine()){

	echo "<br><br>Processing line ". $csv->RowCount() . "<br>";
	echo implode(' | ', $arr_data);

}

echo "<br><br>Number of returned rows: ".$csv->RowCount();
echo "<br><br>Number of skipped rows: ".$csv->SkippedRowCount();


//----
// OR using the csv2array function.
//----

include_once 'class.csv_bv.php';

$csv = & new csv_bv('test.csv', ';', '"' , '\\');
$csv->SkipEmptyRows(TRUE); // Will skip empty rows. TRUE by default. (Shown here for example only).
$csv->TrimFields(TRUE); // Remove leading and trailing \s and \t. TRUE by default.

$_arr = $csv->csv2Array();
print_r($_arr);

echo "<br><br>Number of returned rows: ".$csv->RowCount();
echo "<br><br>Number of skipped rows: ".$csv->SkippedRowCount();


?>
