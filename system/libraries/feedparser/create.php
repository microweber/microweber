<?php

require_once 'simplepie.inc';

function normalize_character_set($charset)
{
	return strtolower(preg_replace('/[\x09-\x0D\x20-\x2F\x3A-\x40\x5B-\x60\x7B-\x7E]/', '', $charset));
}

function build_character_set_list()
{
	$file = new SimplePie_File('http://www.iana.org/assignments/character-sets');
	if (!$file->success && !($file->method & SIMPLEPIE_FILE_SOURCE_REMOTE === 0 || ($file->status_code === 200 || $file->status_code > 206 && $file->status_code < 300)))
	{
		return false;
	}
	else
	{
		$data = explode("\n", $file->body);
		unset($file);
		
		foreach ($data as $line)
		{
			// New character set
			if (substr($line, 0, 5) === 'Name:')
			{
				// If we already have one, push it on to the array
				if (isset($aliases))
				{
					natcasesort($aliases);
					for ($i = 0, $count = count($aliases); $i < $count; $i++)
					{
						$aliases[$i] = normalize_character_set($aliases[$i]);
					}
					$charsets[$preferred] = $aliases;
				}
				
				$start = 5 + strspn($line, "\x09\x0A\x0B\xC\x0D\x20", 5);
				$chars = strcspn($line, "\x09\x0A\x0B\xC\x0D\x20", $start);
				$aliases = array(substr($line, $start, $chars));
				$preferred = end($aliases);
			}
			// Another alias
			elseif(substr($line, 0, 6) === 'Alias:')
			{
				$start = 7 + strspn($line, "\x09\x0A\x0B\xC\x0D\x20", 7);
				$chars = strcspn($line, "\x09\x0A\x0B\xC\x0D\x20", $start);
				$aliases[] = substr($line, $start, $chars);
				
				if (end($aliases) === 'None')
				{
					array_pop($aliases);
				}
				elseif (substr($line, 7 + $chars + 1, 21) === '(preferred MIME name)')
				{
					$preferred = end($aliases);
				}
			}
		}
		
		// Compatibility replacements
		$compat = array(
			'EUC-KR' => 'Windows-949',
			'GB2312' => 'GBK',
			'GB_2312-80' => 'GBK',
			'ISO-8859-1' => 'Windows-1252',
			'ISO-8859-9' => 'Windows-1254',
			'ISO-8859-11' => 'Windows-874',
			'KS_C_5601-1987' => 'Windows-949',
			'TIS-620' => 'Windows-874',
			'x-x-big5' => 'Big5',
		);
		
		foreach ($compat as $real => $replace)
		{
			if (isset($charsets[$real]) && isset($charsets[$replace]))
			{
				$charsets[$replace] = array_merge($charsets[$replace], $charsets[$real]);
				unset($charsets[$real]);
			}
			elseif (isset($charsets[$real]))
			{
				$charsets[$replace] = $charsets[$real];
				$charsets[$replace][] = normalize_character_set($replace);
				$charsets[$replace] = array_unique($charsets[$replace]);
				unset($charsets[$real]);
			}
			else
			{
				$charsets[$replace][] = normalize_character_set($real);
			}
		}
		
		// Sort it
		uksort($charsets, 'strnatcasecmp');
		
		// And we're done!
		return $charsets;
	}
}

function charset($charset)
{
	$normalized_charset = normalize_character_set($charset);
	if ($charsets = build_character_set_list())
	{
		foreach ($charsets as $preferred => $aliases)
		{
			if (in_array($normalized_charset, $aliases))
			{
				return $preferred;
			}
		}
		return $charset;
	}
	else
	{
		return false;
	}
}

function build_function()
{
	if ($charsets = build_character_set_list())
	{
		$return = <<<EOF
function charset(\$charset)
{
	/* Character sets are case-insensitive, and also need some further
	normalization in the real world (though we'll return them in the form given
	in their registration). */
	switch (strtolower(preg_replace('/[\\x09-\\x0D\\x20-\\x2F\\x3A-\\x40\\x5B-\\x60\\x7B-\\x7E]/', '', \$charset)))
	{

EOF;
		foreach ($charsets as $preferred => $aliases)
		{
			foreach ($aliases as $alias)
			{
				$return .= "\t\tcase " . var_export($alias, true) . ":\n";
			}
			$return .= "\t\t\treturn " . var_export($preferred, true) . ";\n\n";
		}
		$return .= <<<EOF
		default:
			return \$charset;
	}
}
EOF;
		return $return;
	}
	else
	{
		return false;
	}
}

if (php_sapi_name() === 'cli' && realpath($_SERVER['argv'][0]) === __FILE__)
{
	echo build_function();
}

?>