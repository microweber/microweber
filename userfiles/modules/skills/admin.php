<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $file = get_option('file', $params['id']);
        if (!$file) {
            $file = '[{"skill":"", "percent": "78", "style":"primary"}]';
        }
        $skills = json_decode($file, true);
        if (sizeof($skills) == 0) {
            $skills = array();
        }
        ?>

        <style>
            .skillfield {
                padding: 40px 10px 10px 10px;
                margin: 10px auto;
                border: 1px solid #eee;
                border-radius: 3px;
                position: relative;
                display: block;
                overflow: hidden;
            }

            .skillfield .mw-icon-drag {
                font-size: 20px;
                display: inline-block;
                padding: 2px 7px;
                cursor: pointer;
                position: absolute;
                top: 10px;
                left: 10px;
            }

            .skillfield .mw-icon-bin {
                font-size: 20px;
                display: inline-block;
                padding: 2px 7px;
                cursor: pointer;
                position: absolute;
                top: 10px;
                right: 10px;
                color: #f12b1c;
            }

            .skillfield .mw-icon-drag {
                cursor: -moz-grab;
                cursor: -webkit-grab;
                cursor: grab;
            }

            .skillfield .form-group {
                clear: none;
            }

            .skillfield {
                padding-bottom: 20px;
                background: white;
            }

            .skill {
                width: 70%;
                clear: both;
            }

            .skillfield.ui-sortable-helper {
                box-shadow: rgba(0, 0, 0, 0.2) 0px 7px 15px
            }

            .skillfield select,
            .skillfield input {
                width: 100%
            }

        </style>

        <script>
            addNewskill = function () {
                var after = ''
                    + '<div class="skillfield">'
                    + '<span class="mw-icon-drag"></span>'
                    + '<span class="mw-icon-bin"></span>'

                    + '<div class="form-group mufh-1"><label class="control-label">Skill Name</label><input type="text" autocomplete="off"  placeholder="Insert skill Name" class="form-control skill" /></div>'

                    + '<div class="row">'
                    + '<div class="col-6">'
                    + '<div class="form-group mufh-2"><label class="control-label">Value(%)</label><input type="number" autocomplete="off" min="0" max="100" step="1" value="50" placeholder="Percent" class="form-control percent-field" /></div>'
                    + '</div>'
                    + '<div class="col-6">'
                    + '<div class="form-group mufh-3">'
                    + '<label class="control-label">Style</label><select class="form-control selectpicker-x" data-size="2" data-width="100%" data-value="primary">'
                    + '<option value="">Choose Style</option>'
                    + '<option value="primary">Primary</option>'
                    + '<option value="warning">Warning</option>'
                    + '<option value="danger">Danger</option>'
                    + '<option value="success">Success</option>'
                    + '<option value="info">Info</option>'
                    + '</select></div>'
                    + '</div>'
                    + '</div>'

                    + '</div>';

                $(".skillfield:last").after(after);
                $(".skill:last").focus();
                init();
            }

            init = function () {
                $(".skill").each(function () {
                    if (!this.__activated) {
                        this.__activated = true;
                        var root = $(this).parents('.skillfield')
                        $('input', root).on('change input paste', function () {
                            save();
                        });

                        $('.mw-icon-bin', root).on('click', function () {
                            mw.confirm('Are you sure', function () {
                                root.fadeOut(function () {
                                    root.remove();
                                    save();
                                })
                            })
                        });

                        mw.$('.percent-field', root).on('change', function () {
                            save()
                        })

                        mw.$('select[data-value]', root).each(function () {
                            $(this).val($(this).dataset('value')).on('change', function () {
                                save();
                            })
                        })
                    }
                });

                if ($('.skillfield').length === 1) {
                    $('.skillfield .mw-icon-bin:first').hide()
                } else {
                    $('.skillfield .mw-icon-bin:first').show()
                }
            }

            time = null;

            save = function () {
                clearTimeout(time);
                time = setTimeout(function () {
                    var final = [];
                    $(".skill").each(function () {
                        var style = $(this).parents('.skillfield').find('select').val();
                        if (!style) style = 'primary'
                        final.push({skill: this.value, percent: $(this).parents('.skillfield').find('.percent-field').val(), style: style});
                    });
                    $("#file").val(JSON.stringify(final)).trigger('change');
                }, 700);

                if ($('.skillfield').length === 1) {
                    $('.skillfield .mw-icon-bin:first').hide()

                } else {
                    $('.skillfield .mw-icon-bin:first').show()
                }
            }

            sort = function () {
                $(".skills").sortable({
                    axis: 'y',
                    stop: function () {
                        save()
                    },
                    handle: '.mw-icon-drag'
                })
            }

            $(window).bind('load', function () {
                sort();
                init();
            });
        </script>

        <div class="module-live-edit-settings module-skills-settings">
            <div class="form-group mb-0">
                <label class="control-label mb-0">Set your skills</label>
            </div>

            <div class="skills">
                <?php foreach ($skills as $skill): ?>
                    <div class="skillfield">
                        <span class="mw-icon-drag"></span>
                        <span class="mw-icon-bin"></span>

                        <div class="form-group mufh-1">
                            <label class="control-label">Skill Name</label>
                            <input type="text" autocomplete="off" value="<?php print $skill['skill']; ?>" placeholder="Insert skill Name" class="form-control skill"/>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mufh-2">
                                    <label class="control-label">Value(%)</label>
                                    <input type="number" min="0" max="100" step="1" autocomplete="off" value="<?php print isset($skill['percent']) ? $skill['percent'] : 50; ?>" placeholder="Percent" class="form-control percent-field"/>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mufh-3">
                                    <label class="control-label">Style</label>
                                    <select class="form-control selectpicker-x" data-size="2" data-width="100%" data-value="<?php print isset($skill['style']) ? $skill['style'] : 'primary' ?>">
                                        <option value="">Choose Style</option>
                                        <option value="primary">Primary</option>
                                        <option value="warning">Warning</option>
                                        <option value="danger">Danger</option>
                                        <option value="success">Success</option>
                                        <option value="info">Info</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-right">
                <span class="btn btn-success" onclick="addNewskill();"><i class="mdi mdi-plus mr-1"></i> Add New</span>
            </div>

            <textarea name="file" id="file" disabled="disabled" class="mw_option_field" style="display: none"><?php print $file; ?></textarea>
        </div>

    </div>
</div>

