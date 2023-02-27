<?php must_have_access(); ?>

<script>

    $(document).ready(function () {
        $("#add-testimonial-form").submit(function (event) {
            var isNew = $('[name="id"]', this).val() === '0';
            event.preventDefault();
            var form = this;
            mw.spinner({
                element: form,
                decorate: true
            }).show()
            var data = $(this).serialize();
            var url = "<?php print api_url('save_testimonial'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {

                $('.js-list-testimonials').trigger('click');

                $('#edit-testimonials').html('');

                mw.reload_module("testimonials/list");
                mw.reload_module("#project-select-testimonials");
                mw.reload_module_parent("#<?php echo $params['parent-module-id']; ?>");

                mw.notification.success('Saved');
                mw.spinner({
                    element: form,
                    decorate: true
                }).hide()
            });
        });
    });

    var handleClientImg = function () {
        mw.top().fileWindow({
            types: 'images',
            change: function (url) {
                if(!url) return;
                url = url.toString();
                mw.$("#client_picture").val(url).trigger('change');
                mw.$(".js-user-image").attr('src', url);
            }
        });
    };
</script>

<style>

    #add-testimonial-form{
        position: relative;
    }


    .js-img-holder:hover img {
        display: none;
    }

    .js-img-holder:hover .js-add-image {
        display: block;
    }

    .js-img-holder .js-add-image .add-the-image {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        align-items: center;
        justify-content: center;
        display: none;
    }

    .js-img-holder:hover .js-add-image .add-the-image {
        display: flex;
    }
    .edit-testimonial-top-nav{
        display: flex;
        justify-content: space-between;
        padding-bottom: 20px;
        align-items: flex-start;
    }
</style>

<script>mw.lib.require('font_awesome5')</script>

<?php $data = false; ?>
<?php if (isset($params['edit-id'])): ?>
    <?php $data = get_testimonials("single=true&id=" . $params['edit-id']); ?>
<?php endif; ?>

<?php if (isset($data['id']) && ($data['id']) == 0): ?>
    <script>
        $(document).ready(function () {
            $('.js-add-new-button').hide();
        });
    </script>
<?php endif; ?>

<?php

if (!isset($data['id'])) {
    $data['id'] = 0;
}
if (!isset($data['name'])) {
    $data['name'] = '';
}
if (!isset($data['content'])) {
    $data['content'] = '';
}
if (!isset($data['read_more_url'])) {
    $data['read_more_url'] = '';
}
if (!isset($data['project_name'])) {
    $data['project_name'] = '';
}
if (!isset($data['client_role'])) {
    $data['client_role'] = '';
}
if (!isset($data['client_picture'])) {
    $data['client_picture'] = '';
}
if (!isset($data['client_website'])) {
    $data['client_website'] = '';
}

if (!isset($data['client_company'])) {
    $data['client_company'] = '';
}
?>

<form id="add-testimonial-form">
    <?php if (($data['id']) == 0): ?>
        <h6 class="font-weight-bold"><?php _e('Add new testimonial'); ?></h6>
    <?php else: ?>
        <div class="edit-testimonial-top-nav">
            <a href="javascript:;" onclick="list_testimonial()" class="btn-link"><i class="mdi mdi-arrow-left"></i> <?php _e('Back'); ?></a>
            <a href="javascript:;" onclick="add_new_testimonial()" class="btn-link"><?php _e('Create new'); ?></a>
        </div>
        <h6 class="font-weight-bold"><?php _e('Edit testimonial'); ?></h6>
    <?php endif; ?>
    <br/>

    <div class="row">
        <div class="col-auto">
            <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>

            <div id="avatar_holder" class="text-center">
                <div class="d-inline-block">
                    <?php if ($data['client_picture'] == '') { ?>
                        <div class="img-circle-holder img-absolute bg-primary-opacity-1 m-auto js-img-holder">
                            <img src="<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no-user.png" class="js-user-image"/>

                            <div class="js-add-image">
                                <a href="javascript:;" class="add-the-image" id="client_img" onclick="handleClientImg();"><i class="mdi mdi-image-plus mdi-24px"></i></a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="img-circle-holder img-absolute bg-primary-opacity-1 m-auto js-img-holder">
                            <img src="<?php print $data['client_picture']; ?>" class="js-user-image"/>

                            <div class="js-add-image">
                                <a href="javascript:;" class="add-the-image" id="client_img" onclick="handleClientImg();"><i class="mdi mdi-image-plus mdi-24px"></i></a>
                            </div>
                        </div>
                    <?php } ?>

                    <span class="text-primary mt-2 d-block" onclick="handleClientImg();"><?php _e("Upload image"); ?></span>
                    <input type="hidden" name="client_picture" id="client_picture" value="<?php print $data['client_picture'] ?>" class="form-control">
                </div>
            </div>
        </div>

        <div class="col">
            <div class="form-group">
                <label class="control-label"><?php _e('Client name'); ?></label>
                <input type="text" name="name" placeholder="Name" value="<?php print $data['name'] ?>" required="required" class="form-control">
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e('Client testimonial'); ?></label>
                <textarea name="content" class="form-control" required="required" rows="10"><?php print $data['content'] ?></textarea>
            </div>

            <button type="button" class="btn btn-link btn-sm mb-3 d-block" onclick="$('#more-testimonial-settings').slideToggle()"><?php _e('Show more settings'); ?></button>

            <div id="more-testimonial-settings" style="display: none;">
                <div class="form-group">
                    <label class="control-label"><?php _e('Client Role'); ?></label>
                    <input type="text" name="client_role" placeholder="CEO, CTO, etc" value="<?php print $data['client_role'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php _e('Company'); ?></label>
                    <input type="text" name="client_company" placeholder="Awesome Co." value="<?php print $data['client_company'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php _e('Client website'); ?></label>
                    <input type="text" name="client_website" placeholder="http://www.example.com" value="<?php print $data['client_website'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php _e('"Read more" button text'); ?></label>
                    <input type="text" name="read_more_url" value="<?php print $data['read_more_url'] ?>" class="form-control">
                </div>


                <?php
                $projectName = '';
                if (isset($params['project_name']) && !empty($params['project_name'])) {
                    $projectName = $params['project_name'];
                }
                $projectNameSettings = get_option('show_testimonials_per_project', $params['parent-module-id']);
                if (!empty($projectNameSettings)) {
                    $projectName = $projectNameSettings;
                }

                if ($data['id'] > 0) {
                    $projectName = $data['project_name'];
                }
                ?>

                <div class="form-group">
                    <label class="control-label"><?php _e('Project name'); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e('You can have more than one “testimonials”, check in Settings tab'); ?></small>
                    <input type="text" name="project_name" value="<?php echo $projectName; ?>" class="form-control">

                    <script>
                        var projectSelect = mw.select({
                            element: '[name="project_name"]',
                            multiple: false,
                            autocomplete: true,
                            tags: false,
                            placeholder: '',
                            ajaxMode: {
                                paginationParam: 'page',
                                searchParam: 'keyword',
                                endpoint: mw.settings.api_url + 'project_testimonial_autocomplete',
                                method: 'get'
                            }
                        });
                        projectSelect.value({id:'<?php echo $projectName; ?>', title: '<?php echo $projectName; ?>'});
                    </script>

                </div>
            </div>

            <div class="mw-ui-field-holder text-end text-right">
                <input type="submit" name="submit" value="Save" class="btn btn-outline-primary btn-sm"/>
            </div>
        </div>
    </div>


</form>
