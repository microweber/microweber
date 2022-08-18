<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>
<script>mw.require('editor.js')</script>
<script>
    MWEditor.controllers.contactFormInsertEmailVariable = function (scope, api, rootScope, data) {
        this.checkSelection = function (opt) {
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [

                    { label: 'email', value: '{email}' },

                ],
                placeholder: rootScope.lang('Insert variable')
            });
            dropdown.select.on('change', function (e, val) {
                api.insertHTML(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    };

    $(document).ready(function () {
        mweditor = mw.Editor({
            selector: '#editorAM',
            mode: 'div',
            smallEditor: false,
            minHeight: 250,
            maxHeight: '70vh',
            controls: [
                [
                    'undoRedo', '|', 'image', '|',
                    {
                        group: {
                            icon: 'mdi mdi-format-bold',
                            controls: ['bold', 'italic', 'underline', 'strikeThrough']
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
                    '|', 'link', 'unlink', 'wordPaste', 'table', 'contactFormInsertEmailVariable'
                ],
            ]
        });
    });
</script>


<?php
$checkEmailFromGlobal = \MicroweberPackages\Option\Facades\Option::getValue('email_from', 'email');
if (empty($checkEmailFromGlobal)):
    ?>
    <div class="alert alert-dismissible alert-danger">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <i class="mdi mdi-email"></i> <?php _e('You don\'t have system e-mail and smtp setup.');?>
        <a href="<?php echo admin_url('view:settings#option_group=email'); ?>" target="_blank"><?php _e('Setup your system settings here.');?></a>
    </div>
<?php
endif;
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>
