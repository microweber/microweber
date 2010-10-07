/*
 Transform a table to a jqGrid.
 Peter Romianowski <peter.romianowski@optivo.de> 
 If the first column of the table contains checkboxes or
 radiobuttons then the jqGrid is made selectable.
*/
// Addition - selector can be a class or id
function tableToGrid(selector, options) {
$(selector).each(function() {
	if(this.grid) {return;} //Adedd from Tony Tomov
	// This is a small "hack" to make the width of the jqGrid 100%
	$(this).width("99%");
	var w = $(this).width();

	// Text whether we have single or multi select
	var inputCheckbox = $('input[type=checkbox]:first', $(this));
	var inputRadio = $('input[type=radio]:first', $(this));
	var selectMultiple = inputCheckbox.length > 0;
	var selectSingle = !selectMultiple && inputRadio.length > 0;
	var selectable = selectMultiple || selectSingle;
	var inputName = inputCheckbox.attr("name") || inputRadio.attr("name");

	// Build up the columnModel and the data
	var colModel = [];
	var colNames = [];
	$('th', $(this)).each(function() {
		if (colModel.length == 0 && selectable) {
			colModel.push({
				name: '__selection__',
				index: '__selection__',
				width: 0,
				hidden: true
			});
			colNames.push('__selection__');
		} else {
			colModel.push({
				name: $(this).attr("id") || $(this).html(),
				index: $(this).attr("id") || $(this).html(),
				width: $(this).width() || 150
			});
			colNames.push($(this).html());
		}
	});
	var data = [];
	var rowIds = [];
	var rowChecked = [];
	$('tbody > tr', $(this)).each(function() {
		var row = {};
		var rowPos = 0;
		data.push(row);
		$('td', $(this)).each(function() {
			if (rowPos == 0 && selectable) {
				var input = $('input', $(this));
				var rowId = input.attr("value");
				rowIds.push(rowId || data.length);
				if (input.attr("checked")) {
					rowChecked.push(rowId);
				}
				row[colModel[rowPos].name] = input.attr("value");
			} else {
				row[colModel[rowPos].name] = $(this).html();
			}
			rowPos++;
		});
	});

	// Clear the original HTML table
	$(this).empty();

	// Mark it as jqGrid
	$(this).addClass("scroll");

	$(this).jqGrid($.extend({
		datatype: "local",
		width: w,
		colNames: colNames,
		colModel: colModel,
		multiselect: selectMultiple
		//inputName: inputName,
		//inputValueCol: imputName != null ? "__selection__" : null
	}, options || {}));

	// Add data
	for (var a = 0; a < data.length; a++) {
		var id = null;
		if (rowIds.length > 0) {
			id = rowIds[a];
			if (id && id.replace) {
				// We have to do this since the value of a checkbox
				// or radio button can be anything 
				id = encodeURIComponent(id).replace(/[.\-%]/g, "_");
			}
		}
		if (id == null) {
			id = a + 1;
		}
		$(this).addRowData(id, data[a]);
	}

	// Set the selection
	for (var a = 0; a < rowChecked.length; a++) {
		$(this).setSelection(rowChecked[a]);
	}
});
};
