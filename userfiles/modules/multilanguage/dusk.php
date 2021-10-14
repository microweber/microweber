<script>
    mw.require('editor.js');
    mw.lib.require('multilanguage');

    $(document).ready(function () {
        $('#js-field-4').mlInput({
            name: 'bojkata',
            currentLocale: 'ar_SA',
            locales: ['bg_BG', 'en_US', 'ar_SA'],
            translations: <?php echo json_encode([
                'bg_BG' => 'Текст на български',
                'ar_SA' => 'ARABSKI BRAT',
                'en_US' => 'Text on English'
            ]);
            ?>,
        });
        $('#js-field-5').mlTextArea({
            name: 'tonkata',
            currentLocale: 'ar_SA',
            locales: ['bg_BG', 'en_US', 'ar_SA'],
            translations: <?php echo json_encode([
                'bg_BG' => 'Текст на български',
                'ar_SA' => 'ARABSKI BRAT',
                'en_US' => 'Text on English'
            ]);
            ?>,
        });
        $('#js-field-6').mlTextArea({
            name: 'qvkatadlg',
            currentLocale: 'ar_SA',
            locales: ['bg_BG', 'en_US', 'ar_SA'],
            translations: <?php echo json_encode([
                'bg_BG' => 'Текст на български',
                'ar_SA' => 'ARABSKI BRAT',
                'en_US' => 'Text on English'
            ]);
            ?>,
            mwEditor: true
        });
    });
</script>
<input type="text" class="form-control" id="js-field-4"/>
<br/>
<textarea id="js-field-5" class="form-control"></textarea>
<br/>
<br/><textarea id="js-field-6" class="form-control"></textarea>
<br/>
<br/>
