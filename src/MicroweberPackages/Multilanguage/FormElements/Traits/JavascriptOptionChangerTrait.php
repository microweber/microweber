<?php

namespace MicroweberPackages\Multilanguage\FormElements\Traits;

trait JavascriptOptionChangerTrait
{
    public function getJavaScript()
    {
        $html = '<script>
                    function runMlField' . $this->randId . '() {
                        var selectLang = document.getElementById("js-multilanguage-select-lang-' . $this->randId . '");
                        selectLang.addEventListener("change", (event) => {
                          var inputText = document.getElementById("js-multilanguage-text-' . $this->randId . '");
                          var currentLangSelected = selectLang.value;
                          var currentTextLang =  document.querySelector(".js-multilanguage-value-lang-' . $this->randId . '[lang="+currentLangSelected+"]");

                          inputText.setAttribute("lang", currentLangSelected);
                          inputText.value = currentTextLang.value;

                        });

                        var inputText = document.getElementById("js-multilanguage-text-' . $this->randId . '");
                        inputText.addEventListener("change", (event) => {
                            var currentLangSelected = selectLang.value;
                            var currentTextLang =  document.querySelector(".js-multilanguage-value-lang-' . $this->randId . '[lang="+currentLangSelected+"]");

                            inputText.setAttribute("lang", currentLangSelected);
                            currentTextLang.value = inputText.value;
                        });
                    }
                    $(document).ready(function() {
                        runMlField' . $this->randId . '();
                    });
                </script>';

        return $html;
    }
}
