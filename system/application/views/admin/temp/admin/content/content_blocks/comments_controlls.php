<h2>Comments</h2><br />
<br />

     <table width="100%" border="0" cellpadding="2">
  <tr>
    <td>
    
    <label class="lbl">Comments enabled?</label>
<input name="comments_enabled" type="radio" value="y" <?php if($form_values['comments_enabled'] != 'n') : ?> checked="checked" <?php endif; ?> />Yes<br />
<input name="comments_enabled" type="radio" value="n" <?php if($form_values['comments_enabled'] == 'n') : ?> checked="checked" <?php endif; ?> />No<br />
 <br />
    </td>
  </tr>
  <tr>
    <td>  <?php $comments = $temp= $this->comments_model->commentsGetForContentId( $form_values['id']); ?>
        <?php include (ADMINVIEWSPATH.'comments/comments_list.php') ?></td>
  </tr>
</table>
 