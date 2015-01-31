<?php





function mw_hl_code_css(){
	
		template_head('//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/styles/github.min.css');
		template_head('//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/highlight.min.js');
		
		template_head("
			<script>
			
			$(document).ready(function() {
			  $('.highlight_code_module > pre code').each(function(i, block) {
				hljs.highlightBlock(block);
			  });
			});
		
		
		</script>
		");





	/*template_head('<script>hljs.initHighlightingOnLoad()</script>');*/
}
event_bind("mw_frontend",function ($params=false) {
    return mw_hl_code_css($params);
});