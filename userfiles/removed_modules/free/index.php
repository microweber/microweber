

<?php $id = 'free'.uniqid(); ?>




<div style="height: 500px;" class="element free-element" id="<?php print $id; ?>">
     <br><br><br>

<span class="btn btn-large free-item">Button</span>

    <div class="free-item relative" style="width: 400px;height: 200px;"><img class="autoscale" src="http://lorempixel.com/400/200/nature/" alt="" /></div>

    <div class="free-item" style="background: rgba(0, 0, 255, 0.7);width: 100px;height: 100px;"></div>

    <h2 class="free-item">Ludnica</h2>

    <p class="free-item">Lorem ipsum sit amet dolor...</p>

</div>



<script>//mw.require("free.js", true);</script>

<script>

$("#<?php print $id ?> .free-item").draggable().resizable();

</script>






