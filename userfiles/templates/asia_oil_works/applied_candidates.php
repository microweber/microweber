<? if(!empty($comments)) : ?>

<div class="pad2">
  <table cellpadding="0" cellspacing="0" id="results">
    <colgroup>
    <col width="50" />
    <col width="178" />
    <col width="305" />
    <col width="" />
    </colgroup>
    <thead>
      <tr>
        <th > </th>
        <th>Name</th>
        <th>Comment</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <? foreach($comments as $item): ?>
      <? $author = CI::model('users')->getUserById( $item['created_by']); ?>
      <? $cf =  get_custom_fields_for_user($item['created_by']) ?>
      <tr id="comment-list-id-<? print $item['id'];?>">
        <td  ><a href="<? print $cf['picture']["custom_field_value"]; ?>"  >
          <? if($cf['picture']["custom_field_value"]): ?>
          <img src="<? print site_url('phpthumb/phpThumb.php') ?>?src=<? print $cf['picture']["custom_field_value"]; ?>&h=50&w=50" alt=""    /> </a>
          <? endif; ?></td>
        <td><a href="<? print $cf['picture']["custom_field_value"]; ?>"  > <? print user_name( $item['created_by']);?> </a> <br />
          <? if($cf['country']["custom_field_value"]): ?>
          <span class="small"> Country:
          <?  print $cf['country']['custom_field_value'] ?>
          </span> <br />
          <? endif; ?>
          <? if($cf['phones']["custom_field_value"]): ?>
          <span class="small"> Phone:
          <?  print $cf['phones']['custom_field_value'] ?>
          </span> <br />
          <? endif; ?>
          <? if($author["email"]): ?>
          <span class="small"> Email: <a class="small" href="mailto:<?  print $author["email"] ?>">
          <?  print $author["email"] ?>
          </a> </span> <br />
          <? endif; ?>
          <? if($cf['cv']["custom_field_value"]): ?>
          <span class="small"> <a class="small" href="<?  print $cf['cv']['custom_field_value'] ?>" target="_blank"><img src="<? print TEMPLATE_URL ?>img/cv.png" height="16" align="left" hspace="3" /> View CV here</a> </span> <br />
          <? endif; ?></td>
        <? // p($cf )  ?>
        <td><? print ($item['comment_body']); ?></td>
        <td><?  print $item['created_on'] ?></td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>
  <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
</div>
<? else: ?>
No applicants yet.
<? endif; ?>
