<?php
/// @cond ALL

/**
 * File system cache driver
 * @brief The FS cache driver handles storing the cache on the filesystem
 *
 * The general structure of the cache is as follows:
 * @c /cache_dir/prefix/cache_id
 *
 * @c cache_dir is the root cache directory (normally luminous/cache),
 * @c prefix is used to separate things out into multiple locations - we take
 * the first two characters of the @c cache_id just to reduce the number of
 * files in any one directory (and maybe on some filesystems this allows
 * slightly faster lookups?).
 * @c cache_id is the unique identifier of the input string (with its first
 * two character missing, for @c prefix).
 *
 * This driver implements necessary functions for reading/writing the cache
 * and performing maintenance.
 *
 */
class LuminousFileSystemCache extends LuminousCache {

  /// root cache directory
  private $dir = null;
  
  /// full path to the cached file (for convenience)
  private $path = null;

  /// subdir within the cache - we factor out the first two
  /// characters of the filename, this reduces the number of files in
  /// any one folder.
  private $subdir = null;

  /// the base filename of the cached file
  private $filename = null;

  public function __construct($id) {
    $this->dir = luminous::root() . '/cache/';
    $this->subdir = substr($id, 0, 2);
    $this->filename = substr($id, 2);
    
    $this->path = rtrim($this->dir, '/') . '/' .
      $this->subdir . '/' . $this->filename;

    parent::__construct($id);
  }

  protected function _log_error($file, $msg) {
    $this->log_error( str_replace('%s', "\"$file\"", $msg) . "\n"
      . "File exists: " . var_export(file_exists($file), true) . "\n"
      . "Readable?: " . var_export(is_readable($file), true) . "\n"
      . "Writable?: "  . var_export(is_writable($file), true) . "\n"
      . (!file_exists($this->dir)?
        "Your cache dir (\"{$this->dir}\") does not exist! \n" : '' )
      . (file_exists($this->dir) && !is_writable($this->dir)?
        "Your cache dir (\"{$this->dir}\") is not writable! \n" : '' )
    );
  }

  protected function _create() {
    $target = $this->dir . '/' . $this->subdir;
    if (!@mkdir($target, 0777, true) && !is_dir($target)) {
     $this->_log_error($target, "%s does not exist, and cannot create.");
      return false;
    }
    return true;
  }

  protected function _update() {
    if (!(@touch($this->path))) {
      $this->_log_error($this->path, "Failed to update (touch) %s");
    }
  }

  protected function _read() {
    $contents = false;
    if (file_exists($this->path)) {
      $contents = @file_get_contents($this->path);
      if ($contents === false)
        $this->_log_error($this->path, 'Failed to read %s"');
    }
    return $contents;
  }

  protected function _write($data) {
    if (@file_put_contents($this->path, $data, LOCK_EX) === false) {
      $this->_log_error($this->path, "Error writing to %s");
    }
  }


  /**
   * Purges the contents of a directory recursively
   */
  private function _purge_recurse($dir) {
    $base = $dir . '/';
    $time = time();
    if (substr($dir, 0, strlen($this->dir)) !== $this->dir) {
      // uh oh, we somehow tried to escape from the cache directory
      assert(0);
      return;
    }
    foreach(scandir($dir) as $f) {
      $fn = $base . $f;
      
      if ($f[0] === '.') continue;
      
      if (is_dir($fn)) {
        $this->_purge_recurse($fn);
      }
      else {
        $update = filemtime($fn);
        if ($time - $update > $this->timeout) {
          unlink($fn);
        }
      }
    }
  }

  protected function _purge() {
    if ($this->timeout <= 0) return;
    $purge_file = $this->dir . '/.purgedata';
    if (!file_exists($purge_file)) @touch($purge_file);
    $last = 0;
    $fh = @fopen($purge_file, 'r+');
    if (!$fh) {
      $this->_log_error($purge_file,
        "Error encountered opening %s for writing");
      return;
    }
    $time = time();
    if (flock($fh, LOCK_EX)) {
      if (filesize($purge_file))
        $last = (int)fread($fh, filesize($purge_file));
      else $last = 0;
      if ($time - $last > 60*60*24) {
        rewind($fh);
        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, $time);
        $this->_purge_recurse($this->dir);
      }
      flock($fh, LOCK_UN);
      fclose($fh);
    }
  }
}
/// @endcond