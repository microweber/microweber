<?php

/*
 *
 * type: layout
 *
 * name: Bootstrap 5
 *
 * description: Bootstrap 5
 *
 */
?>
<script type="text/javascript">
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<div class="row">
	 <?php if (!empty($fields_group)): ?>
		 <?php foreach ($fields_group as $fields): ?>
		 
		 <?php if (!empty($fields)): ?>
		 
		  	  <?php foreach ($fields as $field): ?>
		       		<?php echo $field['html']; ?>
		    <?php endforeach; ?>
		 
		 <?php endif; ?>
		 
		 <?php endforeach; ?>
	 <?php endif; ?>
</div>
