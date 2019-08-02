// -- Depricated start --
// Build HTML Table from json data
function buildTable(tableId, jdata) {
    var elTable = jQuery("#" + tableId);
    var columns = addColumnHeaders(elTable, jdata);
    var tbody = elTable.find("tbody");
    if (tbody.length === 0) {  //if there is no tbody element, add one.
        tbody = jQuery("<tbody></tbody>").appendTo(elTable);
    }
    for (var i = 0; i < jdata.length; i++) {
        var row$ = $('<tr/>');
        for (var colIndex = 0; colIndex < columns.length; colIndex++) {
            var cellValue = jdata[i][columns[colIndex]];
            var tdClass = "col r" + i + "c" + colIndex;
            if (cellValue == null) {
                cellValue = "";
            }
            row$.append($('<td/>').html(cellValue).addClass(tdClass));
        }
        tbody.append(row$);
    }
}

// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records
function addColumnHeaders(elTable, jdata) {
    var columnSet = [];
    var headerTr$ = $('<tr/>');
    var thead = elTable.find("thead");
    if (thead.length === 0) {  //if there is no thead element, add one.
        thead = jQuery("<thead></thead>").appendTo(elTable);
    }
    for (var i = 0; i < jdata.length; i++) {
        var rowHash = jdata[i];
        var c = 0;
        for (var key in rowHash) {
            c++;
            var thClass = "th mw-table-h" + c;
            if ($.inArray(key, columnSet) == -1) {
                columnSet.push(key);
                headerTr$.append($('<th/>').html(key).addClass(thClass));
            }
        }
    }
    thead.append(headerTr$);
    return columnSet;
}
// -- Depricated end --