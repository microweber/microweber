<?php

/*

type: layout

name: Default

description: Default

*/
?>
<div class="newsletter-module-wrapper well">

    <h2>Newsletter</h2>

    <p>Subscribe to our newsletter and stay tuned.</p>
    <form method="post" id="newsletters-form-<?php print $params['id'] ?>">
        <?php print csrf_field(); ?>

        <div class="form-group hide-on-success">
            <label class="control-label requiredField" for="email1">
                Email
                <span class="asteriskField">
        *
       </span>
            </label>
            <input class="form-control"  name="email" placeholder="your@email.com" type="text"/>
        </div>
        <div class="form-group  hide-on-success">
            <div>
                <button class="btn btn-primary " name="submit" type="submit">
                    Submit
                </button>
            </div>
        </div>
    </form>






</div>