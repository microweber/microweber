class Mercury.PageEditor extends Mercury.PageEditor

  save: ->
    url = @saveUrl ? Mercury.saveURL ? @iframeSrc()
    data = @serializeAsXml()
    console.log('saving', data)
    return
    method = 'PUT' if @options.saveMethod == 'PUT'
    jQuery.ajax url, {
      headers: Mercury.ajaxHeaders()
      type: method || 'POST'
      dataType: 'xml'
      data: data
      success: =>
        Mercury.changes = false
      error: =>
        alert("Mercury was unable to save to the url: #{url}")
    }

  serializeAsXml: ->
    data = @serialize()
    regionNodes = []
    for regionName, regionProperties of data
      snippetNodes = []
      for snippetName, snippetProperties of regionProperties['snippets']
        snippetNodes.push("<#{snippetName} name=\"#{snippetProperties['name']}\"><![CDATA[#{jQuery.toJSON(snippetProperties['options'])}]]></#{snippetName}>")
      regionNodes.push("<region name=\"#{regionName}\" type=\"#{regionProperties['type']}\"><value>\n<![CDATA[#{regionProperties['value']}]]>\n</value><snippets>#{snippetNodes.join('')}</snippets></region>")
    return "<regions>#{regionNodes.join('')}</regions>"