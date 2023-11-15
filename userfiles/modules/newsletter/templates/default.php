<?php

/*

type: layout

name: Default

description: Default

*/
?>
<div class="newsletter-module-wrapper well">

    <h2><?php _e('Newsletter'); ?></h2>

    <p><?php _e('Subscribe to our newsletter and stay tuned.'); ?></p>
    <form method="post" id="newsletters-form-<?php print $params['id'] ?>">
        <?php print csrf_form(); ?>
        
         <div class="form-group hide-on-success">
            <label class="control-label requiredField" for="name1">
                <?php _e('Name'); ?>
                <span class="asteriskField">*</span>
            </label>
            <input class="form-control" required="true" name="name" placeholder="Your Name" type="text"/>
        </div>

        <div class="form-group hide-on-success">
            <label class="control-label requiredField" for="email1">
                <?php _e('Email'); ?>
                <span class="asteriskField">*</span> 
            </label>
            <input class="form-control" required="true" name="email" placeholder="name@email.com" type="text"/>
        </div>
        
         <div class="form-group hide-on-success">
            <label class="control-label requiredField" for="email1">
                <?php _e('List'); ?>
                <span class="asteriskField">*</span>
            </label>
            <?php 
            $list_params = array();
            $list_params['no_limit'] = true;
            $list_params['order_by'] = "created_at desc";
            $lists = newsletter_get_lists($list_params);
            ?>
            <select name="list_id" class="form-control">
			<?php if (!empty($lists)): ?>
			<?php foreach($lists as $list) : ?>
			<option value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
			</select>
        </div>

	<?php if($require_terms): ?>
	 <div class="form-group hide-on-success">
		<module type="users/terms" data-for="newsletter" />
	    </div>
	<?php endif; ?>

        <div class="form-group  hide-on-success">
            <div>
                <button class="btn btn-primary " name="submit" type="submit">
                    <?php _e('Submit'); ?>
                </button>
            </div>
        </div>
    </form>


</div>