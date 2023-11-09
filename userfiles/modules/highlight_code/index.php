<?php

    $text = get_option('text', $params['id']);

    if($text==false)
    {
        $text = '<?php print "Hello Wordld"; ?>';
    }








?>
<script>
    mw.lib.require('highlight');
</script>
<script>

$(document).ready(function() {
  $('.highlight_code_module > pre code', '#<?php print $params['id'] ?>').each(function(i, block) {
	hljs.highlightBlock(block);
  });
});


</script>
<div class="highlight_code_module" style="text-align:left;">
<pre><code><?php print htmlentities($text); ?></code></pre>
</div>
