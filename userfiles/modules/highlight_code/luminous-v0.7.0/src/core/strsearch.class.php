<?php

/**
 * @cond CORE
 * @brief A basic preg_match wrapper which caches its results
 * 
 * @note This class is used by Scanner and should not need to be used by
 * anything else.
 * 
 * A class encapsulating the process of searching for a substring efficiently
 * it handles caching of results. 
 * 
 * @warning One instance should be used only incrementally along a string.
 * i.e. do not call it with index = 5 then index = 1.
 * 
 * @internal
 * 
 */
class LuminousStringSearch
{
  /// A copy of the string to operate on.
  private $string;
  
  /**
   * The cache is stored as a map of pattern => result,
   * the result is an array of:
   * (0=>index, 1=>match_groups), OR, it is false if there are no more results
   * left in the string.
   */
  private $cache = array();
  
  public function __construct($str) {
    $this->string = $str;
  }
  
  /**
   * @brief Performs a search for the given pattern past the given index.
   * @param $search the pattern to search for
   * @param $index the minimum string index (offset) of a result
   * @param $matches a reference to the return location of the match groups
   * @return the index or false if no match is found.
   */  
  public function 
  match($search, $index, &$matches) {
    $r = false; // return value
    
    if (isset($this->cache[$search])) {
      $a = $this->cache[$search];
      if ($a === false) return false; // no more results

      $r = $a[0];
      $matches = $a[1];
      assert($matches !== null);

      if ($r >= $index) // cache is good!
        return $r;
    }
    // cache not set, or out of date, we have to perform the match
    if (!($ret = preg_match($search, $this->string, $matches_, 
      PREG_OFFSET_CAPTURE,  $index)))  {
        if ($ret === false && LUMINOUS_DEBUG) {
          throw new Exception('preg_match returned false for pattern: "' 
          . $search . '", with code: ' . LuminousUtils::pcre_error_decode(
            preg_last_error()) . " with string length " . strlen($this->string)
              . " and offset " . $index
        );
      }
      $this->cache[$search] = false;
      return false;
    }
    
    $r = $matches_[0][1];
    // strip the offsets from the match_groups
    foreach($matches_ as $i=>&$v)
      $v = $v[0];

    $this->cache[$search] = array($r, $matches_);
    
    $matches = $matches_;
    return $r;
  }
}

/// @endcond
