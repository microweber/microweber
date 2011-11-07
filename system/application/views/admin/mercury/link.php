<form id="mercury_link" style="width:600px">
  <div class="mercury-modal-pane-container">
    <div class="mercury-modal-pane">
      <fieldset class="inputs" id="link_text_container">
        <ol>
          <li id="link_text_input" class="string input optional stringish">
            <label class="label" for="link_text">Link Content</label>
            <input id="link_text" name="link[text]" type="text"/>
          </li>
        </ol>
      </fieldset>
      <fieldset class="inputs">
        <legend><span>Standard Links</span></legend>
        <ol>
          <li id="link_external_url_input" class="string input optional stringish">
            <label class="label" for="link_external_url">
              <input name="link_type" type="radio" value="external_url" checked="checked"/>
              URL</label>
            <input class="selectable" id="link_external_url" name="link[external_url]" type="text"/>
                        <a style="float:right" class="button filebrowser" href="javascript:parent.openKCFinder('#link_external_url');" >File browser</a>

          </li>
        </ol>
      </fieldset>
      <fieldset class="inputs">
        <legend><span>Index / Bookmark Links</span></legend>
        <ol>
          <li id="link_existing_bookmark_input" class="select input optional">
            <label class="label" for="link_existing_bookmark">
              <input name="link_type" type="radio" value="existing_bookmark"/>
              Existing Links</label>
            <select class="selectable" id="link_existing_bookmark" name="link[existing_bookmark]">
            </select>
          </li>
          <li id="link_new_bookmark_input" class="string input optional stringish">
            <label class="label" for="link_new_bookmark">
              <input name="link_type" type="radio" value="new_bookmark"/>
              Bookmark</label>
            <input class="selectable" id="link_new_bookmark" name="link[new_bookmark]" type="text"/>
          </li>
        </ol>
      </fieldset>
      <fieldset class="inputs">
        <legend><span>Options</span></legend>
        <ol>
          <li id="link_target_input" class="select input optional">
            <label class="label" for="link_target">Link Target</label>
            <select id="link_target" name="link[target]">
              <option value="">Self (the same window or tab)</option>
              <option value="_blank">Blank (a new window or tab)</option>
              <option value="_top">Top (removes any frames)</option>
              <option value="popup">Popup Window (javascript new window popup)</option>
            </select>
          </li>
          <div id="popup_options" class="link-target-options" style="display:none">
            <li id="link_popup_width_input" class="string input optional stringish">
              <label class="label" for="link_popup_width">Popup Width</label>
              <input id="link_popup_width" name="link[popup_width]" type="text"/>
            </li>
            <li id="link_popup_height_input" class="string input optional stringish">
              <label class="label" for="link_popup_height">Popup Height</label>
              <input id="link_popup_height" name="link[popup_height]" type="text"/>
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
          <input class="submit" name="commit" type="submit" value="Insert Link"/>
        </li>
      </ol>
    </fieldset>
  </div>
</form>
