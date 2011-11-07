@Mercury.uploader = (file, options) ->
  Mercury.uploader.show(file, options) if Mercury.config.uploading.enabled
  return Mercury.uploader

jQuery.extend Mercury.uploader, {

  show: (file, @options = {}) ->
    @file = new Mercury.uploader.File(file)
    if @file.errors
      alert("Error: #{@file.errors}")
      return
    return unless @supported()

    Mercury.trigger('focus:window')
    @initialize()
    @appear()


  initialize: ->
    return if @initialized
    @build()
    @bindEvents()
    @initialized = true


  supported: ->
    xhr = new XMLHttpRequest
    fileReader = window.FileReader

    if window.Uint8Array && window.ArrayBuffer && !XMLHttpRequest.prototype.sendAsBinary
      XMLHttpRequest::sendAsBinary = (datastr) ->
        ui8a = new Uint8Array(datastr.length)
        ui8a[index] = (datastr.charCodeAt(index) & 0xff) for data, index in datastr
        @send(ui8a.buffer)

    return !!(xhr.upload && xhr.sendAsBinary && fileReader)


  build: ->
    @element = jQuery('<div>', {class: 'mercury-uploader', style: 'display:none'})
    @element.append('<div class="mercury-uploader-preview"><b><img/></b></div>')
    @element.append('<div class="mercury-uploader-details"></div>')
    @element.append('<div class="mercury-uploader-progress"><span>Processing...</span><div class="mercury-uploader-indicator"><div><b>0%</b></div></div></div>')

    @overlay = jQuery('<div>', {class: 'mercury-uploader-overlay', style: 'display:none'})

    @element.appendTo(jQuery(@options.appendTo).get(0) ? 'body')
    @overlay.appendTo(jQuery(@options.appendTo).get(0) ? 'body')


  bindEvents: ->
    Mercury.bind 'resize', => @position()


  appear: ->
    @fillDisplay()
    @position()

    @overlay.show()
    @overlay.animate {opacity: 1}, 200, 'easeInOutSine', =>
      @element.show()
      @element.animate {opacity: 1}, 200, 'easeInOutSine', =>
        @visible = true
        @loadImage()


  position: ->
    width = @element.outerWidth()
    height = @element.outerHeight()

    @element.css {
      top: (Mercury.displayRect.height - height) / 2
      left: (Mercury.displayRect.width - width) / 2
    }


  fillDisplay: ->
    details = ["Name: #{@file.name}", "Size: #{@file.readableSize}", "Type: #{@file.type}"]
    @element.find('.mercury-uploader-details').html(details.join('<br/>'))


  loadImage: ->
    @file.readAsDataURL (result) =>
      @element.find('.mercury-uploader-preview b').html(jQuery('<img>', {src: result}))
      @upload()


  upload: ->
    xhr = new XMLHttpRequest
    jQuery.each ['onloadstart', 'onprogress', 'onload', 'onabort', 'onerror'], (index, eventName) =>
      xhr.upload[eventName] = (event) => @uploaderEvents[eventName].call(@, event)
    xhr.onload = (event) =>
      if (event.currentTarget.status >= 400)
        @updateStatus('Error: Unable to upload the file')
        alert("#{event.currentTarget.status}: Unable to process response")
        @hide()
      else
        try
          response =
            if Mercury.config.uploading.handler
              Mercury.config.uploading.handler(event.target.responseText)
            else
              jQuery.parseJSON(event.target.responseText)
          Mercury.trigger('action', {action: 'insertImage', value: {src: response.image.url}})
          @hide()
        catch error
          @updateStatus('Error: Unable to upload the file')
          alert("Unable to process response: #{error}")
          @hide()

    xhr.open('post', Mercury.config.uploading.url, true)
    xhr.setRequestHeader('Accept', 'application/json, text/javascript, text/html, application/xml, text/xml, */*')
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhr.setRequestHeader(Mercury.config.csrfHeader, Mercury.csrfToken)

    @file.readAsBinaryString (result) =>
      # build the multipart post string
      multipart = new Mercury.uploader.MultiPartPost(Mercury.config.uploading.inputName, @file, result)

      # update the content size so we can calculate
      @file.updateSize(multipart.delta)

      # set the content type and send
      xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + multipart.boundary)
      xhr.sendAsBinary(multipart.body)


  updateStatus: (message, loaded) ->
    @element.find('.mercury-uploader-progress span').html(message)
    if loaded
      percent = Math.floor(loaded * 100 / @file.size) + '%'
      @element.find('.mercury-uploader-indicator div').css({width: percent})
      @element.find('.mercury-uploader-indicator b').html(percent).show()


  hide: (delay = 0) ->
    setTimeout delay * 1000, =>
      @element.animate {opacity: 0}, 200, 'easeInOutSine', =>
        @overlay.animate {opacity: 0}, 200, 'easeInOutSine', =>
          @overlay.hide()
          @element.hide()
          @reset()
          @visible = false
          Mercury.trigger('focus:frame')


  reset: ->
    @element.find('.mercury-uploader-preview b').html('')
    @element.find('.mercury-uploader-indicator div').css({width: 0})
    @element.find('.mercury-uploader-indicator b').html('0%').hide()
    @updateStatus('Processing...')


  uploaderEvents:
    onloadstart: -> @updateStatus('Uploading...')

    onprogress: (event) -> @updateStatus('Uploading...', event.loaded)

    onabort: ->
      @updateStatus('Aborted')
      @hide(1)

    onload: ->
      @updateStatus('Successfully uploaded...', @file.size)

    onerror: ->
      @updateStatus('Error: Unable to upload the file')
      @hide(3)
}



class Mercury.uploader.File

  constructor: (@file) ->
    @size = @file.size
    @fullSize = @file.size
    @readableSize = @file.size.toBytes()
    @name = @file.fileName
    @type = @file.type

    # add any errors if we need to
    errors = []
    errors.push('Too large') if @size >= Mercury.config.uploading.maxFileSize
    errors.push('Unsupported format') unless Mercury.config.uploading.allowedMimeTypes.indexOf(@type) > -1
    @errors = errors.join(' / ') if errors.length


  readAsDataURL: (callback = null) ->
    reader = new FileReader()
    reader.readAsDataURL(@file)
    reader.onload = => callback(reader.result) if callback


  readAsBinaryString: (callback = null) ->
    reader = new FileReader()
    reader.readAsBinaryString(@file)
    reader.onload = => callback(reader.result) if callback


  updateSize: (delta) ->
    @fullSize = @size + delta



class Mercury.uploader.MultiPartPost

  constructor: (@inputName, @file, @contents, @formInputs = {}) ->
    @boundary = 'Boundaryx20072377098235644401115438165x'
    @body = ''
    @buildBody()
    @delta = @body.length - @file.size


  buildBody: ->
    boundary = '--' + @boundary
    for own name, value of @formInputs
      @body += "#{boundary}\r\nContent-Disposition: form-data; name=\"#{name}\"\r\n\r\n#{unescape(encodeURIComponent(value))}\r\n"
    @body += "#{boundary}\r\nContent-Disposition: form-data; name=\"#{@inputName}\"; filename=\"#{@file.name}\"\r\nContent-Type: #{@file.type}\r\nContent-Transfer-Encoding: binary\r\n\r\n#{@contents}\r\n#{boundary}--"
