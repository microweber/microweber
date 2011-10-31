<?php
/// @cond ALL

/**
 * Cache superclass provides a skeleton for implementations using the filesystem
 * or SQL, or anything else.
 */
abstract class LuminousCache {

  protected $gz = true;
  protected $id = null;
  protected $timeout = 0;
  protected $cache_hit = false;
  private $use_cache = true;
  private $creation_check = false;

  public function __construct($id)  {
    $this->id = $id;
  }

  public function set_purge_time($seconds) {
    $this->timeout = $seconds;
  }

  private function _compress($data) {
    return $this->gz? gzcompress($data) : $data;
  }
  private function _decompress($data) {
    return $this->gz? gzuncompress($data) : $data;
  }

  protected abstract function _create(&$err);
  protected abstract function _read();
  protected abstract function _write($data);
  protected abstract function _update();

  protected abstract function _purge();

  private function purge() {
    assert($this->creation_check);
    if ($this->use_cache)
      $this->_purge();
  }

  private function create() {
    if ($this->creation_check) return;
    $this->creation_check = true;
    if (!$this->_create($err)) {
      trigger_error($err);
      $this->use_cache = false;
    } else {
      $this->purge();
    }
  }
  

  /**
   * @brief Reads from the cache
   * @returns the cached string or @c null
   */
  public function read() {
    $this->create();
    if (!$this->use_cache) return null;
    
    $contents = $this->_read();
    if ($contents !== false) {
      $this->cache_hit = true;
      $contents = $this->_decompress($contents);
      $this->_update();
      return $contents;
    } else return null;
  }
  /**
   * @brief Writes into the cache
   * @param $data the data to write
   */
  public function write($data) {
    $this->create();
    $this->purge();
    if (!$this->cache_hit && $this->use_cache)
      $this->_write($this->_compress($data));
    
  }


}

/// @endcond
// ends 'ALL'
