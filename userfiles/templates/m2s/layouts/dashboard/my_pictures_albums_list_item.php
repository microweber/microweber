<? $dashboard_user = user_id_from_url(); ?>

<div class="video_list_item"> <a class="mw_blue_link" href="<? print site_url('dashboard/action:my-pictures/id:'.$the_post['id']); ?>/user_id:<? print $dashboard_user ?>"><? print $the_post['content_title'] ?></a> <a style="background-image: url('<? print thumbnail($the_post['id'], 150) ?>');" class="img" href="<? print site_url('dashboard/action:my-pictures/id:'.$the_post['id']); ?>/user_id:<? print $dashboard_user ?>"> </a> </div>
<? //var_dump($the_post); ?>
