@Mercury.modalHandlers.insertLink = ->
  # make the inputs work with the radio buttons
  @element.find('label input').click (event) ->
    jQuery(@).closest('label').next('.selectable').focus()

  @element.find('.selectable').focus ->
    jQuery(@).prev('label').find('input[type=radio]').prop("checked", true)

  # show/hide the link target options on target change
  @element.find('#link_target').change =>
    @element.find(".link-target-options").hide()
    @element.find("##{@element.find('#link_target').val()}_options").show()
    @resize(true)

  # fill the existing bookmark select
  bookmarkSelect = @element.find('#link_existing_bookmark')
  for link in jQuery('a[name]', window.mercuryInstance.document)
    bookmarkSelect.append(jQuery('<option>', {value: jQuery(link).attr('name')}).text(jQuery(link).text()))

  # get the selection and initialize its information into the form
  if Mercury.region && Mercury.region.selection
    selection = Mercury.region.selection()

    # if we're editing a link prefill the information
    container = selection.commonAncestor(true).closest('a') if selection && selection.commonAncestor
    if container && container.length
      existingLink = container

      # don't allow changing the content on edit
      @element.find('#link_text_container').hide()

      # fill in the external url or bookmark select based on what it looks like
      if container.attr('href') && container.attr('href').indexOf('#') == 0
        bookmarkSelect.val(container.attr('href').replace(/[^#]*#/, ''))
        bookmarkSelect.prev('label').find('input[type=radio]').prop("checked", true)
      else
        @element.find('#link_external_url').val(container.attr('href'))

      # if it has a name, assume it's a bookmark target
      if container.attr('name')
        newBookmarkInput = @element.find('#link_new_bookmark')
        newBookmarkInput.val(container.attr('name'))
        newBookmarkInput.prev('label').find('input[type=radio]').prop("checked", true)

      # if it has a target, select it, and try to pull options out
      if container.attr('target')
        @element.find('#link_target').val(container.attr('target'))

      # if it's a popup window
      if container.attr('href') && container.attr('href').indexOf('javascript:void') == 0
        href = container.attr('href')
        @element.find('#link_external_url').val(href.match(/window.open\('([^']+)',/)[1])
        @element.find('#link_target').val('popup')
        @element.find('#link_popup_width').val(href.match(/width=(\d+),/)[1])
        @element.find('#link_popup_height').val(href.match(/height=(\d+),/)[1])
        @element.find('#popup_options').show()

    # get the text content
    @element.find('#link_text').val(selection.textContent()) if selection.textContent

  # build the link on form submission
  @element.find('form').submit (event) =>
    event.preventDefault()

    content = @element.find('#link_text').val()
    target = @element.find('#link_target').val()
    type = @element.find('input[name=link_type]:checked').val()

    switch type
      when 'existing_bookmark' then attrs = {href: "##{@element.find('#link_existing_bookmark').val()}"}
      when 'new_bookmark' then attrs = {name: "#{@element.find('#link_new_bookmark').val()}"}
      else attrs = {href: @element.find("#link_#{type}").val()}

    switch target
      when 'popup'
        args = {
          width: parseInt(@element.find('#link_popup_width').val()) || 500,
          height: parseInt(@element.find('#link_popup_height').val()) || 500,
          menubar: 'no',
          toolbar: 'no'
        }
        attrs['href'] = "javascript:void(window.open('#{attrs['href']}', 'popup_window', '#{jQuery.param(args).replace(/&/g, ',')}'))"
      else attrs['target'] = target if target

    value = {tagName: 'a', attrs: attrs, content: content}

    if existingLink
      Mercury.trigger('action', {action: 'replaceLink', value: value, node: existingLink.get(0)})
    else
      Mercury.trigger('action', {action: 'insertLink', value: value})

    @hide()
