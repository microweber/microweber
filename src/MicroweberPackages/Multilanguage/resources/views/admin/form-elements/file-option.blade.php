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


        @foreach($supportedLanguages as $language)
        @php
            $showTab= '';
            if ($currentLanguage == $language['locale']) {
                $showTab = 'show active';
            }
        @endphp
              <div class="tab-pane fade {{$showTab}}" id="{{$randId . $language['locale']}}">

                  <div style="background:#fff;padding:15px;">

                      <module type="admin/components/file_append"
                              title="File attachments"
                              lang="{{$language['locale']}}"
                              option_group="{{$optionGroup}}"
                              option_key="{{$optionKey}}" />
                  </div>

            </div>
        @endforeach

    </div>
</div>
