@Mercury.modalHandlers.insertCharacter = ->
  @element.find('.character').click ->
    Mercury.trigger('action', {action: 'insertHTML', value: "&#{jQuery(@).attr('data-entity')};"})
    Mercury.modal.hide()
