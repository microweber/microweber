<div class="bs-component">

    <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1">
        @php
        foreach($supportedLanguages as $language) {
            $showTab= '';
            if ($currentLanguage == $language['locale']) {
                  $showTab = 'active';
            }
            $langData = \MicroweberPackages\Translation\LanguageHelper::getLangData($language['locale']);
            $flagIcon = "<i class='flag-icon flag-icon-".$language['icon']."'></i> " . strtoupper($langData['language']);
            echo '<a class="btn btn-outline-secondary btn-sm justify-content-center '.$showTab.'" data-bs-toggle="tab" href="#' . $randId . $language['locale'] . '">'.$flagIcon.'</a>';
        }
        @endphp
    </nav>

    <div id="js-multilanguage-tab-{{$randId}}" class="tab-content py-3">

        @php
        foreach($supportedLanguages as $language) {
            $showTab= '';
            if ($currentLanguage == $language['locale']) {
                $showTab = 'show active';
            }

            $textareaValue = '';

            if (isset($modelAttributes['multilanguage'])) {
                foreach ($modelAttributes['multilanguage'] as $locale => $multilanguageFields) {
                    if ($locale == $language['locale']) {
                        if (isset($multilanguageFields['option_value'])) {
                          $textareaValue = $multilanguageFields['option_value'];
                        }
                    }
                }
            }
        @endphp
          <div class="tab-pane fade {{$showTab}}" id="{{$randId . $language['locale']}}">
                <div class="form-group mb-4 p-4" style="background:#fff;">
                    <label class="form-label">
                        Attachments
                    </label>
                    <small class="text-muted d-block mb-2">
                        You can attach a file to this field.
                    </small>
                    <button type="button" id="mw_uploader" lang="{{$language['locale']}}" class="btn btn-sm btn-outline-primary">
                        Upload file <span id="upload_info"></span>
                    </button>
                </div>
            </div>
        @php
        }
        @endphp

        <script type="text/javascript">

            function getAppendFiles() {
                var append_files = mw.$('#append_files').val();
                if (append_files == '') {
                    var append_files_array = [];
                } else {
                    var append_files_array = append_files.split(',');
                }

                return append_files_array;
            }

            $(document).ready(function () {
                var uploader = mw.uploader({
                    filetypes: "images,videos",
                    multiple: false,
                    element: "#mw_uploader"
                });
                $(uploader).bind("FileUploaded", function (event, data) {

                    var append_file = '<div class="form-group"> <div class="input-group mb-3 append-transparent"> <input type="text" class="form-control form-control-sm" value="' + data.src + '"> <div class="input-group-append"> <span class="input-group-text py-0 px-2"><a href="javascript:;" class="text-danger mw-append-file-delete" file-url="' + data.src + '">X</a></span> </div> </div> </div>';

                    mw.$("#mw_uploader_loading").hide();
                    mw.$("#mw_uploader").show();
                    mw.$("#upload_info").html('');
                    mw.$("#upload_files").append(append_file);

                    var append_files_array = getAppendFiles();
                    append_files_array.push(data.src);

                    mw.$('#append_files').val(append_files_array.join(',')).trigger('change');

                });

                $(uploader).bind('progress', function (up, file) {
                    mw.$("#mw_uploader").hide();
                    mw.$("#mw_uploader_loading").show();
                    mw.$("#upload_info").html(file.percent + "%");
                });

                $(uploader).bind('error', function (up, file) {
                    mw.notification.error("The file is not uploaded.");
                });
            });

            // function applyMlFieldChanges(element) {
            //     var applyToElement = document.getElementById("js-multilanguage-textarea-' . $randId . '");
            //     applyToElement.value = element.value
            //     applyToElement.setAttribute("lang", element.getAttribute("lang"));
            //
            //     var changeEvent = new Event("change");
            //     applyToElement.dispatchEvent(changeEvent);
            // }
        </script>


        <textarea {!! $renderAttributes !!} style="display:none"></textarea>

    </div>
</div>
