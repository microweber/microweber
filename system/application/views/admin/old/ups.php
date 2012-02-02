<table>
<?php
if(!isset($image)){
	if(!empty($output_xml)){
		foreach($output_xml as $key => $val){
			if ($key != "graphic_image")
				echo "<tr><td>$key</td><td>$val</td></tr>";
		}
	}else{
		echo '<tr><td collsapn="2">No data for output!</td></tr>';
	}
//$printer = "\\\server\\CPL-310_Series";
//$content  = base64_decode($image["graphic_image"]);
}else{
	header('Content-Type: application/epl2');
	print($image);
	exit;
}
?>
<tr>
	<td colspan="2"><a href="<?php echo site_url('admin/ups_order/id/'.$id.'/1');?>">Click here to print shipping label</a></td>
</tr>



<tr>
	<td colspan="2">
	
	
	
	
	
	
	
	
	
	
	<small><br><br><br><br><a target="_blank" href="http://www.ups.com/content/us/en/resources/ship/create/labels/technical.html#Download+the+UPS+Thermal+Printer+Plugin+or+ActiveX+Control+Program">Those instructions will help you through the process of installing a UPS Thermal Printer.</a></small>
	
	 </td>
</tr>

</table>




