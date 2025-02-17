<script src="{{ modules_url() }}editor/html_editor/html_editor.js"></script>
<script>
    function reset() {
        var also = $('#also_reset_modules').is(':checked');
        var txt = "@lang('Are you sure you want to reset the content?')";
        if (also) {
            txt = "@lang('Are you sure you want to reset the content and modules?')";
        }
        mw.confirm(txt, function () {
            mw.html_editor.reset_content(also);
        });
    }

    $(document).ready(function () {
        var fields = mw.html_editor.get_edit_fields(true);
        @isset($params['root_element_id'])
            fields = mw.html_editor.get_edit_fields(true, '#{{ $params['root_element_id'] }}');
        @endisset

        mw.html_editor.build_dropdown(fields, false);
        mw.html_editor.populate_editor();
    });

    mw.require('{{ modules_url() }}editor/selector.css');
</script>

<div class="mw-reest-content-wrapper position-relative">

    <div class="d-flex align-items-center gap-4 mb-4">
        <div>
            <span class="mdi mdi-alert mw-color-important" style="font-size:45px"></span>
        </div>


        <div class="d-flex align-items-center gap-4 mb-4">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" height="45px" viewBox="0 -960 960 960" width="45px" fill="#F12B1C"><path d="m40-120 440-760 440 760H40Zm138-80h604L480-720 178-200Zm302-40q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Zm40-100Z"/></svg>
            </div>

            <div>
                <h3 class="mb-1">@lang('Warning')</h3>
                <p class="mb-0">@lang('This will reset the content of the selected element!')</p>
            </div>
        </div>
    </div>

  <div class="card p-3">
      <div class="card-header p-3 shadow">
          <label class="mw-ui-check">
              <input type="checkbox" id="also_reset_modules" name="also_reset_modules" value="1" checked>
              <span></span>
              <span>@lang('Also reset modules inside the selected edit field')</span>
          </label>
      </div>

      <div class="card-body p-3 shadow">
          <div id="select_edit_field_container">
              <div id="select_edit_field_wrap"></div>
          </div>
      </div>

      <div id="save-toolbar">
          <button onclick="reset();" class="btn btn-danger">@lang('Reset content')</button>
      </div>
  </div>


</div>
