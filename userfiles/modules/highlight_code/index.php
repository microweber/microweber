<?php

    $text = get_option('text', $params['id']);

    if($text==false)
    {
        $text = 'Hello world';
    }

 
	
	
	
	
	
	
?>
<div class="highlight_code_module">
<pre><code><?php print htmlentities($text); ?></code></pre>
</div>