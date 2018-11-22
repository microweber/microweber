<?php only_admin_access(); ?>

<script>
    function delete_testimonial(id) {
        var are_you_sure = confirm('<?php _e('Are you sure?'); ?>');
        if (are_you_sure == true) {
            var data = {}
            data.id = id;
            var url = "<?php print api_url('delete_testimonial'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module("testimonials");
                mw.reload_module("testimonials/list");

            });
        }
    }

    add_testimonial = function () {
        $('.js-add-new-button').hide();
        $("#edit-testimonials").attr("edit-id", "0");
        mw.reload_module("#edit-testimonials");
    }

    add_new_testimonial = function () {
        $('.mw-ui-btn-nav-tabs .mw-ui-btn:nth-child(2)').trigger('click');
    }

    edit_testimonial = function (id) {
        $('.js-add-new-button').show();
        $("#edit-testimonials").attr("edit-id", id);
        mw.reload_module("#edit-testimonials");
        $('.js-add-new-testimonials .mw-accordion-title').trigger('click');
    }

    $(document).ready(function () {
        mw.$("#testimonials-list tbody").sortable({
            change: function () {

            },
            axis: 'y',
            start: function () {
                mw.$("#testimonials-list").addClass('dragging')
            },
            stop: function () {
                mw.$("#testimonials-list").removeClass('dragging');

                var data = {
                    ids: []
                }
                mw.$("#testimonials-list tbody tr").each(function () {
                    data.ids.push($(this).dataset('id'));
                });

                $.post("<?php print api_url(); ?>reorder_testimonials", data, function () {
                    parent.mw.reload_module("testimonials");
                });

            }
        });

        mw.$("#AddNew").click(function () {
            mw.$("#add-testimonial-form").show();
            mw.$(this).hide();
        });
    });
</script>

<style>
    .testimonial-client-image {
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
        border-radius: 100%;
        width: 75px;
        height: 75px;
        -webkit-background-size: cover;
        background-size: cover;
        margin: 0 auto 10px auto;
    }
</style>

<?php $data = get_testimonials(); ?>
<?php if ($data): ?>
    <div class="table-responsive">
        <table width="100%" class="mw-ui-table mw-ui-table-basic" id="testimonials-list" style="table-layout: auto">
            <thead>
            <tr>
                <th><?php _e('Image'); ?></th>
                <th><?php _e('Name'); ?>/<?php _e('Content'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $item): ?>
                <tr data-id="<?php print $item['id'] ?>">
                    <td class="text-center" style="width: 120px;">
                        <div style="background-image: url('<?php print thumbnail($item['client_picture'], 75, 75) ?>');" class="testimonial-client-image"></div>

                        <a class="tip mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small" data-tip="Edit Item" data-tipposition="top-center" href="javascript:;" onclick="edit_testimonial('<?php print $item['id'] ?>');">Edit</a>
                        &nbsp;
                        <a style="color: rgba(204, 0, 0, 1)" class="mw-ui-btn mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-small tip " data-tip="Delete Item" data-tipposition="top-center" href="javascript:delete_testimonial('<?php print $item['id'] ?>');"><i class="mw-icon-bin"></i></a>
                    </td>
                    <td>
                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label"><?php print $item['name'] ?> </label>
                        </div>
                        <p><?php print character_limiter($item['content'], 100); ?></p>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <h2 class="text-center"><?php _e('You have no testimonials'); ?></h2>
    <div class="text-center"><a href="javascript:;" onclick="add_new_testimonial()" class="mw-ui-btn"><?php _e('Create new'); ?></a></div>
<?php endif; ?>
