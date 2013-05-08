<?php
require_once('luminous.php');
?><!DOCTYPE html>
<html>
  <head>
    <style>
body {
  font-family: sans-serif;
}
table {
  border-collapse: collapse;
}
td {
 border: 1px solid gray;
 padding: 0 1em;
}
th {
  border-bottom: 1px solid black;
  text-align:center;
  font-weight:bold;
}

    </style>
  </head>
<body>
<table style='margin-left:auto; margin-right: auto;'>
    <thead>
        <tr>
            <th style='border: 0px'></th>
            <th>Language</th>
            <th>Valid Codes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach(luminous::scanners() as $l=>$codes) { ?>
            <tr> 
                <td><?php echo ++$i; ?></td>
                <td><?php echo $l; ?></td>
                <td><?php echo join(', ', $codes); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>