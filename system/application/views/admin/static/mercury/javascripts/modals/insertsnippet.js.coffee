@Mercury.modalHandlers.insertSnippet = ->
  @element.find('form').submit (event) =>
    event.preventDefault()
    serializedForm = @element.find('form').serializeObject()
    if Mercury.snippet
      snippet = Mercury.snippet
      snippet.setOptions(serializedForm)
      Mercury.snippet = null
    else
      snippet = Mercury.Snippet.create(@options.snippetName, serializedForm)
    Mercury.trigger('action', {action: 'insertSnippet', value: snippet})
    @hide()
