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
        $('a.saveButton').hide();
        $('a.addNewButton').show();
        $("#edit-testimonials").attr("edit-id", "0");
        mw.reload_module("#edit-testimonials");
        window.TTABS.set(1);
    }

    edit_testimonial = function (id) {
        $('a.saveButton').show();
        $('a.addNewButton').hide();
        $("#edit-testimonials").attr("edit-id", id);
        mw.reload_module("#edit-testimonials");
        window.TTABS.set(1);
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
<?php $data = get_testimonials(); ?>
<?php if ($data): ?>

    <div class="table-responsive">
        <table width="100%" class="mw-ui-table mw-ui-table-basic" id="testimonials-list">
            <thead>
            <tr>
                <th><?php _e('Image'); ?></th>
                <th><?php _e('Name'); ?></th>
                <th><?php _e('Content'); ?></th>
                <th style="text-align:center"><?php _e('Edit'); ?></th>
                <th style="text-align:center"><?php _e('Delete'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $item): ?>
                <tr data-id="<?php print $item['id'] ?>">
                    <td style="width:10%"><?php if ($item['client_picture'] == false): ?><img src="<?php print $item['client_picture'] ?>" alt="" class="testimonial-client-image" /><?php endif; ?>
                    </td>
                    <td style="width:20%"><?php print $item['name'] ?></td>
                    <td style="width:50%"><?php print $item['content'] ?></td>
                    <td style="text-align:center" style="width:10%">
                        <a class="mw-icon-pen tip show-on-hover" data-tip="Edit Item" data-tipposition="top-center"
                           href="javascript:;" onclick="edit_testimonial('<?php print $item['id'] ?>');"></a>
                    </td>
                    <td style="text-align:center" style="width:10%">
                        <a class="mw-icon-close tip show-on-hover" data-tip="Delete Item" data-tipposition="top-center"
                           href="javascript:delete_testimonial('<?php print $item['id'] ?>');"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>

    <h2 class="text-center"><?php _e('You have no testimonials'); ?></h2>
    <div class="text-center"><a href="javascript:;" onclick="window.TTABS.set(1)"
                                class="mw-ui-btn"><?php _e('Create new'); ?></a></div>

<?php endif; ?>
