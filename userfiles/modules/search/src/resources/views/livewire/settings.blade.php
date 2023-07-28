<div x-data="{
showEditTab: 'settings',
showAdvancedDesign: false
}">

    <div class="d-flex justify-content-between align-items-center collapseNav-initialized">
        <div class="d-flex flex-wrap gap-md-4 gap-3">
            <a href="#" x-on:click="showEditTab = 'settings'" :class="{ 'active': showEditTab == 'settings' }"
               class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                Settings
            </a>
            <a href="#" x-on:click="showEditTab = 'design'" :class="{ 'active': showEditTab == 'design' }"
               class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                Design
            </a>
        </div>
    </div>

    <div x-show="showEditTab=='settings'">


        <?php
        $pages = get_content('content_type=page&subtype=dynamic&limit=1000');
        $posts_parent_page = get_option('data-content-id', $moduleId);
        ?>
        <div class="mt-3">
            <label class="live-edit-label">{{__('Search in page')}} </label>
            <select name="data-content-id" class="mw_option_field form-select form-control-live-edit-input" data-width="100%" data-size="5" data-live-search="true">
                <option value="0" <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?> title="<?php _e("None"); ?>"><?php _e("All pages"); ?></option>
                <?php
                $pt_opts = array();
                $pt_opts['link'] = "{empty}{title}";
                $pt_opts['list_tag'] = " ";
                $pt_opts['list_item_tag'] = "option";
                $pt_opts['active_ids'] = $posts_parent_page;
                $pt_opts['active_code_tag'] = '   selected="selected"  ';
                pages_tree($pt_opts);
                ?>
            </select>
        </div>

    </div>
    <div x-show="showEditTab=='design'">
        <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType" />
    </div>

</div>
