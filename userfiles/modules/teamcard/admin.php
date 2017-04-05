<?php only_admin_access() ?>
<?php

$settings = get_option('settings', $params['id']);

$defaults = array(
    'name' => '',
    'role' => '',
    'file' => ''
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

?>
<div class="module-live-edit-settings">
<style scoped="scoped">
#teamcard-settings{
    clear: both;
}

#teamcard-settings > div{
    margin-top: 15px;
    clear: both;
}

.add-new{
    float: right;
    margin-bottom: 20px;
    width: 100px;
}

.mw-ui-box-header{
    cursor: -moz-grab;
    cursor: -webkit-grab;
    cursor: grab;
}

</style>
<input type="hidden" class="mw_option_field" name="settings" id="settingsfield" />
<a class="mw-ui-btn mw-ui-btn-invert pull-right add-new" href="javascript:teamcards.create()">+ <?php _e('Add new'); ?></a>
<div id="teamcard-settings">
    <?php
    $count = 0;
    foreach($json as $slide){
        $count++;


        ?>
        <div class="mw-ui-box  teamcard-setting-item" id="teamcard-setting-item-<?php print $count; ?>">
            <div class="mw-ui-box-header"> <a class="pull-right" href="javascript:teamcards.remove('#teamcard-setting-item-<?php print $count; ?>');">x</a></div>
            <div class="mw-ui-box-content mw-accordion-content">
                <div class="mw-ui-row-nodrop">
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <label class="mw-ui-label"><?php _e('Name'); ?></label>
                            <input type="text" class="mw-ui-field teamcard-name w100 " value="<?php print $slide['name']; ?>" >
                        </div>
                    </div>
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <label class="mw-ui-label"><?php _e('Position'); ?></label>
                            <input type="text" class="mw-ui-field teamcard-role w100" value="<?php print $slide['role']; ?>">
                        </div>
                    </div>
                </div>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('File'); ?></label>
                    <input type="hidden" class="mw-ui-field teamcard-file" value="<?php print $slide['file']; ?>">
                    <span class="mw-ui-btn teamcard-file-up"> <span class="ico iupload"></span> <span><?php _e('Upload file'); ?> </span> </span> </div>
            </div>
        </div>
    <?php } ?>
</div></div>
<script>

    teamcards = {
        init:function(item){
            $(item.querySelectorAll('input[type="text"]')).bind('keyup', function(){
                mw.on.stopWriting(this, function(){
                    teamcards.save();
                });
            });
            var up = mw.uploader({
                filetypes:'*',
                element:item.querySelector('.teamcard-file-up')
            });
            $(up).bind('FileUploaded', function(event, data){
                item.querySelector('.teamcard-file').value = data.src
                teamcards.save();
            });
        },

        collect : function(){
            var data = {}, all = mwd.querySelectorAll('.teamcard-setting-item'), l = all.length, i = 0;
            for( ; i < l ; i++){
                var item = all[i];
                data[i] = {};
                data[i]['name'] = item.querySelector('.teamcard-name').value;
                data[i]['role'] = item.querySelector('.teamcard-role').value;
                data[i]['file'] = item.querySelector('.teamcard-file').value;

            }
            return data;
        },
        save: function(){
            mw.$('#settingsfield').val(JSON.stringify(teamcards.collect())).trigger('change');
        },


        create:function(){
            var last = $('.teamcard-setting-item:last');
            var html = last.html();
            var item = mwd.createElement('div');
            item.className = last.attr("class");
            item.innerHTML = html;
            $(item.querySelectorAll('input')).val('');
            $(item.querySelectorAll('.mw-uploader')).remove();
            last.after(item);
            teamcards.init(item);
        },

        remove: function(element){
            var txt;
            var r = confirm("<?php _e('Are you sure?'); ?>");
            if (r == true) {
                $(element).remove();
                teamcards.save();
            }
        },



    }




    $( document ).ready(function() {
        var all = mwd.querySelectorAll('.teamcard-setting-item'), l = all.length, i = 0;
        for( ; i < l ; i++){
            if(!!all[i].prepared) continue;
            var item = all[i];
            item.prepared = true;
            teamcards.init(item);
        }
    });







$( document ).ready(function() {

    $('#teamcard-settings').sortable({
        handle: '.mw-ui-box-header',
		items: ".teamcard-setting-item",

        update: function(event, ui) {
            teamcards.save();
        }
    });
});

</script>