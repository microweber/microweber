<?php must_have_access(); ?>

<?php
if (!isset($params['project_name'])) {
    $params['project_name'] = '';
}
?>

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
            mw.reload_module_parent("testimonials");
        }
    }

    add_testimonial = function () {
        $('.js-add-new-button').hide();
        $("#edit-testimonials").attr("edit-id", "0");
        $("#edit-testimonials").attr("project_name", '<?php echo $params['project_name']; ?>');
        mw.reload_module("#edit-testimonials");

        var sel = document.querySelector('.js-add-new-testimonials')
        bootstrap.Tab.getOrCreateInstance(sel).show()
    }

    add_new_testimonial = function () {
        $("#edit-testimonials").attr("edit-id", 0);
        $("#edit-testimonials").attr("project_name", '<?php echo $params['project_name']; ?>');
        mw.reload_module("#edit-testimonials");
   //     $('.js-add-new-testimonials').trigger('click');
      //  $('.js-add-new-testimonials').tab('show');
        var sel = document.querySelector('.js-add-new-testimonials')
        bootstrap.Tab.getOrCreateInstance(sel).show()
    }

    list_testimonial = function () {
      //  $('.js-list-testimonials').trigger('click');
     //   $('.js-list-testimonials').tab('show');

        var sel = document.querySelector('.js-list-testimonials')
        bootstrap.Tab.getOrCreateInstance(sel).show()
    }
        ``
    edit_testimonial = function (id) {
        $('.js-add-new-button').show();
        $("#edit-testimonials").attr("edit-id", id);
        $("#edit-testimonials").attr("project_name", '<?php echo $params['project_name']; ?>');
        mw.reload_module("#edit-testimonials");

        var sel = document.querySelector('.js-add-new-testimonials')
        bootstrap.Tab.getOrCreateInstance(sel).show()


       // $('.js-add-new-testimonials').trigger('click');
      //  $('.js-add-new-testimonials').tab('show');
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
                    mw.parent().reload_module("testimonials");
                });

            }
        });

        mw.$("#AddNew").click(function () {
            mw.$("#add-testimonial-form").show();
            mw.$(this).hide();
        });
    });
</script>
<script>mw.lib.require('mwui_init');</script>

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

    .text-danger.position-absolute {
        right: -10px;
        top: -20px;
    }

    .testimonial-holder .text-danger.position-absolute {
        display: none;
    }

    .testimonial-holder:hover .text-danger.position-absolute {
        display: block;
    }
</style>

<?php

$projects = [];
$selected_project = get_option('show_testimonials_per_project', $params['parent-module-id']);
if (empty($selected_project)) {
    if (isset($params['project_name']) && !empty($params['project_name'])) {
        $selected_project = $params['project_name'];
    }
}

$testimonialsQuery = \Illuminate\Support\Facades\DB::table('testimonials');
if (trim($selected_project) != '') {
    $testimonialsQuery->where('project_name', $selected_project);
}
$data = $testimonialsQuery->orderBy('id','DESC')->get();

?>

<?php if ((trim($selected_project) != '') && $data->count()==0): ?>
    No testimonials data found for the project <b><?php echo $selected_project; ?></b>.
<?php endif; ?>

<?php if ($data != null): ?>
    <script>
        $(document).ready(function () {
            $('.js-hide-on-no-data').show();
        });
    </script>

    <?php foreach ($data as $project): ?>
        <?php
        $projects[$project->project_name][] = $project;
        ?>
    <?php endforeach; ?>

    <div class="muted-cards-3">
        <?php
        foreach ($projects as $key => $project): ?>
            <div>
                <strong class="mb-2 d-block"><?php echo $key; ?></strong>
                <?php foreach ($project as $item): ?>
                    <div class="card style-1 testimonial-holder mb-3" data-id="<?php echo $item->id ?>">
                        <div class="card-body mt-3">
                            <div class="row">
                                <div class="col-auto d-flex flex-column align-items-center">
                                    <div class="img-circle-holder img-absolute">
                                        <img src="<?php print thumbnail($item->client_picture, 75, 75) ?>"/>
                                    </div>
                                    <a class="btn btn-outline-primary btn-sm mt-2" href="javascript:;" onclick="edit_testimonial('<?php echo $item->id ?>');"><?php _e("Edit"); ?></a>
                                </div>

                                <div class="col">
                                    <a href="javascript:delete_testimonial('<?php echo $item->id ?>');" class="btn btn-link text-danger btn-sm position-absolute" data-bs-toggle="tooltip" data-title="Delete item"><i class="mdi mdi-close-thick"></i></a>

                                    <h6 class="font-weight-bold"><?php echo $item->name ?> </h6>

                                    <p><?php print character_limiter($item->content, 400); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <script>
        $(document).ready(function () {
            $('.js-hide-on-no-data').hide();
        });
    </script>
    <div class="text-center">
        <div class="icon-title d-inline-flex">
            <i class="mdi mdi-comment-quote text-muted text-opacity-5"></i> <h5 class="mb-0"><?php _e('You have no testimonials'); ?></h5>
        </div>
    </div>

    <div class="text-center"><a href="javascript:;" onclick="add_new_testimonial()" class="btn btn-primary btn-rounded"><?php _e('Create new'); ?></a></div>
<?php endif; ?>
