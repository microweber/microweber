<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Table Builder'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">

                <?php
                /*
                    Build html tables by Ezyweb.uk v1.0 14/09/2018

                    Future enharnsements:
                    1) add method to move position of rows and columns
                    2) add support for colspan
                    3) add cell styling
                    4) add template using jexcel.js https://bossanova.uk/jexcel
                    5) add template using bootgrid
                    6) add import csv option
                    7) add template to re-orientate on smaller screens https://codepen.io/AllThingsSmitty/pen/MyqmdM
                    8) add auto-save cell contents onblur
                    9) change to on-page editing?

                    Changes:
                    i) context sensitive menu added to insert and delete rows, and columns
                    ii) html now saved as html instead of json format

                */
                ?>
                <?php

                must_have_access();

                $tablehtml = get_option('table_html', $params['id']);
                $tablehtml = preg_replace("/\r|\n/", "", $tablehtml);
                $tablehtml = str_replace('id="' . $params['id'] . '"', 'id="htmltable"', $tablehtml);


                // Depricated - start
                $settings = get_option('settings', $params['id']);
                $json = ($settings ? $settings : '');
                // Depricated - end
                ?>

                <style>
                    #modtable {
                        float: left;
                        width: 100%;
                        overflow-y: auto;
                        overflow-x: auto;
                        height: 300px;
                        border: 1px solid #cacaca;
                        margin-bottom: 10px;
                        margin-top: 10px;
                    }

                    #modtable table {
                        border-collapse: collapse;
                    }

                    #modtable th {
                        height: 30px;
                        border: 1px solid #cacaca;
                        background: rgb(231, 235, 245);
                        padding: 4px;
                    }

                    #modtable td {
                        height: 30px;
                        border: 1px solid #cacaca;
                        text-align: left;
                        padding: 4px;
                    }

                    #modtable tr:nth-child(even) {
                        background-color: #fafafa;
                        min-height: 10px;
                    }

                    #modtable .tab {
                        display: none;
                    }

                    #modtable td:empty:after{
                        content: 'Type here';
                        color: #cfcfcf;
                        font-size: 12px;
                    }
                </style>

                <script>
                    mw.lib.require('font_awesome5');
                    mw.require("<?php echo $config['url_to_module'];?>contextMenu/jquery.contextMenu.min.js");
                    mw.require("<?php echo $config['url_to_module'];?>contextMenu/jquery.contextMenu.min.css");
                    mw.require("<?php echo $config['url_to_module'];?>contextMenu/font/context-menu-icons.eot");


                    // -- Depricated start --

                    function insertRow() {
                        var tbl = document.getElementById('htmltable');
                        var nrows = tbl.rows.length,
                            row = tbl.insertRow(nrows),
                            i;
                        for (i = 0; i < tbl.rows[0].cells.length; i++) {
                            var cellRef = 'r' + (nrows) + 'c' + (i + 1);
                            createCell(row.insertCell(i), '');
                            //createCell(row.insertCell(i), '', 'row ' + cellRef);
                        }
                    }

                    function appendRow() {
                        var tbl = document.getElementById('htmltable');
                        var nrows = tbl.rows.length,
                            row = tbl.insertRow(nrows),
                            i;
                        for (i = 0; i < tbl.rows[0].cells.length; i++) {
                            //var cellRef = 'r' + (nrows) + 'c' + (i + 1);
                            //createCell(row.insertCell(i), '', 'row ' + cellRef);
                            createCell(row.insertCell(i), '');
                        }
                    }

                    function createHeaderCell(row, text) {
                        var headerCell = document.createElement("th");
                        headerCell.innerHTML = text;
                        //headerCell.setAttribute('class', style);
                        //headerCell.setAttribute('className', style);
                        headerCell.setAttribute('contenteditable', true);
                        row.appendChild(headerCell);
                    }

                    function createCell(cell, text) {
                        var txt = document.createTextNode(text);
                        //cell.setAttribute('class', style);
                        //cell.setAttribute('className', style);    // set className attribute for IE (?!)
                        cell.setAttribute('contenteditable', true);
                        cell.appendChild(txt);
                    }

                    // append column to the HTML table
                    function appendColumn() {
                        var tbl = document.getElementById('htmltable'),
                            i;
                        for (i = 0; i < tbl.rows.length; i++) {
                            var ncols = tbl.rows[i].cells.length;
                            if (i == 0) {
                                //var cellRef = 'mw-table-h' + (ncols + 1);
                                var text = 'Header ' + (ncols + 1);
                                createHeaderCell(tbl.rows[i], text);
                                //createHeaderCell(tbl.rows[i], text, 'th ' + cellRef);
                            } else {
                                //var cellRef = 'r' + (i) + 'c' + (ncols + 1);
                                createCell(tbl.rows[i].insertCell(ncols), '');
                                //createCell(tbl.rows[i].insertCell(ncols), '', 'col ' + cellRef);
                            }
                        }
                    }

                    function deleteRow(row) {
                        row = row || '';

                        var i;
                        var tbl = document.querySelector('#htmltable tbody');

                        if (tbl.querySelectorAll('tr').length <= 1) return;
                        (top.mw || window.mw).confirm('Are you sure', function () {
                            if (row.nodeType === 1) {
                                $(row).remove();
                                return;
                            }
                            if (row == 'last') {
                                lastRow = tbl.rows.length - 1;
                                tbl.deleteRow(lastRow);
                            } else if (row == 'all') {
                                lastRow = tbl.rows.length - 2;
                                // delete rows including header with index greater then 0
                                for (i = lastRow; i >= 0; i--) {
                                    tbl.deleteRow(i);
                                }
                            } else if (row == 'allbutone') {
                                lastRow = tbl.rows.length - 1;
                                // delete rows except header
                                for (i = lastRow; i > 0; i--) {
                                    tbl.deleteRow(i);
                                }
                            } else if (row != '') {
                                tbl.deleteRow(row);
                            } else {
                                alert('row not set in function deleteRow');
                            }
                        });
                    }

                    function deleteColumn(col) {
                        col = col || '';
                        var tbl = document.getElementById('htmltable');

                        if (tbl.querySelectorAll('th').length <= 1) return;
                        (top.mw || window.mw).confirm('Are you sure', function () {
                            if (col.nodeType === 1) {
                                $(row).remove();
                                return;
                            }
                            if (col == 'last') {
                                lastCol = tbl.rows[0].cells.length - 1;
                                for (i = 0; i < tbl.rows.length; i++) {
                                    tbl.rows[i].deleteCell(lastCol);
                                }
                            } else if (col == 'all') {
                                lastCol = tbl.rows[0].cells.length - 1;
                                // delete cells with index greater than or equal to 0 (for each row)
                                for (i = 0; i < tbl.rows.length; i++) {
                                    for (j = lastCol; j >= 0; j--) {
                                        tbl.rows[i].deleteCell(j);
                                    }
                                }
                            } else if (col == 'allbutone') {
                                lastCol = tbl.rows[0].cells.length - 1;
                                // delete cells with index greater then 0 (for each row)
                                for (i = 0; i < tbl.rows.length; i++) {
                                    for (j = lastCol; j > 0; j--) {
                                        tbl.rows[i].deleteCell(j);
                                    }
                                }
                            } else if (col != '') {
                                for (i = 0; i < tbl.rows.length; i++) {
                                    tbl.rows[i].deleteCell(col);
                                }
                            } else {
                                alert('col not set in function deleteColumn');
                            }
                        });
                    }

                    // ---- build table functions ----

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
                                if (cellValue == null) {
                                    cellValue = "";
                                }
                                //var tdClass = "col r" + i + "c" + colIndex;
                                //var td = $('<td/>').html(cellValue).addClass(tdClass).attr('contenteditable', 'true');
                                var td = $('<td/>').html(cellValue).attr('contenteditable', 'true');
                                row$.append(td);
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
                                    headerTr$.append($('<th/>').html(key).addClass(thClass).attr('contenteditable', 'true'));
                                }
                            }
                        }
                        thead.append(headerTr$);
                        return columnSet;
                    }

                    // -- Depricated end --


                    $(function () {
                        $("#htmltable").contextMenu({
                            selector: 'td',
                            callback: function (key, options) {
                                switch (key) {
                                    case 'insert_row_above':
                                    case 'insert_row_below':
                                        var thisRow = $(this).parent();
                                        var nCols = thisRow.children().length;
                                        var newRow = '<tr>';
                                        for (i = 1; i <= nCols; i++) {
                                            newRow = newRow + '<td contenteditable="true"></td>';
                                        }
                                        newRow = newRow + '</tr>';
                                        var i = thisRow.index();
                                        if (key == 'insert_row_above') {
                                            $('#htmltable > tbody > tr').eq(i).before(newRow);
                                        } else if (key == 'insert_row_below') {
                                            $('#htmltable > tbody > tr').eq(i).after(newRow);
                                        }
                                        break;
                                    case 'insert_column_before':
                                    case 'insert_column_after':
                                        var i = $(this).index();
                                        $('#htmltable > thead').find('tr').each(function () {
                                            var html = '<th contenteditable="true"></th>';
                                            if (key == 'insert_column_before') {
                                                $(this).find('th').eq(i).before(html);
                                            } else if (key == 'insert_column_after') {
                                                $(this).find('th').eq(i).after(html);
                                            }
                                        });
                                        $('#htmltable > tbody').find('tr').each(function () {
                                            var html = '<td contenteditable="true"></td>';
                                            if (key == 'insert_column_before') {
                                                $(this).find('td').eq(i).before(html);
                                            } else if (key == 'insert_column_after') {
                                                $(this).find('td').eq(i).after(html);
                                            }
                                        });
                                        break;
                                    case 'delete_row':
                                        $(this).parent().remove();
                                        break;
                                    case 'delete_column':
                                        var i = $(this).index();
                                        $('#htmltable > thead').find('tr').each(function () {
                                            $(this).find('th').eq(i).remove();
                                        });
                                        $('#htmltable > tbody').find('tr').each(function () {
                                            $(this).find('td').eq(i).remove();
                                        });
                                        break;
                                }
                            },
                            items: {
                                "insert_row_above": {name: "Insert row above", icon: "edit"},
                                "insert_row_below": {name: "Insert row below", icon: "edit"},
                                "insert_column_before": {name: "Insert column before", icon: "edit"},
                                "insert_column_after": {name: "Insert column after", icon: "edit"},
                                "delete_row": {name: "Delete row", icon: "delete"},
                                "delete_column": {name: "Delete column", icon: "delete"},
                            }
                        });
                    });


                    // ---- button click functions -----


                    handlePaste = function () {
                        $("#htmltable th, #htmltable td").on('paste', function (e) {
                            if (e.originalEvent) {
                                var clipboard = e.originalEvent.clipboardData || window.clipboardData;
                            }
                            else {
                                var clipboard = e.clipboardData || window.clipboardData;
                            }
                            var text = clipboard.getData('text');
                            mw.wysiwyg.insert_html(text);
                            e.preventDefault()

                        });
                    };


                    $(document).ready(function () {
                        <?php if(!empty($tablehtml)) { ?>
                        $('#htmltable th').attr('contenteditable', 'true');
                        $('#htmltable td').attr('contenteditable', 'true');

                        <?php } elseif(!empty($json)) { ?>
                        // -- Depricated start --
                        try {
                            var json = <?php print $json;?>;
                            var jdata = json.tabledata;
                            var tableId = "htmltable";
                            $("#htmltable tbody").empty();
                            $("#htmltable thead").empty();
                            buildTable(tableId, jdata);
                        } catch (e) {
                            // No data found so default table will load
                        }
                        // -- Depricated end --
                        <?php } ?>


                        $(document).on('click', '#saveData', function (event) {

                            /* -- Depricated start --
                             var myRows = [];
                             var headersText = [];
                             var tableCss = []; // place holder
                             var $headers = $("th");
                             var $cells;
                             var $rows = $("tbody tr").each(function (index) {
                             $cells = $(this).find("td");
                             myRows[index] = {};
                             $cells.each(function (cellIndex) {
                             if (headersText[cellIndex] === undefined) {
                             headersText[cellIndex] = $($headers[cellIndex]).html();
                             }
                             myRows[index][headersText[cellIndex]] = $(this).html();
                             });
                             });
                             var myObj = {
                             "tabledata": myRows,
                             "tablecss": tableCss
                             };
                             var json = JSON.stringify(myObj);
                             mw.$('#settingsfield').val(json).trigger('change');
                             // -- Depricated end -- */

                            var tableclone = $("#modtable").clone();
                            tableclone.find('#htmltable').attr('id', '<?php print $params['id'];?>');
                            tableclone.find('th').each(function () {
                                $(this).removeAttr('contenteditable');
                            });
                            tableclone.find('td').each(function () {
                                $(this).removeAttr('contenteditable');
                            });
                            var tablehtml = tableclone.html();

                            mw.$('#htmlfield').val(tablehtml).trigger('change');
                        });
                    });
                </script>

                <!-- Settings Content -->
                <div class="module-live-edit-settings module-table-settings">

                    <!-- <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/> //-->

                    <input type="hidden" class="mw_option_field" name="table_html" id="htmlfield"/>

                    <div id="modtable">
                        <?php
                        if (!empty($tablehtml)) {
                            print $tablehtml;
                        } else {
                            ?>
                            <table id="htmltable" align="left" cellspacing="0" celpadding="0">
                                <thead>
                                <tr>
                                    <th contentEditable="true">Header 1</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td contentEditable="true">Cell 1</td>
                                </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>


                    <div class="row justify-content-between">
                        <div class="col-auto text-center text-sm-left">
                            <button class="btn btn-outline-success btn-sm mb-2" type="button" onclick="appendColumn()">Add Column</button>
                            <button class="btn btn-outline-success btn-sm mb-2" type="button" onclick="appendRow()">Add Row</button>
                        </div>

                        <div class="col-auto text-center text-sm-right">
                            <button class="btn btn-outline-danger btn-sm mb-2" type="button" onclick="deleteColumn('all')">Delete All Columns</button>
                            <button class="btn btn-outline-danger btn-sm mb-2" type="button" onclick="deleteRow('all')">Delete All Rows</button>
                        </div>
                    </div>

                    <hr class="thin"/>

                    <div class="text-end text-right">
                        <button class="btn btn-success btn-sm" id="saveData">Save</button>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
