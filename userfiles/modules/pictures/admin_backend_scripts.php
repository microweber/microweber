
<style>
    #admin-thumb-item-dialog .mw-dialog-container{
        padding-bottom:0;
    }
    #image-json-options-dialog-footer{
        padding: 20px 0;
        background: var(--tblr-bg-surface);
        position: sticky;
        bottom:0;
    }
</style>

<script>

    var saveOptions = function (id, data) {

        mw.module_pictures.save_options(id, data, function () {
            mw.reload_module('#<?php print $params['id'] ?>');
            mw.reload_module('pictures/admin')
            mw.top().reload_module('pictures')
        });
    }

    imageConfigDialog = function (id) {
        var el = mw.$('#admin-thumb-item-' + id + ' .image-options');
        var dialog =  mw.top().dialog({
            overlay: true,
            content: el.html(),
            template: 'default',
            height: 'auto',
            id: 'admin-thumb-item-dialog',
            title: '<?php _e('Image Settings'); ?>',
            onResult: function (id) {
                var data = {};
                var root = $(dialog.dialogContainer);
                root.find('input').each(function () {
                    data[this.name] = this.value;
                })
                root.find('select').each(function () {
                    data[this.name] = this.value;
                })
                saveOptions(id, data);
                this.remove()
            }
        })

    }


    deleteSelected = function () {
        mw.module_pictures.del(doselect());
    }
    downloadSelected = function () {
        mw.$(".admin-thumb-item .mw-ui-check input:checked").each(function () {
            var a = $("<a>")
                .attr("href", $(this).dataset('url'))
                .attr("download", $(this).dataset('url'))
                .appendTo("body");

            a[0].click();
            a.remove();
        });

    }
    doselect = function () {
        var final = [];
        var all = mw.$(".admin-thumb-item:visible")

        all.each(function () {
            var check = $('.mw-ui-check input:checked', this);
            if (check.length) {
                final.push(check[0].value);
                $(this).addClass('checked')
            } else {
                $(this).removeClass('checked');

            }
        });

        var allPicker = mw.$('.post-media-select-all-pictures');

        allPicker[final.length !== 0 ? 'addClass' : 'removeClass']('active').find('span').html(final.length);
        allPicker[final.length === all.length ? 'addClass' : 'removeClass']('all-selected');
        mw.$('#post-media-card-header')[final.length === 0 ? 'removeClass' : 'addClass']('active');
        $(".select_actions")[final.length === 0 ? 'removeClass' : 'addClass']('active');
        $(".select_actions_holder").stop()[final.length === 0 ? 'hide' : 'show']();
        return final;
    }
    editImageTags = function (event) {
        var parent = null;
        mw.tools.foreachParents(event.target, function (loop) {

            if (mw.tools.hasClass(this, 'admin-thumb-item')) {
                parent = this;
                mw.tools.stopLoop(loop);
            }

        });
        if (parent !== null) {
            $(".image-tags", parent).show()
        }

    }



    selectItems = function (val) {
        if (val === 'all') {
            mw.$(".admin-thumb-item .mw-ui-check input").each(function () {
                this.checked = true;
            })
        }
        else if (val === 'none') {
            mw.$(".admin-thumb-item .mw-ui-check input").each(function () {
                this.checked = false;
            })
        }
        doselect()
    }


    $(document).ready(function () {

        var $root = mw.$('#admin-thumbs-holder-sort-<?php print $rand; ?>');

        var thumbs = mw.$('.admin-thumb-item', $root);

        if (thumbs.length) {
            $('#post-file-picker').hide()
            $('#post-file-picker-small').show()
        } else {
            $('#post-file-picker').show()
            $('#post-file-picker-small').hide()
        }


        $(".image-tag-view").remove();
        $(".image-tags").each(function () {
            $(".mw-post-media-img", mw.tools.firstParentWithClass(this, 'admin-thumb-item'))
                .append('<span class="image-tag-view tip" onclick="editImageTags(event)" data-tip="Tags: ' + this.value + '" ><span class="mw-icon-app-pricetag"></span></span>');
            $(this).on('change', function () {
                $(".image-tag-view", mw.tools.firstParentWithClass(this, 'admin-thumb-item')).attr('data-tip', 'Tags: ' + this.value);
            });

        });

        doselect()
    });
</script>
