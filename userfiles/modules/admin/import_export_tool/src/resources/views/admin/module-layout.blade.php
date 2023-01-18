<div class="row">
    <div class="col-md-12">
        <div>
            <?php $module_info = module_info('admin/import_export_tool'); ?>
            <h5>
                <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
                <strong><?php _e($module_info['name']); ?></strong>
            </h5>
        </div>

        <div class="card style-1 mb-3 mt-3">
            <div class="card-body pt-3">

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="{{route('admin.import-export-tool.index')}}" class="nav-link
             @if(route_is('admin.import-export-tool.index')) active @endif ">
                            Imports
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.import-export-tool.index-exports')}}" class="nav-link
             @if(route_is('admin.import-export-tool.index-exports')) active @endif ">
                            Exports
                        </a>
                    </li>
                </ul>

                <div>
                    @yield('ie_tool_module_content')
                </div>
            </div>
        </div>
    </div>
</div>
