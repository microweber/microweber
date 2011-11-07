@Mercury.modalHandlers.insertTable = ->
  table = @element.find('#table_display table')

  # make td's selectable
  table.click (event) =>
    cell = jQuery(event.target)
    table = cell.closest('table')
    table.find('.selected').removeAttr('class')
    cell.addClass('selected')
    Mercury.tableEditor(table, cell, '&nbsp;')

  # select the first td
  firstCell = table.find('td, th').first()
  firstCell.addClass('selected')
  Mercury.tableEditor(table, firstCell, '&nbsp;')

  # make the buttons work
  @element.find('input.action').click (event) =>
    action = jQuery(event.target).attr('name')
    switch action
      when 'insertRowBefore' then Mercury.tableEditor.addRow('before')
      when 'insertRowAfter' then Mercury.tableEditor.addRow('after')
      when 'deleteRow' then Mercury.tableEditor.removeRow()
      when 'insertColumnBefore' then Mercury.tableEditor.addColumn('before')
      when 'insertColumnAfter' then Mercury.tableEditor.addColumn('after')
      when 'deleteColumn' then Mercury.tableEditor.removeColumn()
      when 'increaseColspan' then Mercury.tableEditor.increaseColspan()
      when 'decreaseColspan' then Mercury.tableEditor.decreaseColspan()
      when 'increaseRowspan' then Mercury.tableEditor.increaseRowspan()
      when 'decreaseRowspan' then Mercury.tableEditor.decreaseRowspan()

  # set the alignment
  @element.find('#table_alignment').change =>
    table.attr({align: @element.find('#table_alignment').val()})

  # set the border
  @element.find('#table_border').keyup =>
    table.attr({border: parseInt(@element.find('#table_border').val())})

  # set the cellspacing
  @element.find('#table_spacing').keyup =>
    table.attr({cellspacing: parseInt(@element.find('#table_spacing').val())})

  # build the table on form submission
  @element.find('form').submit (event) =>
    event.preventDefault()
    table.find('.selected').removeAttr('class')
    table.find('td, th').html('<br/>')

    html = jQuery('<div>').html(table).html()
    value = html.replace(/^\s+|\n/gm, '').replace(/(<\/.*?>|<table.*?>|<tbody>|<tr>)/g, '$1\n')

    Mercury.trigger('action', {action: 'insertTable', value: value})
    @hide()

