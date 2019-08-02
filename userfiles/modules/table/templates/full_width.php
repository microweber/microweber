<?php
/*

type: layout

name: Full Width

description: Full Width

*/
?>

<style>
.modtable.full-width {
  float:left;
  width:100%;
  overflow-x: auto;
  margin-bottom:10px;
  margin-top:10px;
}

.modtable.full-width table {
  border-collapse:collapse;
  width:100%;
  border:1px solid #60b306;
}

.modtable.full-width th{
        min-height: 10px;
	background-color: #60b306;
	padding:6px;
	font-size:17px;
	color:white;
}

.modtable.full-width td {
	text-align:left;
	min-height:10px;
	padding:6px;
}

.modtable.full-width tr:nth-child(even) {
  background-color: #f8fcea;
  min-height:10px;
}

.modtable.full-width td i {
	font-family: "FontAwesome";
	content: '\2714';
	color: #60b306;
	margin-left:20px;
	/* margin-right:20px;*/
	font-size:30px;
}
</style>

<script>
    mw.moduleJS('<?php print module_url(); ?>js/table.js');
    mw.lib.require('font_awesome');

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
            $("#" + tableId + " thead").children().remove();
            $("#" + tableId + " tbody").children().remove();
            buildTable(tableId, jdata);
            foundData = true;
        } catch (e) {
            console.log('No json data found');
        }
        // -- Depricated end --
        <?php } ?>
        if (foundData == false) {
		    $('.modtable > #<?php print $params['id']; ?> > tbody > tr > td').eq(0).text('No data found');
            //$('.r1c1').text('Data not found');
        }
    });
</script>

<div class="modtable full-width">
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