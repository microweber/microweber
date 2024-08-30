<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
		<title>PHP LibDiff - Examples</title>
		<link rel="stylesheet" href="styles.css" type="text/css" charset="utf-8"/>
	</head>
	<body>
		<h1>PHP LibDiff - Examples</h1>
		<hr />
		<?php
        spl_autoload_register(function($class) {
            if (0 === strpos($class, 'PhpDiff\\')) {
                return require(dirname(__DIR__) . '/src/' . substr($class, 8) . '.php');
            }
        });
		// Include the diff class

		// Include two sample files for comparison
		$a = explode("\n", file_get_contents(dirname(__FILE__).'/a.txt'));
		$b = explode("\n", file_get_contents(dirname(__FILE__).'/b.txt'));

		// Options for generating the diff
		$options = array(
			//'ignoreWhitespace' => true,
			//'ignoreCase' => true,
		);

		// Initialize the diff class
		$diff = new PhpDiff\Diff($a, $b, $options);

		?>
		<h2>Side by Side Diff</h2>
		<?php

		// Generate a side by side diff
		$renderer = new PhpDiff\Renderer\Html\SideBySide;
		echo $diff->Render($renderer);

		?>
		<h2>Inline Diff</h2>
		<?php

		// Generate an inline diff
		$renderer = new PhpDiff\Renderer\Html\Inline;
		echo $diff->render($renderer);

		?>
		<h2>Unified Diff</h2>
		<pre><?php

		// Generate a unified diff
		$renderer = new PhpDiff\Renderer\Text\Unified;
		echo htmlspecialchars($diff->render($renderer));

		?>
		</pre>
		<h2>Context Diff</h2>
		<pre><?php

		// Generate a context diff
		$renderer = new PhpDiff\Renderer\Text\Context;
		echo htmlspecialchars($diff->render($renderer));
		?>
		</pre>
	</body>
</html>