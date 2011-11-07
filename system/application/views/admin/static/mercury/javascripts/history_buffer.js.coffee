class @Mercury.HistoryBuffer

  constructor: (@maxLength = 200) ->
    @index = 0
    @stack = []
    @markerRegExp = /<em class="mercury-marker"><\/em>/g


  push: (item) ->
    if jQuery.type(item) == 'string'
      return if @stack[@index] && @stack[@index].replace(@markerRegExp, '') == item.replace(@markerRegExp, '')
    else if jQuery.type(item) == 'object' && item.html
      return if @stack[@index] && @stack[@index].html == item.html

    @stack = @stack[0...@index + 1]
    @stack.push(item)
    @stack.shift() if @stack.length > @maxLength
    @index = @stack.length - 1


  undo: ->
    return null if @index < 1
    @index -= 1
    return @stack[@index]


  redo: ->
    return null if @index >= @stack.length - 1
    @index += 1
    return @stack[@index]
