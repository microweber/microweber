class @Mercury.Select extends Mercury.Dialog

  constructor: (@url, @name, @options = {}) ->
    super


  build: ->
    @element = jQuery('<div>', {class: "mercury-select mercury-#{@name}-select loading", style: 'display:none'})
    @element.appendTo(jQuery(@options.appendTo).get(0) ? 'body')


  bindEvents: ->
    Mercury.bind 'hide:dialogs', (event, dialog) => @hide() unless dialog == @

    @element.mousedown (event) => event.preventDefault()

    super


  position: (keepVisible) ->
    @element.css({top: 0, left: 0, display: 'block', visibility: 'hidden'})
    position = @button.offset()
    elementWidth = @element.width()
    elementHeight = @element.height()
    documentHeight = jQuery(document).height()

    top = position.top + (@button.height() / 2) - (elementHeight / 2)
    top = position.top - 100 if top < position.top - 100
    top = 20 if top < 20

    height = if @loaded then 'auto' else elementHeight
    height = documentHeight - top - 20 if top + elementHeight >= documentHeight - 20

    left = position.left
    left = left - elementWidth + @button.width() if left + elementWidth > jQuery(window).width()

    @element.css {
      top: top
      left: left
      height: height
      display: if keepVisible then 'block' else 'none'
      visibility: 'visible'
    }
