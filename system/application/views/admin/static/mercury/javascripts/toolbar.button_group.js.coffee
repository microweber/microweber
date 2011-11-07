class @Mercury.Toolbar.ButtonGroup

  constructor: (@name, @options = {}) ->
    @build()
    @bindEvents()
    @regions = @options._regions
    return @element


  build: ->
    @element = jQuery('<div>', {class: "mercury-button-group mercury-#{@name}-group"})
    if @options._context || @options._regions
      @element.addClass('disabled')


  bindEvents: ->
    Mercury.bind 'region:update', (event, options) =>
      context = Mercury.Toolbar.ButtonGroup.contexts[@name]
      if context
        if options.region && jQuery.type(options.region.currentElement) == 'function'
          element = options.region.currentElement()
          if element.length && context.call(@, element, options.region.element)
            @element.removeClass('disabled')
          else
            @element.addClass('disabled')

    Mercury.bind 'region:focused', (event, options) =>
      if @regions && options.region && options.region.type
        if @regions.indexOf(options.region.type) > -1
          @element.removeClass('disabled') unless @options._context
        else
          @element.addClass('disabled')

    Mercury.bind 'region:blurred', (event, options) =>
      @element.addClass('disabled') if @options.regions



# ButtonGroup contexts
@Mercury.Toolbar.ButtonGroup.contexts =

  table: (node, region) -> !!node.closest('table', region).length
