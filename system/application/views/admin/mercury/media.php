
    
<form id="mercury_media" style="width:600px">
  <div class="mercury-modal-pane-container">
    <div class="mercury-modal-pane">
      <fieldset class="inputs">
        <legend><span>Images</span></legend>
        <ol>
          <li id="media_image_url_input" class="string input optional stringish">
            <label class="label" for="media_image_url">
              <input name="media_type" type="radio" value="image_url" checked="checked"/>
              URL</label>
            <input class="selectable url_finder" id="media_image_url" name="media[image_url]" type="text"/>
            <a style="float:right" class="button filebrowser" href="javascript:parent.openKCFinder('#media_image_url');" >File browser</a>
            
          </li>
        </ol>
      </fieldset>
      <fieldset class="inputs">
        <legend><span>Videos</span></legend>
        <ol>
          <li id="media_youtube_url_input" class="string input optional stringish">
            <label class="label" for="media_youtube_url">
              <input name="media_type" type="radio" value="youtube_url"/>
              YouTube Share URL</label>
            <input class="selectable" id="media_youtube_url" name="media[youtube_url]" type="text" placeholder="http://youtu.be/Pny4hoN8eII"/>
          </li>
          <li id="media_vimeo_url_input" class="string input optional stringish">
            <label class="label" for="media_vimeo_url">
              <input name="media_type" type="radio" value="vimeo_url"/>
              Vimeo URL</label>
            <input class="selectable" id="media_vimeo_url" name="media[vimeo_url]" type="text" placeholder="http://vimeo.com/25708134"/>
          </li>
        </ol>
      </fieldset>
      <fieldset class="inputs">
        <legend><span>Options</span></legend>
        <ol>
          <div id="image_url" class="media-options">
            <li id="media_image_alignment_input" class="select input optional">
              <label class="label" for="media_image_alignment">Alignment</label>
              <select id="media_image_alignment" name="media[image_alignment]">
                <option value="">None</option>
                <option value="left">Left</option>
                <option value="right">Right</option>
                <option value="top">Top</option>
                <option value="middle">Middle</option>
                <option value="bottom">Bottom</option>
                <option value="absmiddle">Absolute Middle</option>
                <option value="absbottom">Absolute Bottom</option>
              </select>
            </li>
            
            
             <li  class="string input optional stringish">
              <label class="label" for="media_image_height">Height</label>
              <input id="media_image_height" name="media[media_image_height]" type="text" />
            </li>
            
            <li  class="string input optional stringish">
              <label class="label" for="media_image_width">Width</label>
              <input id="media_image_width" name="media[media_image_width]" type="text" />
            </li>
            
            
          </div>
          <div id="youtube_url" class="media-options" style="display:none">
            <li id="media_youtube_width_input" class="string input optional stringish">
              <label class="label" for="media_youtube_width">Width</label>
              <input id="media_youtube_width" name="media[youtube_width]" type="text" value="560"/>
            </li>
            <li id="media_youtube_height_input" class="string input optional stringish">
              <label class="label" for="media_youtube_height">Height</label>
              <input id="media_youtube_height" name="media[youtube_height]" type="text" value="349"/>
            </li>
          </div>
          <div id="vimeo_url" class="media-options" style="display:none">
            <li id="media_vimeo_width_input" class="string input optional stringish">
              <label class="label" for="media_vimeo_width">Width</label>
              <input id="media_vimeo_width" name="media[vimeo_width]" type="text" value="400"/>
            </li>
            <li id="media_vimeo_height_input" class="string input optional stringish">
              <label class="label" for="media_vimeo_height">Height</label>
              <input id="media_vimeo_height" name="media[vimeo_height]" type="text" value="225"/>
            </li>
          </div>
        </ol>
      </fieldset>
    </div>
  </div>
  <div class="mercury-modal-controls">
    <fieldset class="buttons">
      <ol>
        <li class="commit button">
          <input class="submit" name="commit" type="submit" value="Insert Media"/>
        </li>
      </ol>
    </fieldset>
  </div>
</form>