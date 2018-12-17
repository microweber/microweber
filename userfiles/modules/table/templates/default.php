<?php
/*

type: layout

name: Default

description: Default with horizontal scrolling

*/
?>

<style>
.modtable {
  float:left;
  width:100%;
  overflow-x: auto;
  margin-bottom:10px;
  margin-top:10px;
}

.modtable table {
  border-collapse:collapse;
}

.modtable th{
    min-height:10px;
	border:1px solid #cacaca;
	background: rgb(231, 235, 245);
	padding:4px;
}

.modtable td {
	border:1px solid #cacaca;
	text-align:left;
    min-height:10px;
	padding:4px;
}

.modtable tr:nth-child(even) {
  background-color: #fafafa;
    min-height:10px;
}
</style>

<script>

mw.moduleJS('<?php print module_url(); ?>js/table.js');

$(document).ready(function () {
	var foundData = false;
	<?php if(!empty($json)) { ?>
	try {
	  var json = <?php print htmlspecialchars_decode($json);?>;
	  var jdata = json.tabledata;
	  var tableId = '<?php print $params['id'];?>';
	  $("#"+tableId+" thead").children().remove();
	  $("#"+tableId+" tbody").children().remove();
	  buildTable(tableId,jdata);
	  foundData = true;
	} catch (e) {
	  console.log('No json data found');
	}
	<?php } ?>
	if(foundData==false){
		$('.r1c1').text('Data not found');
	}
});
</script>

<div class="modtable">

  <table id="<?php print $params['id'];?>" align="left" cellspacing="0" celpadding="0">
	<thead>
		<tr>
			<th class="th mw-table-h1" classname="th mw-table-h1"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="col r1c1" classname="col r1c1">Loading data ...</td>
		</tr>
	</tbody>
  </table>
</div>