@Mercury.tableEditor = (table, cell, cellContent) ->
  Mercury.tableEditor.load(table, cell, cellContent)
  return Mercury.tableEditor

jQuery.extend Mercury.tableEditor, {

  load: (@table, @cell, @cellContent = '') ->
    @row = @cell.parent('tr')
    @columnCount = @getColumnCount()
    @rowCount = @getRowCount()


  addColumn: (position = 'after') ->
    sig = @cellSignatureFor(@cell)

    for row, i in @table.find('tr')
      rowSpan = 1
      matchOptions = if position == 'after' then {right: sig.right} else {left: sig.left}
      if matching = @findCellByOptionsFor(row, matchOptions)
        newCell = jQuery("<#{matching.cell.get(0).tagName}>").html(@cellContent)
        @setRowspanFor(newCell, matching.height)
        if position == 'before' then matching.cell.before(newCell) else matching.cell.after(newCell)
        i += matching.height - 1
      else if intersecting = @findCellByIntersectionFor(row, sig)
        @setColspanFor(intersecting.cell, intersecting.width + 1)


  removeColumn: ->
    sig = @cellSignatureFor(@cell)
    return if sig.width > 1

    removing = []
    adjusting = []
    for row, i in @table.find('tr')
      if matching = @findCellByOptionsFor(row, {left: sig.left, width: sig.width})
        removing.push(matching.cell)
        i += matching.height - 1
      else if intersecting = @findCellByIntersectionFor(row, sig)
        adjusting.push(intersecting.cell)

    jQuery(cell).remove() for cell in removing
    @setColspanFor(cell, @colspanFor(cell) - 1) for cell in adjusting


  addRow: (position = 'after') ->
    newRow = jQuery('<tr>')

    if (rowspan = @rowspanFor(@cell)) > 1 && position == 'after'
      @row = jQuery(@row.nextAll('tr')[rowspan - 2])

    cellCount = 0
    for cell in @row.find('th, td')
      colspan = @colspanFor(cell)
      newCell = jQuery("<#{cell.tagName}>").html(@cellContent)
      @setColspanFor(newCell, colspan)
      cellCount += colspan
      if (rowspan = @rowspanFor(cell)) > 1 && position == 'after'
        @setRowspanFor(cell, rowspan + 1)
        continue

      newRow.append(newCell)

    if cellCount < @columnCount
      rowCount = 0
      for previousRow in @row.prevAll('tr')
        rowCount += 1
        for cell in jQuery(previousRow).find('td[rowspan], th[rowspan]')
          rowspan = @rowspanFor(cell)
          if rowspan - 1 >= rowCount && position == 'before'
            @setRowspanFor(cell, rowspan + 1)
          else if rowspan - 1 >= rowCount && position == 'after'
            if rowspan - 1 == rowCount
              newCell = jQuery("<#{cell.tagName}>").html(@cellContent)
              @setColspanFor(newCell, @colspanFor(cell))
              newRow.append(newCell)
            else
              @setRowspanFor(cell, rowspan + 1)

    if position == 'before' then @row.before(newRow) else @row.after(newRow)


  removeRow: ->
    # check to see that all cells have the same rowspan, and figure out the minimum rowspan
    rowspansMatch = true
    prevRowspan = 0
    minRowspan = 0
    for cell in @row.find('td, th')
      rowspan = @rowspanFor(cell)
      rowspansMatch = false if prevRowspan && rowspan != prevRowspan
      minRowspan = rowspan if rowspan < minRowspan || !minRowspan
      prevRowspan = rowspan

    return if !rowspansMatch && @rowspanFor(@cell) > minRowspan

    # remove any emtpy rows below
    if minRowspan > 1
      jQuery(@row.nextAll('tr')[i]).remove() for i in [0..minRowspan - 2]

    # find and move down any cells that have a larger rowspan
    for cell in @row.find('td[rowspan], th[rowspan]')
      sig = @cellSignatureFor(cell)
      continue if sig.height == minRowspan
      if match = @findCellByOptionsFor(@row.nextAll('tr')[minRowspan - 1], {left: sig.left, forceAdjacent: true})
        @setRowspanFor(cell, @rowspanFor(cell) - @rowspanFor(@cell))
        if match.direction == 'before' then match.cell.before(jQuery(cell).clone()) else match.cell.after(jQuery(cell).clone())

    if @columnsFor(@row.find('td, th')) < @columnCount
      # move up rows looking for cells with rowspans that might intersect
      rowsAbove = 0
      for aboveRow in @row.prevAll('tr')
        rowsAbove += 1
        for cell in jQuery(aboveRow).find('td[rowspan], th[rowspan]')
          # if the cell intersects with the row we're trying to calculate on, and it's index is less than where we've
          # gotten so far, add it
          rowspan = @rowspanFor(cell)
          @setRowspanFor(cell, rowspan - @rowspanFor(@cell)) if rowspan > rowsAbove

    @row.remove()


  increaseColspan: ->
    cell = @cell.next('td, th')
    return unless cell.length
    return if @rowspanFor(cell) != @rowspanFor(@cell)
    return if @cellIndexFor(cell) > @cellIndexFor(@cell) + @colspanFor(@cell)
    @setColspanFor(@cell, @colspanFor(@cell) + @colspanFor(cell))
    cell.remove()


  decreaseColspan: ->
    return if @colspanFor(@cell) == 1
    @setColspanFor(@cell, @colspanFor(@cell) - 1)
    newCell = jQuery("<#{@cell.get(0).tagName}>").html(@cellContent)
    @setRowspanFor(newCell, @rowspanFor(@cell))
    @cell.after(newCell)


  increaseRowspan: ->
    sig = @cellSignatureFor(@cell)
    nextRow = @row.nextAll('tr')[sig.height - 1]
    if nextRow && match = @findCellByOptionsFor(nextRow, {left: sig.left, width: sig.width})
      @setRowspanFor(@cell, sig.height + match.height)
      match.cell.remove()

  decreaseRowspan: ->
    sig = @cellSignatureFor(@cell)
    return if sig.height == 1
    nextRow = @row.nextAll('tr')[sig.height - 2]
    if match = @findCellByOptionsFor(nextRow, {left: sig.left, forceAdjacent: true})
      newCell = jQuery("<#{@cell.get(0).tagName}>").html(@cellContent)
      @setColspanFor(newCell, @colspanFor(@cell))
      @setRowspanFor(@cell, sig.height - 1)
      if match.direction == 'before' then match.cell.before(newCell) else match.cell.after(newCell)

  # Counts the columns of the first row (alpha row) in the table.  We can safely rely on the first row always being
  # comprised of a full set of cells or cells with colspans.
  getColumnCount: ->
    return @columnsFor(@table.find('thead tr:first-child, tbody tr:first-child, tfoot tr:first-child').first().find('td, th'))


  # Counts the rows of the table.
  getRowCount: ->
    return @table.find('tr').length


  # Gets the index for a given cell, taking into account that rows above it can have cells that have rowspans.
  cellIndexFor: (cell) ->
    cell = jQuery(cell)

    # get the row for the cell and calculate all the columns in it
    row = cell.parent('tr')
    columns = @columnsFor(row.find('td, th'))
    index = @columnsFor(cell.prevAll('td, th'))

    # if the columns is less than expected, we need to look above for rowspans
    if columns < @columnCount
      # move up rows looking for cells with rowspans that might intersect
      rowsAbove = 0
      for aboveRow in row.prevAll('tr')
        rowsAbove += 1
        for aboveCell in jQuery(aboveRow).find('td[rowspan], th[rowspan]')
          # if the cell intersects with the row we're trying to calculate on, and it's index is less than where we've
          # gotten so far, add it
          if @rowspanFor(aboveCell) > rowsAbove && @cellIndexFor(aboveCell) <= index
            index += @colspanFor(aboveCell)

    return index

  # Creates a signature for a given cell, which is made up if it's size, and itself.
  cellSignatureFor: (cell) ->
    sig = {cell: jQuery(cell)}
    sig.left = @cellIndexFor(cell)
    sig.width = @colspanFor(cell)
    sig.height = @rowspanFor(cell)
    sig.right = sig.left + sig.width
    return sig

  # Find a cell based on options.  Options can be:
  # right
  # or
  # left, [width], [forceAdjacent]
  # eg. findCellByOptionsFor(@row, {left: 1, width: 2, forceAdjacent: true})
  findCellByOptionsFor: (row, options) ->
    for cell in jQuery(row).find('td, th')
      sig = @cellSignatureFor(cell)
      if typeof(options.right) != 'undefined'
        return sig if sig.right == options.right
      if typeof(options.left) != 'undefined'

        if options.width
          return sig if sig.left == options.left && sig.width == options.width
        else if !options.forceAdjacent
          return sig if sig.left == options.left
        else if options.forceAdjacent
          if sig.left > options.left
            prev = jQuery(cell).prev('td, th')
            if prev.length
              sig = @cellSignatureFor(prev)
              sig.direction = 'after'
            else
              sig.direction = 'before'
            return sig

    if options.forceAdjacent
      sig.direction = 'after'
      return sig

    return null

  # Finds a cell that intersects with the current signature
  findCellByIntersectionFor: (row, signature) ->
    for cell in jQuery(row).find('td, th')
      sig = @cellSignatureFor(cell)
      return sig if sig.right - signature.left >= 0 && sig.right > signature.left
    return null


  # Counts all the columns in a given array of columns, taking colspans into
  # account.
  columnsFor: (cells) ->
    count = 0
    count += @colspanFor(cell) for cell in cells
    return count


  # Tries to get the colspan of a cell, falling back to 1 if there's none
  # specified.
  colspanFor: (cell) ->
    return parseInt(jQuery(cell).attr('colspan')) || 1


  # Tries to get the rowspan of a cell, falling back to 1 if there's none
  # specified.
  rowspanFor: (cell) ->
    return parseInt(jQuery(cell).attr('rowspan')) || 1


  # Sets the colspan of a cell, removing it if it's 1.
  setColspanFor: (cell, value) ->
    jQuery(cell).attr('colspan', if value > 1 then value else null)


  # Sets the rowspan of a cell, removing it if it's 1
  setRowspanFor: (cell, value) ->
    jQuery(cell).attr('rowspan', if value > 1 then value else null)

}
