<?php
/*

type: layout

name: Default

description: Default with horizontal scrolling

*/
?>

<style>
.modtable.default {
  float:left;
  width:100%;
  overflow-x: auto;
  margin-bottom:10px;
  margin-top:10px;
}

.modtable.default table {
  border-collapse:collapse;
}

.modtable.default th{
    min-height:10px;
	border:1px solid #cacaca;
	background: rgb(231, 235, 245);
	padding:4px;
}

.modtable.default td {
	border:1px solid #cacaca;
	text-align:left;
    min-height:10px;
	padding:4px;
}

.modtable.default tr:nth-child(even) {
  background-color: #fafafa;
    min-height:10px;
}
</style>

<script>

mw.moduleJS('<?php print module_url(); ?>js/table.js');

$(document).ready(function () {
	var foundData = false;
    <?php if(!empty($tablehtml)) { ?>
    	foundData = true;
    <?php } elseif(!empty($json)) { ?>
        // -- Depricated start --
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
        // -- Depricated end --
	<?php } ?>
	if(foundData==false){
		    $('.modtable > #<?php print $params['id']; ?> > tbody > tr > td').eq(0).text('No data found');
            //$('.r1c1').text('Data not found');
	}
});
</script>

<div class="modtable default">
    <?php
    if(!empty($tablehtml)) {
    	print $tablehtml;
    } else {
    ?>
    <table id="<?php print $params['id']; ?>" align="left" cellspacing="0" celpadding="0">
		<thead>
			<tr>
				<th>Column Name</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Loading data ...</td>
			</tr>
		</tbody>
    </table>
    <?php } ?>
</div>