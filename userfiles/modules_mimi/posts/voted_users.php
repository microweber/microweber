<? 


if($post_id): ?>
<? $voted = voted_users($post_id);
 
  loop($voted, 'user_small_square.php');
  ?>
<? endif; ?>