@Mercury.dialogHandlers.formatblock = ->
  @element.find('[data-tag]').click (event) =>
    tag = jQuery(event.target).data('tag')
    Mercury.trigger('action', {action: 'formatblock', value: tag})
