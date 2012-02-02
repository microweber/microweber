class @Mercury.Panel extends Mercury.Dialog

  constructor: (@url, @name, @options = {}) ->
    super


  build: ->
    @element = jQuery('<div>', {class: 'mercury-panel loading', style: 'display:none;'})
    @titleElement = jQuery("<h1>#{@options.title}</h1>").appendTo(@element)
    @paneElement = jQuery('<div>', {class: 'mercury-panel-pane'}).appendTo(@element)

    @element.appendTo(jQuery(@options.appendTo).get(0) ? 'body')


  bindEvents: ->
    Mercury.bind 'resize', => @position(@visible)
    Mercury.bind 'hide:panels', (event, panel) =>
      unless panel == @
        @button.removeClass('pressed')
        @hide()

    @element.mousedown (event) -> event.stopPropagation()

    @element.bind 'ajax:beforeSend', (event, xhr, options) =>
      options.success = (content) =>
        @loadContent(content)
        @resize()

    super


  show: ->
    Mercury.trigger('hide:panels', @)
    super


  resize: ->
    @paneElement.css({display: 'none'})
    preWidth = @element.width()

    @paneElement.css({visibility: 'hidden', width: 'auto', display: 'block'})
    postWidth = @element.width()

    @paneElement.css({visibility: 'visible', display: 'none'})
    position = @element.offset()
    @element.animate {left: position.left - (postWidth - preWidth), width: postWidth}, 200, 'easeInOutSine', =>
      @paneElement.css({display: 'block', width: postWidth})
      @makeDraggable()

    @hide() unless @visible


  position: (keepVisible) ->
    @element.css({display: 'block', visibility: 'hidden'})
    offset = @element.offset()
    elementWidth = @element.width()
    height = Mercury.displayRect.height - 16

    paneHeight = height - @titleElement.outerHeight()
    @paneElement.css({height: paneHeight, overflowY: if paneHeight < 30 then 'hidden' else 'auto'})

    left = Mercury.displayRect.width - elementWidth - 20 unless @moved
    left = 8 if left <= 8

    if @pinned || elementWidth + offset.left > Mercury.displayRect.width - 20
      left = Mercury.displayRect.width - elementWidth - 20

    @element.css {
      top: Mercury.displayRect.top + 8,
      left: left,
      height: height,
      display: if keepVisible then 'block' else 'none',
      visibility: 'visible'
    }

    @makeDraggable()
    @element.hide() unless keepVisible


  loadContent: (data) ->
    @loaded = true
    @element.removeClass('loading')
    @paneElement.css({visibility: 'hidden'})
    @paneElement.html(data)


  makeDraggable: ->
    elementWidth = @element.width()
    @element.draggable {
      handle: 'h1',
      axis: 'x',
      opacity: 0.70
      scroll: false,
      addClasses: false,
      iframeFix: true,
      containment: [8, 0, Mercury.displayRect.width - elementWidth - 20, 0]  #[x1, y1, x2, y2]
      stop: =>
        left = @element.offset().left
        @moved = true
        @pinned = if left > Mercury.displayRect.width - elementWidth - 30 then true else false
        return true
    }
