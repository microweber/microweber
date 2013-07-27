<?php
only_admin_access();?>
<style>
.term_window {
	width:800px;
	height:600px;
}
@import url(http://fonts.googleapis.com/css?family=Open+Sans);
 @import url(http://fonts.googleapis.com/css?family=Ubuntu+Mono);
.term_window {
	font-family: 'Open Sans', sans-serif;
	font-size: 10px;
	background: -webkit-linear-gradient(45deg, #260e1e 0%, #f57453 100%);
	background:    -moz-linear-gradient(45deg, #260e1e 0%, #f57453 100%);
	background:      -o-linear-gradient(45deg, #260e1e 0%, #f57453 100%);
	
}

#term_buffer {
	 width:100%;
	height:470px;
	overflow:scroll-y;
}
 .term_results {
	 width:100%;
	height:450px;
	 background-color:transparent;
	 color: #efecec;
	 font-size: 12px;
	 font-family: Georgia, "Times New Roman", Times, serif, sans-serif;
}
.term_head {
	background: -webkit-linear-gradient(top, #4d4c47 0%, #3c3b37 100%);
	background:    -moz-linear-gradient(top, #4d4c47 0%, #3c3b37 100%);
	background:      -o-linear-gradient(top, #4d4c47 0%, #3c3b37 100%);
	box-shadow: 0px 2px 0px #575757 inset;
	height: 15px;
	padding: 5px;
	border-radius: 8px 8px 0px 0px;
}
.term_head span {
	font-size: 11px;
	color:#fff;
	font-weight: bold;
	text-shadow:0px -1px 0px #333;
	margin-left: 10px;
}
 
.term_window {
	border:1px solid #eaeaea;
	border-top:none;
	border-radius: 8px 8px 0px 0px;
  	margin:0px auto;
	margin-top:20px;
	background: #000;
	box-shadow: 0px 3px 20px #333;
}
.term_window .term {
	color:#AAA;
	font-size: 14px;
	font-family: 'Ubuntu Mono', sans-serif;
}
 
 
#exec_term_command {
	min-width:20px;
	padding:5px;
}
.select2-container {
	min-width:300px;
}
</style>
<script type="text/javascript">
mw.require("<?php  print $config['url_to_module'] ?>static/base64.js");
mw.require("<?php  print $config['url_to_module'] ?>static/select2.js");

mw.require("<?php  print $config['url_to_module'] ?>static/select2.css");

</script>
<script>
 
$(document).ready(function(){
 
	$('#exec_term_command').on('keyup', function(e) {
    if (e.which == 13) {
		 $v = $(this).val();
		 $v1 = $('#exec_term_command_sel').val();
		 $v = Base64.encode( $v);
		  $v1 = Base64.encode( $v1);
		 $holder = $('#mw_exec_term_command');
		 if($holder.length == 0){
			 $('#term_buffer').html('<div id="mw_exec_term_command"></div>');
		 }
		$('#mw_exec_term_command').attr('exec_command',$v1);
				$('#mw_exec_term_command').attr('exec_command_params',$v);

		mw.load_module('admin/console/term','#mw_exec_term_command')
		//$('#exec_term_command').html(' ').focus();;
        e.preventDefault();
    }
});
 
});


</script>
<script>
        $(document).ready(function() {
			
			 $("#exec_term_command_sel").select2({  }); 
			 
			 
			 });

 </script>

<div class='term_window'>
  <div class='term_head'><span>Logged in as <?php print user_name(); ?></span></div>
  <div class='term'>
    <div id="term_buffer">
      <module type="admin/console/term" id="mw_exec_term_command" />
    </div>
<!--    <div> <span class='cursor' onclick="$('#exec_term_command').focus()">&#9610;</span></div>
-->    <br/>
    <?php $api_func = (get_defined_functions()); 
	
	$data = $api_func['user'];
	 
	
	?>
    <labeL>Select function: <select name="exec_term_command_sel"  id="exec_term_command_sel">
      <?php if(is_array($data )): ?>
      <?php foreach($data  as $item): ?>
      <option value="<?php print $item ?>"><?php print $item ?></option>
      <?php endforeach ; ?>
      <?php endif; ?>
    </select></labeL>
    <br />
<labeL>Enter $params: <input class='exec_term_command' id="exec_term_command" type="text" /></labeL>
  </div>
</div>
