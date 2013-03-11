<?

namespace mw\utils;
use ZipArchive;
use RecursiveIteratorIterator;
class zip    {
	function compress($source, $destination) {
		if (!extension_loaded('zip') || !file_exists($source)) {
			error('The PHP Zip extension is required!');
		}
 
		$zip = new \ZipArchive();
		if (!$zip -> open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}

		$source = str_replace('\\', '/', realpath($source));
		$source = normalize_path($source);
		if (is_dir($source) === true) {
			$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file) {
				$file = str_replace('\\', '/', $file);
				$file = normalize_path($file, false);

				// Ignore "." and ".." folders
				if (in_array(substr($file, strrpos($file, DS) + 1), array('.', '..', '.git', '.gitignore', '_notes')))
					continue;

				$file = realpath($file);

				if (is_dir($file) === true) {
					$rel_d = str_replace($source, '', $file);

					$zip -> addEmptyDir($rel_d);
				} else if (is_file($file) === true) {
					$rel_d = str_replace($source, '', $file);

					$zip -> addFromString($rel_d, file_get_contents($file));
				}
			}
		} else if (is_file($source) === true) {
			$zip -> addFromString(basename($source), file_get_contents($source));
		}

		return $zip -> close();
	}

}
