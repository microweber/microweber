# ## Require all the dependencies
#= require mercury/dependencies/jquery-ui-1.8.13.custom
#= require mercury/dependencies/jquery.additions
#= require mercury/dependencies/jquery.htmlClean
#= require mercury/dependencies/liquidmetal
#= require mercury/dependencies/showdown
#
# ## Require all mercury files
#= require_self
#= require ./native_extensions
#= require ./page_editor
#= require ./history_buffer
#= require ./table_editor
#= require ./dialog
#= require ./palette
#= require ./select
#= require ./panel
#= require ./modal
#= require ./lightview
#= require ./statusbar
#= require ./toolbar
#= require ./toolbar.button
#= require ./toolbar.button_group
#= require ./toolbar.expander
#= require ./tooltip
#= require ./snippet
#= require ./snippet_toolbar
#= require ./region
#= require ./uploader
#= require_tree ./regions
#= require_tree ./dialogs
#= require_tree ./modals
#= require ./finalize
#
@Mercury ||= {}
jQuery.extend @Mercury, {
  version: '0.2.3'

  # No IE support yet because it doesn't follow the W3C standards for HTML5 contentEditable (aka designMode).
  supported: document.getElementById && document.designMode && !jQuery.browser.konqueror && !jQuery.browser.msie

  # Mercury object namespaces
  Regions: Mercury.Regions || {}
  modalHandlers: Mercury.modalHandlers || {}
  lightviewHandlers: Mercury.lightviewHandlers || {}
  dialogHandlers: Mercury.dialogHandlers || {}
  preloadedViews: Mercury.preloadedViews || {}

  # Custom ajax headers
  ajaxHeaders: ->
    headers = {}
    headers[Mercury.config.csrfHeader] = Mercury.csrfToken
    return headers


  # Custom event and logging methods
  bind: (eventName, callback) ->
    jQuery(top).bind("mercury:#{eventName}", callback)


  trigger: (eventName, options) ->
    Mercury.log(eventName, options)
    jQuery(top).trigger("mercury:#{eventName}", options)


  log: ->
    if Mercury.debug && console
      return if arguments[0] == 'hide:toolbar' || arguments[0] == 'show:toolbar'
      try console.debug(arguments) catch e

}