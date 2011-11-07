@Mercury.lightview = (url, options = {}) ->
  Mercury.lightview.show(url, options)
  return Mercury.lightview

jQuery.extend Mercury.lightview, {

  minWidth: 400

  show: (@url, @options = {}) ->
    Mercury.trigger('focus:window')
    @initialize()
    if @visible then @update() else @appear()


  initialize: ->
    return if @initialized
    @build()
    @bindEvents()
    @initialized = true


  build: ->
    @element = jQuery('<div>', {class: 'mercury-lightview loading'})
    @element.html('<h1 class="mercury-lightview-title"><span></span></h1>')
    @element.append('<div class="mercury-lightview-content"></div>')

    @overlay = jQuery('<div>', {class: 'mercury-lightview-overlay'})

    @titleElement = @element.find('.mercury-lightview-title')
    @titleElement.append('<a class="mercury-lightview-close"></a>') if @options.closeButton

    @contentElement = @element.find('.mercury-lightview-content')

    @element.appendTo(jQuery(@options.appendTo).get(0) ? 'body')
    @overlay.appendTo(jQuery(@options.appendTo).get(0) ? 'body')

    @titleElement.find('span').html(@options.title)


  bindEvents: ->
    Mercury.bind 'refresh', => @resize(true)
    Mercury.bind 'resize', => @position() if @visible

    @overlay.click => @hide() unless @options.closeButton
    @titleElement.find('.mercury-lightview-close').click => @hide()

    jQuery(document).bind 'keydown', (event) =>
       @hide() if event.keyCode == 27 && @visible

    @element.bind 'ajax:beforeSend', (event, xhr, options) =>
      options.success = (content) =>
        @loadContent(content)


  appear: ->
    @position()

    @overlay.show().css({opacity: 0})
    @overlay.animate {opacity: 1}, 200, 'easeInOutSine', =>
      @setTitle()
      @element.show().css({opacity: 0})
      @element.stop().animate {opacity: 1}, 200, 'easeInOutSine', =>
        @visible = true
        @load()


  resize: (keepVisible) ->
    viewportWidth = Mercury.displayRect.width
    viewportHeight = Mercury.displayRect.fullHeight

    @element.css({overflow: 'hidden'})
    @contentElement.css({visibility: 'hidden', display: 'none', width: 'auto', height: 'auto'})

    width = @contentElement.outerWidth() + 40 + 2
    width = viewportWidth - 40 if width > viewportWidth - 40 || @options.fullSize
    height = @contentElement.outerHeight() + @titleElement.outerHeight() + 30
    height = viewportHeight - 20 if height > viewportHeight - 20 || @options.fullSize

    width = 300 if width < 300
    height = 150 if height < 150

    @element.stop().animate {top: ((viewportHeight - height) / 2) + 10, left: (Mercury.displayRect.width - width) / 2, width: width, height: height}, 200, 'easeInOutSine', =>
      @contentElement.css({visibility: 'visible', display: 'block', opacity: 0})
      @contentElement.stop().animate({opacity: 1}, 200, 'easeInOutSine')
      @element.css({overflow: 'auto'})


  position: ->
    viewportWidth = Mercury.displayRect.width
    viewportHeight = Mercury.displayRect.fullHeight

    @contentElement.css({position: 'absolute', width: 'auto', height: 'auto'})
    width = @contentElement.width() + 40 + 2
    width = viewportWidth - 40 if width > viewportWidth - 40 || @options.fullSize

    height = @contentElement.height() + @titleElement.outerHeight() + 30
    height = viewportHeight - 20 if height > viewportHeight - 20 || @options.fullSize
    @contentElement.css({position: 'relative'})

    width = 300 if width < 300
    height = 150 if height < 150

    @element.css({top: ((viewportHeight - height) / 2) + 10, left: (viewportWidth - width) / 2, width: width, height: height, overflow: 'auto'})
    @contentElement.css({width: width - 40, height: height - 30 - @titleElement.outerHeight()}) if @visible


  update:  ->
    @reset()
    @resize()
    @load()


  load: ->
    @setTitle()
    return unless @url
    @element.addClass('loading')
    if Mercury.preloadedViews[@url]
      setTimeout((=> @loadContent(Mercury.preloadedViews[@url])), 10)
    else
      jQuery.ajax @url, {
        headers: Mercury.ajaxHeaders()
        type: @options.loadType || 'GET'
        data: @options.loadData
        success: (data) => @loadContent(data)
        error: =>
          @hide()
          alert("Mercury was unable to load #{@url} for the lightview.")
      }


  loadContent: (data, options = null) ->
    @initialize()
    @options = options || @options
    @setTitle()
    @loaded = true
    @element.removeClass('loading')
    @contentElement.html(data)
    @contentElement.css({display: 'none', visibility: 'hidden'})

    @options.afterLoad.call(@) if @options.afterLoad
    if @options.handler && Mercury.lightviewHandlers[@options.handler]
      Mercury.lightviewHandlers[@options.handler].call(@)

    @resize()


  setTitle: ->
    @titleElement.find('span').html(@options.title)


  reset: ->
    @titleElement.find('span').html('')
    @contentElement.html('')


  hide: ->
    Mercury.trigger('focus:frame')
    @element.hide()
    @overlay.hide()
    @reset()

    @visible = false

}