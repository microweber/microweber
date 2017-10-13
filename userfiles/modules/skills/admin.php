<?php only_admin_access(); ?>
<div class="module-live-edit-settings">
<?php

$file =  get_option('file', $params['id']);
if(!$file){
    $file = '[{"skill":"", "percent": "78", "style":"primary"}]';
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
            <span class="mw-icon-drag"></span>
            <span class="mw-icon-bin"></span>

            <div class="mw-ui-field-holder mufh-1">
              <label class="mw-ui-label">Skill Name</label>
              <input type="text" autocomplete="off"  value="<?php print $skill['skill']; ?>" placeholder="Insert skill Name" class="mw-ui-field skill" />
            </div>

            <div class="mw-ui-field-holder mufh-2">
              <label class="mw-ui-label">Value(%)</label>
              <input type="number" min="0" max="100" step="1" autocomplete="off"  value="<?php print isset($skill['percent']) ? $skill['percent'] : 50; ?>" placeholder="Percent" class="mw-ui-field percent-field" />
            </div>

            <div class="mw-ui-field-holder mufh-3">
              <label class="mw-ui-label">Style</label>
              <select class="mw-ui-field" data-value="<?php print isset($skill['style']) ? $skill['style'] : 'primary' ?>">
                <option value="">Choose Style</option>
                <option value="primary">Primary</option>
                <option value="warning">Warning</option>
                <option value="danger">Danger</option>
                <option value="success">Success</option>
                <option value="info">Info</option>
              </select>
            </div>
        </span>
    <?php } ?>
</div>

<span class="mw-ui-btn w100" onclick="addNewskill();"><span class="mw-icon-plus"></span> Add More</span>

<textarea name="file" id="file" disabled="disabled" class="mw_option_field" style="display: none"><?php print $file; ?></textarea>

<style>

.skillfield{
  padding: 40px 10px 10px 10px;
  margin: 10px auto;
  border: 1px solid #eee;
  border-radius: 3px;
  position: relative;
  display: block;
  overflow: hidden;
}

.skillfield .mw-icon-bin,
.skillfield .mw-icon-drag{
    font-size: 20px;
    display: inline-block;
    padding: 2px 7px;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 10px;
}
.skillfield .mw-icon-bin{
  right: 30px;
}

.skillfield .mw-icon-drag{
    cursor: -moz-grab;
    cursor: -webkit-grab;
    cursor: grab;
}

.skillfield .mw-ui-field-holder{
  clear: none;
}

.skillfield{
    padding-bottom:20px;
    background: white;
}


.skill{
    width: 70%;
    clear: both;
}

.skillfield .mw-ui-field-holder{
  float: left;
}

.mufh-1{width: 50%;}
.mufh-2{width: 23%;margin: 0 1%;}
.mufh-3{width: 25%;}

.skillfield.ui-sortable-helper{
  box-shadow: rgba(0, 0, 0, 0.2) 0px 7px 15px
}

.skillfield select,
.skillfield input{
    width: 100%
}


</style>

<script>
addNewskill = function(){
    var after = ''
      +'<span class="skillfield">'
        +'<span class="mw-icon-drag"></span>'
        +'<span class="mw-icon-bin"></span>'
        +'<div class="mw-ui-field-holder mufh-1">'
        +'<label class="mw-ui-label">Skill Name</label><input type="text" autocomplete="off"  placeholder="Insert skill Name" class="mw-ui-field skill" />'
        +'</div><div class="mw-ui-field-holder mufh-2">'
        +'<label class="mw-ui-label">Value(%)</label><input type="number" autocomplete="off" min="0" max="100" step="1" value="50 placeholder="Percent" class="mw-ui-field percent-field" />'

        +'</div><div class="mw-ui-field-holder mufh-3">'
          +'<label class="mw-ui-label">Style</label><select class="mw-ui-field" data-value="primary">'
            +'<option value="">Choose Style</option>'
            +'<option value="primary">Primary</option>'
            +'<option value="warning">Warning</option>'
            +'<option value="danger">Danger</option>'
            +'<option value="success">Success</option>'
            +'<option value="info">Info</option>'
        +'</select></div>'
      +'</span>';


    $(".skillfield:last").after(after);
    $(".skill:last").focus();
    init();
}
init = function(){
   $(".skill").each(function(){
       if(!this.__activated){
           this.__activated = true;
        var root = $(this).parents('.skillfield')  
        $('input', root).on('change input paste', function(){
           save();
        });

        $('.mw-icon-bin', root).on('click', function(){
          mw.confirm('Are you sure', function(){
            root.fadeOut(function(){
              root.remove();
              save();
            })
          })

        });

        mw.$('.percent-field', root).on('change', function(){save()})
        mw.$('select[data-value]', root).each(function(){
          $(this).val($(this).dataset('value')).on('change', function(){save()})
        })
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
            var style = $(this).parents('.skillfield').find('select').val();
            if(!style) style = 'primary'
            final.push({skill: this.value, percent:$(this).parents('.skillfield').find('.percent-field').val(), style:style});
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

