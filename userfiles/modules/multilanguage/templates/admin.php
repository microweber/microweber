<?php
/*
type: layout
name: Default
description: MW Default
*/
?>
<style>
    .module-multilanguage-change-language .flag-icon {
        margin-right: 7px;
    }

    .module-multilanguage-change-language {
        display: inline-block;
    }

    .mw-ui-row.header-top-center-notifs {
        table-layout: auto;
    }

    .multilanguage-display-icon-custom {
        max-width: 18px;
        max-height: 18px;
        margin-right: 5px;
    }
</style>
<?php if (!empty($supported_languages)): ?>
    <script type="text/javascript">
        $(document).ready(function () {

            $('.multilanguage-module-dropdown-settings').on('click', function () {

                var data = {};
                var module_id = 'multilanguage-admin-settings';
                var opts = {};
                opts.width = '800';
                opts.height = '600';
                mw.tools.open_global_module_settings_modal('multilanguage/admin', module_id, opts, data);

            });

 
            mw.dropdown();
        });
    </script>

    <script>mw.lib.require('flag_icons');</script>




<select id="website-language--select" placeholder="<?php _e("Website Language"); ?>" value="<?php print $current_language['locale']; ?>"></select>

<script>
    new TomSelect("#website-language--select",{
        maxItems: null,
        valueField: 'locale',
        labelField: 'title',
        controlInput: false,
        searchField: false,
        mode: 'single',
        dropdownClass: 'dropdown-menu ts-dropdown',
        closeAfterSelect: true,
        items: ['<?php print $current_language['locale']; ?>'],
        render:{
    			item: function(data,escape) {
                    const content = (data.title);
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">'+ data.icon + decodeURIComponent(data.customProperties) + '</span>' + content + '</div>';
    				}
    				return '<div>' + data.icon + content + '</div>';
    			},
    			option: function(data,escape){
                    console.log(2, data)
                    const content = (data.title);
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.icon + decodeURIComponent(data.customProperties) + '</span>' + content + '</div>';
    				}
    				return '<div>' + data.icon + content + '</div>';
    			},
    		},
        onChange: function(val){
            var value = val[0];
            if(value) {
                $.post(mw.settings.api_url + "multilanguage/change_language", {locale: value, is_admin: <?php echo defined('MW_FRONTEND') ? 0 : 1;  ?> })
                    .done(function (data) {
                        if (data.refresh) {
                            if (data.location) {
                                window.location.href = data.location;
                            } else {
                                location.reload();
                            }
                        }
                    });
            }
        },
        options: [
            <?php foreach ($supported_languages as $language): ?>

                {
                    locale: '<?php print $language['locale'] ?>', 
                    title: '<?php 
                        if (!empty($language['display_name'])) {
                                echo $language['display_name'];
                        } else {
                                echo $language['language'];  
                            } ?>', 
                        icon: `
                        <?php if (!empty($language['display_icon'])): ?>
                            <img src="<?php echo $language['display_icon']; ?>" />
                        <?php else: ?>
                            <span class="flag-icon flag-icon-<?php echo get_flag_icon($language['locale']); ?> m-r-10"></span>
                        <?php endif; ?>

                    `
                },
 
            <?php endforeach; ?>
 

            
 
        ],
        create: false
    });
</script>

<?php if (isset($params['show_settings_link']) && $params['show_settings_link'] == true): ?>
    <span class="btn btn-sm multilanguage-module-dropdown-settings">
        <svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m370 976-16-128q-13-5-24.5-12T307 821l-119 50L78 681l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78 471l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12l-16 128H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342 576q0 58 40.5 99t99.5 41Zm0-80q-25 0-42.5-17.5T422 576q0-25 17.5-42.5T482 516q25 0 42.5 17.5T542 576q0 25-17.5 42.5T482 636Zm-2-60Zm-40 320h79l14-106q31-8 57.5-23.5T639 729l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533 362l-13-106h-79l-14 106q-31 8-57.5 23.5T321 423l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427 790l13 106Z"></path></svg>    
        <?php _e('Settings'); ?>
    </span>
<?php endif; ?>

 
<?php endif; ?>
