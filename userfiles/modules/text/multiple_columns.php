
<div class="mw-row nodrop no-edit">
  <?php 

  $cols = 3;
  
  for($i = 1; $i <= $cols; $i ++) { 
    
  ?>

    <div class="mw-col" style="width: <?php print (100/$cols); ?>%" >
        <div class="mw-col-container safe-mode element ">
            <div class="mw-empty-element element allow-drop allow-edit"></div>
        </div>
    </div>

   <?php } ?>

 
 
</div>
