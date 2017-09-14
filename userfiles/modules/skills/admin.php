<?php only_admin_access(); ?>
<div class="module-live-edit-settings">
<?php

$file =  get_option('file', $params['id']);
if(!$file){
    $file = '[{"skill":"", "percent": "78"}]';
}
$skills = json_decode($file, true);
if(sizeof($skills) == 0){
    $skills = array();
}

?>

<label class="mw-ui-label">Set your skills</label>

<div class="skills">
    <?php foreach($skills as $skill){ ?>
        <span class="skillfield">
            <input type="text" autocomplete="off"  value="<?php print $skill['skill']; ?>" placeholder="Insert skill Name" class="mw-ui-field skill" />
            <input type="text" autocomplete="off"  value="<?php print $skill['percent']; ?>" placeholder="Insert skill percent" class="mw-ui-field" />
            <span class="mw-icon-drag"></span>
            <span class="mw-icon-bin"></span>
            <select>
                <option value="primary">Primary</option>
                <option value="warning">Warning</option>
                <option value="danger">Danger</option>
                <option value="success">Success</option>
                <option value="info">Info</option>
            </select>
        </span>
    <?php } ?>
</div>

<span class="mw-ui-btn w100" onclick="addNewskill();"><span class="mw-icon-plus"></span> Add More</span>

<input type="hidden" value="<?php print $file; ?>" name="file" id="file" class="mw_option_field mw-ui-field w100"  />

<style>

.skillfield .mw-icon-bin,
.skillfield .mw-icon-drag{
    font-size: 20px;
    display: inline-block;
    padding: 2px 7px;
    cursor: pointer;
}
.skillfield .mw-icon-drag{
    cursor: -moz-grab;
    cursor: -webkit-grab;
    cursor: grab;
}

.skillfield{
    padding-bottom:20px;
    display: block;
}


.skill{
    width: 80%;
}

.skillfield input{
    width: 55%
}
.skillfield input + input{
    width: 15%;
    margin-left: 12px;
}

</style>

<script>
addNewskill = function(){
    $(".skillfield:last").after('<span class="skillfield"><input type="text" autocomplete="off"  placeholder="Insert skill Name" class="mw-ui-field skill" /><input type="text" autocomplete="off" placeholder="Insert skill percent" class="mw-ui-field" /><span class="mw-icon-drag"></span><span class="mw-icon-bin"></span></span>');
    $(".skill:last").focus();
    init();
}
init = function(){
   $(".skill").each(function(){
       if(!this.__activated){
           this.__activated = true;
        $(this).on('change input paste', function(){
           save();
        });
        $('.mw-icon-bin', $(this).parent()).on('click', function(){
            $(this).parent().remove()
            save();
        });
       }

   });
   if($('.skillfield').length === 1){
    $('.skillfield .mw-icon-bin:first').hide()

   }
   else{
      $('.skillfield .mw-icon-bin:first').show()
   }

}

time = null;

save = function(){
    clearTimeout(time);
    time = setTimeout(function(){
        var final = [];
        $(".skill").each(function(){
            final.push({skill: this.value, percent:$(this).next().val()});
        });
        $("#file").val(JSON.stringify(final)).trigger('change');
    }, 700);
    if($('.skillfield').length === 1){
    $('.skillfield .mw-icon-bin:first').hide()

   }
   else{
      $('.skillfield .mw-icon-bin:first').show()
   }

}
sort = function(){
    $(".skills").sortable({
        axis:'y',
        stop:function(){
           save()
        },
        handle:'.mw-icon-drag'
    })
}
$(window).bind('load', function(){
    sort();
    init();
});

</script>

</div>

