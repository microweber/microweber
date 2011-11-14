<?php
/// @cond ALL
class LuminousFileSystemCache extends LuminousCache {

  private $dir = null;
  private $path = null;

  public function __construct($id) {
    $this->dir = luminous::root() . '/cache/';
    $this->path = $this->dir . '/' . $id;
    parent::__construct($id);
  }

  protected function _create(&$s) {
    if (!@mkdir($this->dir, 0777, true) && !is_dir($this->dir)) {
      $s = "Luminous: Failed to create {$this->dir}";
      return false;
    }
    return true;
  }

  protected function _update() {
    touch($this->path);
  }

  protected function _read() {
    $contents = false;
    if (file_exists($this->path)) {
      $contents = file_get_contents($this->path);
    }
    return $contents;
  }

  protected function _write($data) {
    file_put_contents($this->path, $data, LOCK_EX);
  }

  protected function _purge() {
    if ($this->timeout <= 0) return;
    $purge_file = $this->dir . '/.purgedata';
    if (!file_exists($purge_file)) touch($purge_file);
    $last = 0;
    $fh = fopen($purge_file, 'r+');
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
        foreach(scandir($this->dir) as $file) {
          if ($file[0] === '.') continue;
          $mtime = filemtime($this->dir . '/' . $file);
          if ($time - $mtime > $this->timeout)
            unlink($this->dir . '/' . $file);
        }
      }
      flock($fh, LOCK_UN);
      fclose($fh);
    }
  }
}
/// @endcond