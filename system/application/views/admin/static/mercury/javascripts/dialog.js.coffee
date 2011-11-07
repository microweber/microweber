# # Mercury Dialog
#
# Dialog is a base class that's used by Palette, Select, Panel, and through Palette, the Toolbar.Expander.  It's basic
# function is to provide an interface element that can be opened, interacted with, and then closed.
#
class @Mercury.Dialog

  # The constructor expects a url to load, a name, and options.
  #
  # @url _string_ used to load the contents, either using Ajax or by pulling content from preloadedViews
  #
  # @name _string_ used in building of the element, and is assigned as part of the elements class (eg.
  #     `mercury-[name]-dialog`)
  #
  # @options _object_
  #
  # **Options**
  #
  # @for _Toolbar.Button_ used so the dialog can be shown/hidden based on button interactions.
  #
  # @preload _boolean_ if true, the view for this dialog will be loaded prior to being shown, otherwise the view will be
  #     loaded the first time it's shown, and cached for all future interactions.
  #
  # @appendTo _element_ you can append a dialog to any element by providing this option.
  constructor: (@url, @name, @options = {}) ->
    @button = @options.for

    @build()
    @bindEvents()
    @preload()


  # ## #build
  #
  # Builds the element and appends it to the DOM.  You can provide an element to append it to in the options, otherwise
  # it will be appended to the body.
  build: ->
    @element = jQuery('<div>', {class: "mercury-dialog mercury-#{@name}-dialog loading", style: 'display:none'})
    @element.appendTo(jQuery(@options.appendTo).get(0) ? 'body')


  # ## #bindEvents
  #
  # Bind to all the events we should handle.  In this case we only stop the mousedown event, since dialogs aren't
  # expected to have inputs etc.
  #
  # **Note:** By stopping the mousedown event we're limiting what's possible in dialogs, but this is needed to keep
  # focus from being taken away from the active region when different things are clicked on in dialogs.
  bindEvents: ->
    @element.mousedown (event) -> event.stopPropagation()


  # ## #preload
  #
  # If the options dictate that the content should be preloaded we load the view before we do anything else.  This is
  # useful if you don't want to make the user wait the half second or however long the server may take to respond when
  # they open various dialogs.
  preload: ->
    @load() if @options.preload


  # ## #toggle
  #
  # Toggle the dialog based on current visibility.
  toggle: ->
    if @visible then @hide() else @show()


  # ## #resize
  #
  # In the base class resize just calls through to show, but in the implementation classes resize typically adjusts the
  # size based on it's contents.
  resize: ->
    @show()


  # ## #show
  #
  # When showing a dialog it's expected that all other dialogs will close, and we fire an event for this.  The dialog is
  # then positioned and shown.  The show animation of the dialog is typically dictated by the implementation class.
  show: ->
    # Tell all other dialogs to close.
    Mercury.trigger('hide:dialogs', @)
    @visible = true
    if @loaded
      @element.css({width: 'auto', height: 'auto'})
      @position(@visible)
    else
      @position()
    # Then show the element.
    @appear()


  # ## #position
  #
  # Interface method.  Implemenations are expected to position the dialog themselves.
  #
  # @keepVisible _boolean_ specifies if the element should stay visible if it's already visible.
  position: (keepVisible) ->


  # ## #appear
  #
  # Animate the element into view.  After it's done showing, we load and resize it if it's not already loaded, so in
  # cases where content is not preloaded we can display the dialog, fill it's contents, and then animate the resize.
  appear: ->
    @element.css({display: 'block', opacity: 0})
    @element.animate {opacity: 0.95}, 200, 'easeInOutSine', =>
      @load(=> @resize()) unless @loaded


  # ## #hide
  #
  # Hides the element and keeps track of it's visibility.
  hide: ->
    @element.hide()
    @visible = false


  # ## #load
  #
  # Fetches the content that will be loaded into the dialog.
  #
  # @callback _function_ will be called after the content is loaded.
  load: (callback) ->
    return unless @url
    if Mercury.preloadedViews[@url]
      # If there's a preloadedView defined for the url being requested, load that one.
      @loadContent(Mercury.preloadedViews[@url])
      # And call the dialog handler if there's one.  We've broken the handlers out into seperate files so they can be
      # tested more easily, but you can define your own by putting them in dialogHanders.
      Mercury.dialogHandlers[@name].call(@) if Mercury.dialogHandlers[@name]
      callback() if callback
    else
      # Otherwise make an Ajax request to get the content.
      jQuery.ajax @url, {
        success: (data) =>
          @loadContent(data)
          Mercury.dialogHandlers[@name].call(@) if Mercury.dialogHandlers[@name]
          callback() if callback
        error: =>
          # If the Ajax fails, we hide the dialog and alert the user about the error.
          @hide()
          @button.removeClass('pressed') if @button
          alert("Mercury was unable to load #{@url} for the #{@name} dialog.")
      }


  # ## #loadContent
  #
  # Loads content into the element, and removes the loading class.
  #
  # @data _mixed_ a string or jQuery object that can be inserted into the dialog.
  loadContent: (data) ->
    @loaded = true
    @element.removeClass('loading')
    @element.html(data)
