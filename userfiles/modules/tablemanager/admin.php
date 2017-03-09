<?php only_admin_access() ?>
<?php

    $tr = get_option('tr', $params['id']);
    $th = get_option('th', $params['id']);

    /*$tr = json_decode($tr, true);
    $th = json_decode($th, true);*/



?>
<div class="module-live-edit-settings">
<style scoped="scoped">

#holder{
    overflow: auto;
}

#table{
    width: auto !important;
}

.mw-ui-field{
    border-color: transparent;
    background: transparent
}

tr:hover .mw-ui-field{
    background-color: white;
    border-color: #cdcdcd;
}

/*
 * dragtable
 *
 * @Version 2.0.15
 *
 * default css
 *
 */
/*##### the dragtable stuff #####*/
.dragtable-sortable {
    list-style-type: none; margin: 0; padding: 0; -moz-user-select: none;
}
.dragtable-sortable li {
    margin: 0; padding: 0; float: left; font-size: 1em; background: white;
}

.dragtable-sortable th, .dragtable-sortable td{
    border-left: 0px;
}

.dragtable-sortable li:first-child th, .dragtable-sortable li:first-child td {
    border-left: 1px solid #CCC;
}

.ui-sortable-helper {
    opacity: 0.7;filter: alpha(opacity=70);
}
.ui-sortable-placeholder {
    -moz-box-shadow: 4px 5px 4px #C6C6C6 inset;
    -webkit-box-shadow: 4px 5px 4px #C6C6C6 inset;
    box-shadow: 4px 5px 4px #C6C6C6 inset;
    border-bottom: 1px solid #CCCCCC;
    border-top: 1px solid #CCCCCC;
    visibility: visible !important;
    background: #EFEFEF !important;
    visibility: visible !important;
}
.ui-sortable-placeholder * {
    opacity: 0.0; visibility: hidden;
}


.tr-handle{
    display: none;
}

tr > td:first-child > .tr-handle{
    display: inline-block;
}

#table .mw-icon-drag{
    float: left;
    margin-top: 9px;
    font-size: 19px;
    margin-right: 11px;
    color: #bbb;

}


</style>
<input type="hidden" class="mw_option_field" name="tr" id="tr" />
<input type="hidden" class="mw_option_field" name="th" id="th" />

<script>
mw.lib.require('jqueryui')
    tr = <?php print $tr?$tr:'[]'; ?>;
    th = <?php print $th?$th:'[]'; ?>;

    window.tr = window.tr || [];
    window.th = window.th || [];
</script>

<script src="<?php print $config['url_to_module'] ?>jquery.dragtable.js"></script>
<?php /*<script src="https://rawgit.com/akottr/dragtable/master/jquery.dragtable.js"></script>*/ ?>

<script>

    table = {
        addRow:function(){
            var html = '<tr>';
            $.each(th, function(){
                html += '<td><span class="mw-icon-drag tr-handle"></span><input class="mw-ui-field" placeholder="'+this+'"></td>';
            })
            html += '</tr>'
            $("#tbody").append(html);
            this.save()
            this.initSave();
            this.initDrag()
            $('#table tbody').sortable({
                handle:'.tr-handle'
            })
        },
        addCol:function(name){
            $("#thead").append('<th><span class="col-handle mw-icon-drag"></span><input class="mw-ui-field" value="Title"></th>');
            $("#tbody tr").each(function(){
                $(this).append('<td><span class="mw-icon-drag tr-handle"></span><input class="mw-ui-field" value="Title"></td>')
            })
            this.save()
            this.initSave()
            this.initDrag()
            this.configUI();
        },
        buildTh:function(){
            var thead = $("#thead").empty(),
                html = '';
            $.each(th, function(){
                html += '<th><span class="mw-icon-drag col-handle"></span><input class="mw-ui-field" value="'+this+'"></th>';
            })
            thead.html(html);
        },
        buildTr:function(){
            var tbody = $("#tbody").empty(),
                html = '';
            $.each(tr, function(){
                html += '<tr>';
                $.each(this, function(){
                    html += '<td><span class="mw-icon-drag tr-handle"></span><input class="mw-ui-field" value="'+this+'"></td>';
                });
                html += '</tr>';
            });
            tbody.html(html);
        },
        initDrag:function(){
            $('#table').dragtable('destroy');
            $('#table').dragtable({
                dragHandle:'.col-handle'
            });
        },
        buildTable:function(){
            this.buildTh()
            this.buildTr();
            $('#table').dragtable({
                dragHandle:'.col-handle'
            });
            this.initSave();
            this.configUI();
        },
        save:function(){

                tr = [];
                th = [];
                $("#thead input").each(function(){
                    th.push(this.value);
                });
                $("#tbody tr").each(function(){
                    var temp = [];
                    $('input', this).each(function(){
                        temp.push(this.value);
                    });
                    tr.push(temp);
                });




        },
        ServerSave:function(){
             $("#tr").val(JSON.stringify(tr)).trigger('change');
             $("#th").val(JSON.stringify(th)).trigger('change');
        },
        initSave:function(){
            $("#table .mw-ui-field").each(function(){
                if(!$(this).hasClass('activated')){
                    $(this).addClass('activated');
                    $(this).on('input', function(){
                        table.save();
                    })
                }

            })
        },
        configUI:function(){
            if($("#thead th").length == 0){
                $("#adrow").hide()
            }
            else{
                $("#adrow").show()
            }
        }
    }

    $(document).ready(function(){
        window.top.$(".mw_modal")[0].modal.resize('90%').center()
        table.buildTable()
    })
</script>

<span class="mw-ui-btn mw-ui-btn-notification pull-right" onclick="table.ServerSave()">Save</span>

<span class="mw-ui-btn" onclick="table.addCol();" id="adcol"><span class="mw-icon-plus"></span> Add Column</span>
<span class="mw-ui-btn" onclick="table.addRow();" id="adrow"><span class="mw-icon-plus"></span> Add Row</span>

<hr>

<div id="holder"><table id="table" class="mw-ui-table">
    <thead>
        <tr id="thead"></tr>
    </thead>
    <tbody id="tbody">

    </tbody>
</table></div>

</div>



