<?php $id = "mw-free-".uniqid(); ?>
<div class="element mw-free-element" style="height: 250px;" id="<?php print $id; ?>"></div>

<script type="text/javascript">

$(document).ready(function(){
     $("#<?php print $id; ?>").resizable({
       handles:"e,s"
     });
});

</script>
