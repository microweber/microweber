<?php

namespace MicroweberPackages\Form\Elements;

class MwEditor extends TextArea
{
    public function render()
    {
        $mwEditorId = rand(1111,9999) . time();

        $html = "<script>
                    mw.require('editor.js');
                    $(mwd).ready(function () {
                        mweditor$mwEditorId = mw.Editor({
                        selector: document.getElementById('$mwEditorId'),
                        mode: 'div',
                        smallEditor: false,
                        minHeight: 250,
                        maxHeight: '70vh',
                        controls: [
                                [
                                    'undoRedo', '|', 'image', '|',
                                {
                                    group: {
                                    controller: 'bold',
                                        controls: ['italic', 'underline', 'strikeThrough']
                                    }
                                },
                                '|',
                                {
                                    group: {
                                    icon: 'mdi mdi-format-align-left',
                                        controls: ['align']
                                    }
                                },
                                '|', 'format',
                                {
                                    group: {
                                    icon: 'mdi mdi-format-list-bulleted-square',
                                        controls: ['ul', 'ol']
                                    }
                                },
                                '|', 'link', 'unlink', 'wordPaste', 'table', 'removeFormat'
                            ],
                        ]
                  });
                });
                </script>";

        $this->setAttribute('id', $mwEditorId);

        $html .= implode([
            sprintf('<textarea%s>', $this->renderAttributes()),
            $this->escape($this->value),
            '</textarea>',
        ]);

        return $html;
    }
}
