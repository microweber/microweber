|<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Добавне на филтър</h3>
    </div>
    <div class="panel-body">
        <form action="https://demo.mycredocart.com/admin/index.php?route=catalog/filter/edit&amp;user_token=CAozggrLhEAbeImxucYkR6kwdRpEBNe5&amp;filter_group_id=1" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
            <fieldset id="option-value">
                <legend>Филтрирай група</legend>
                <div class="form-group required">
                    <label class="col-sm-2 control-label">Име на груповия филтър:</label>
                    <div class="col-sm-10">                 <div class="input-group"><span class="input-group-addon"><img src="language/bg-bg/bg-bg.png" title="Bulgarian"></span>
                            <input type="text" name="filter_group_description[2][name]" value="Държава" placeholder="Име на груповия филтър:" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort-order">Подреждане:</label>
                    <div class="col-sm-10">
                        <input type="text" name="sort_order" value="0" placeholder="Подреждане:" id="input-sort-order" class="form-control">
                    </div>
                </div>
            </fieldset>
            <fieldset id="option-value">
                <legend>Филтрирай стойности</legend>
                <table id="filter" class="table table-elegant table-hover">
                    <thead>
                    <tr>
                        <td class="text-start required">Име на филтъра:</td>
                        <td class="text-end">Подреждане:</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>

                    <tr id="filter-row0">
                        <td class="text-start" style="width: 70%;"><input type="hidden" name="filter[0][filter_id]" value="1">
                            <div class="input-group"><span class="input-group-addon"><img src="language/bg-bg/bg-bg.png" title="Bulgarian"></span>
                                <input type="text" name="filter[0][filter_description][2][name]" value="България" placeholder="Име на филтъра:" class="form-control">
                            </div>
                        </td>
                        <td class="text-end"><input type="text" name="filter[0][sort_order]" value="0" placeholder="Подреждане:" id="input-sort-order" class="form-control"></td>
                        <td class="text-end"><button type="button" onclick="$('#filter-row0').remove();" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Премахване"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <tr id="filter-row1">
                        <td class="text-start" style="width: 70%;"><input type="hidden" name="filter[1][filter_id]" value="2">
                            <div class="input-group"><span class="input-group-addon"><img src="language/bg-bg/bg-bg.png" title="Bulgarian"></span>
                                <input type="text" name="filter[1][filter_description][2][name]" value="Япония" placeholder="Име на филтъра:" class="form-control">
                            </div>
                        </td>
                        <td class="text-end"><input type="text" name="filter[1][sort_order]" value="0" placeholder="Подреждане:" id="input-sort-order" class="form-control"></td>
                        <td class="text-end"><button type="button" onclick="$('#filter-row1').remove();" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Премахване"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-end"><button type="button" onclick="addFilterRow();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Добави филтър"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                    </tfoot>
                </table>
            </fieldset>
        </form>
    </div>
</div>
