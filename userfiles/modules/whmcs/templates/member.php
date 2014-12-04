<div class="col-md-3">
	<ul class="nav nav-pills nav-stacked" id="profile_tabs">
		<li class="active"><a href="#home">Home</a></li>
		<li><a href="#profile">Profile</a></li>
		<li><a href="#my_posts">My posts</a></li>
		<li><a href="#my_comments">My comments</a></li>
		<li><a href="#websites">My websites</a></li>
	</ul>
</div>
<div class="col-md-9">
	<div class="tab-content">
		<div class="tab-pane active" id="home">Home</div>
		<div class="tab-pane" id="profile">
			<?php 
		
		$user = get_user();
		?>
			<table class="table table-striped">
				<tbody>
					<tr>
						<td>Email Address</td>
						<td><?php print $user['email']; ?></td>
					</tr>
					<tr>
						<td>First name</td>
						<td><?php print $user['first_name']; ?></td>
					</tr>
					<tr>
						<td>Last name</td>
						<td><?php print $user['last_name']; ?></td>
					</tr>
					<tr>
						<td>Join Date</td>
						<td><?php
											if(!isset($user['created_at']) or $user['created_at'] == false){
												$user['created_at'] = $user['updated_at'];
											}
											 print $user['created_at']; ?></td>
					</tr>
					<tr>
						<td>Total posts</td>
						<td><?php print get_content('debug=1&content_type=post&created_by='.$user['id']); ?></td>
					</tr>
					<tr>
						<td>Total comments</td>
						<td><?php print get('table=comments&count=1&created_by='.$user['id']); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="tab-pane" id="my_posts">
			<?php $posts = get_content('content_type=post&limit=1000&order_by=updated_at desc&created_by='.user_id()); ?>
			<?php if(is_array($posts) and !empty($posts)):  ?>
			<div>
				<h3 class="pad">My posts</h3>
				<table class="table table-hover table-profile">
					<tbody>
						<?php foreach($posts as $item): ?>
						<tr>
							<td><h4><a href="<?php print content_link($item['id']); ?>" class="blue"><?php print $item['title']; ?></a></h4>
								<blockquote>
									<?php
                                                        $desc =  mw('format')->clean_html($item['content']);
											            print character_limiter($desc,200);
											        ?>
									<small> <a href="<?php print content_link($item['id']); ?>">Updated on: <?php print $item['updated_at']; ?></a> </small> </blockquote></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php else: ?>
			<h4>You dont have any posts</h4>
			<?php endif; ?>
		</div>
		<div class="tab-pane" id="my_comments">
			<?php $comments = get_comments('limit=1000&order_by=updated_at desc&created_by='.user_id()); ?>
			<?php if(is_array($comments) and !empty($comments)):  ?>
			<div class="det-forms">
				<h3 class="pad">My comments</h3>
				<table class="table table-hover table-profile">
					<tbody>
						<?php foreach($comments as $item): ?>
						<?php  $content = get_content_by_id($item['rel_id']); ?>
						<tr>
							<td><h4><a class="blue" href="<?php print content_link($item['rel_id']); ?>#comment-<?php print $item['id']; ?>"><?php print $content['title']; ?></a></h4>
								<?php $desc =  mw('format')->clean_html($item['comment_body']);
    											print character_limiter($desc,200);
    											?>
								<br />
								<small><a href="<?php print content_link($content['id']); ?>#comment-<?php print $item['id']; ?>">Commented on: <?php print $item['updated_at']; ?></a></small></td>
							<td></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php else: ?>
			<h4>You dont have any comments</h4>
			<?php endif; ?>
		</div>
		<div class="tab-pane" id="websites">
			<module type="whmcs" template="client_products" />
		</div>
	</div>
</div>
<script type="text/javascript">
		  $('#profile_tabs a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		  });
</script> 