<?php

/*

type: layout
content_type: static
name: Page with sidebar
position: 10

description: Page with right sidebar

*/
 

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
	<div class="container">
		<div class="row-fluid">
			<div class="span7">
				<h2 class="edit"  field="title" rel="content">New page</h2>
				<div class="edit"  field="content" rel="content">
					<p>You can edit this text</p>
				</div>
			</div>
			<div class="span3 offset1">
				<div class="edit"  field="sidebar" rel="inherit">
					<div class="well">
						<h3>Pages</h3>
						<module type="pages" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
