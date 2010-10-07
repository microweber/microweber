
<?php $dates = $_GET['dates'];
if($dates != ''){
$dates = base64_decode($dates);
$dates = unserialize($dates);

}
?>
<?php // var_dump($dates); ?>



<?php if(!empty($dates)) : ?>



<div class="item">
  <label class="label">Дата на отпътуване*</label>
  <span class="field value">
      <select id="date" name="date" class="required">
          <option value="">Изберете</option>
          <?php foreach($dates as $d) : ?>
          <option value="<?php print $d ?>"><?php echo date('j.m.Y',strtotime($d));  ?></option>
          <?php endforeach; ?>
          
      </select>
  </span>
</div>
<span class="hr">&nbsp;</span>


<?php endif; ?>