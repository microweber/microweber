@Mercury.dialogHandlers.snippetPanel = ->
  # make the filter work
  @element.find('input.filter').keyup =>
    value = @element.find('input.filter').val()
    for snippet in @element.find('li[data-filter]')
      if LiquidMetal.score(jQuery(snippet).data('filter'), value) == 0 then jQuery(snippet).hide() else jQuery(snippet).show()

  # when an element is dragged, set it so we have a global object
  @element.find('img[data-snippet]').bind 'dragstart', (event) ->
    Mercury.snippet = jQuery(@).data('snippet')
