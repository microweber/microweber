<?php

    $text = get_option('text', $params['id']);

    if($text==false)
    {
        $text = 'Hello world';
    }

 
	
	
	
	
	
	
?>
<script>
mw.require('//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/styles/github.min.css');
mw.require('//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/highlight.min.js');
</script>
<script>
			
$(document).ready(function() {
  $('.highlight_code_module > pre code', '#<?php print $params['id'] ?>').each(function(i, block) {
	hljs.highlightBlock(block);
  });
});

		
</script>
<div class="highlight_code_module">
<pre><code><?php print htmlentities($text); ?></code></pre>
</div>