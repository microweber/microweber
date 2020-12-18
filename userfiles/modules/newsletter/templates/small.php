<?php

/*

type: layout

name: Small

description: Small

*/
?>
<style>
#<?php print $params['id'] ?> {
    max-width: 350px;
    min-height: 260px;
    background-color:#2c540b;
    border-radius:4px;
}
#<?php print $params['id'] ?> h3 {
    font-size:26px;
    color:white;
    padding:16px 4px 12px 4px;
}
#<?php print $params['id'] ?> .btn-default {
    border:none;
}
#<?php print $params['id'] ?> label {
    color: white;
}
#<?php print $params['id'] ?> .form-group {
	margin:4px 20px 6px 20px;
}
#<?php print $params['id'] ?> input[type="text"] {
    width:100%;
}
#<?php print $params['id'] ?> .control-label{
	display:inline-block;
	margin-top:8px;
}
#<?php print $params['id'] ?> .form-group .form-group {
    margin:0;
}
#<?php print $params['id'] ?> a {
	color: #8be827;
}
#<?php print $params['id'] ?> .btn-default {
	margin-top: 2px;
	padding: 10px 17px 10px 17px;
}
#<?php print $params['id'] ?> .module-users-terms {
	margin-bottom:10px;
}
</style>

<div class="newsletter-module-wrapper well">

    <h3><?php _e('Subscribe for monthly news'); ?></h3>

    <form method="post" id="newsletters-form-<?php print $params['id'] ?>">
        <?php print csrf_form(); ?>

         <div class="form-group hide-on-success">
		    <div class="mw-flex-row">
			    <div class="mw-flex-col-md-2 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <label class="control-label" for="name">
                        <?php _e('Name'); ?>
                    </label>
                </div>
			    <div class="mw-flex-col-md-10 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <input class="form-control" required="true" name="name" placeholder="Your Name" type="text"/>
                </div>
             </div>
        </div>

        <div class="form-group hide-on-success">
		    <div class="mw-flex-row">
			    <div class="mw-flex-col-md-2 mw-flex-col-sm-2 mw-flex-col-xs-12">
					<label class="control-label" for="email">
						<?php _e('Email'); ?>
					</label>
                </div>
			    <div class="mw-flex-col-md-10 mw-flex-col-sm-10 mw-flex-col-xs-12">
                    <input class="form-control" required="true" name="email" placeholder="name@email.com" type="text"/>
                </div>
            </div>
        </div>

        <div class="form-group hide-on-success">
		    <div class="mw-flex-row">
			    <div class="mw-flex-col-md-8 mw-flex-col-sm-8 mw-flex-col-xs-12">
				<?php if($require_terms): ?>
					<module type="users/terms" data-for="newsletter" />
				<?php endif; ?>
			    </div>
			    <div class="mw-flex-col-md-4 mw-flex-col-sm-4 mw-flex-col-xs-12">
					<div class="control-group">
						   <module type="btn" button_action="submit" button_style="btn-default" button_text="<?php _e("Subscribe"); ?>"  />
					</div>
			    </div>
			</div>
	    </div>

    </form>


</div>