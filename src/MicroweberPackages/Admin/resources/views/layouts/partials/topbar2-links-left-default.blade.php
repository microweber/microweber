<button class="navbar-toggler d-xl-none" id="sidebar-toggle">
    <span class="navbar-toggler-icon"></span>
</button>

<script>

    

    document.body.addEventListener('click', function(e){
        var sidebar = document.getElementById('admin-sidebar');
        if(!sidebar.contains(e.target)) {
            document.body.classList.remove('sidebar-mobile-toggle')
        }
        
    });

    document.getElementById('sidebar-toggle').addEventListener('click', function(e){
        
        document.body.classList.toggle('sidebar-mobile-toggle');
        e.stopPropagation();
    });


</script>

 

@if (user_can_access('module.content.edit'))

<button type="button" class="btn btn-light    border-0 admin-toolbar-buttons"
        data-bs-toggle="modal"  data-bs-target="#modal-add-new-admin" aria-expanded="false">
    <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/add-new-button.svg" alt="">
</button>


{{--                <div class="dropdown-menu ">--}}
{{--                        <?php $custom_view = url_param('view'); ?>--}}
{{--                        <?php $custom_action = url_param('action'); ?>--}}
{{--                        <?php event_trigger('content.create.menu'); ?>--}}
{{--                        <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>--}}
{{--                        <?php if (!empty($create_content_menu)): ?>--}}
{{--                        <?php foreach ($create_content_menu as $type => $item): ?>--}}
{{--                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>--}}
{{--                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>--}}
{{--                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>--}}
{{--                        <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>--}}
{{--                        <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>--}}
{{--                        <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>--}}
{{--                        <?php--}}
{{--                        $base_url = route('admin.content.create');--}}
{{--                        if (Route::has('admin.' . $item['content_type'] . '.create')) {--}}
{{--                            $base_url = route('admin.' . $item['content_type'] . '.create');--}}
{{--                        }--}}
{{--                        ?>--}}
{{--                    <a class="dropdown-item" href="<?php print $base_url; ?>"><span--}}
{{--                            class="<?php print $class; ?>"></span> <?php print $title; ?></a>--}}
{{--                    <?php endforeach; ?>--}}
{{--                    <?php endif; ?>--}}
{{--                </div>--}}


@endif
