<?php

/// \cond ALL

/**
 * \file scanners.class.php
 * \brief Scanner lookup table definition.
 */ 
/**
 * \class LuminousScanners
 * \author Mark Watkinson
 * \brief A glorified lookup table for languages to scanners.
 * One of these is instantiated in the global scope at the bottom of this source.
 * The parser assumes it to exist and uses it to look up scanners.
 * Users seeking to override scanners or add new scanners should add their
 * scanner into '$luminous_scanners'.
 *
 */




class LuminousScanners
{

  private $lookup_table = array(); /**< 
    The language=>scanner lookup table. Scanner is an array with keys:
    scanner (the string of the scanner's class name),
    file (the path to the file in which its definition resides)
    dependencies (the language name for any scanners it this scanner
      either depends on or needs to instantiate itself)
  */

  private $default_scanner = null; /**<
    Language name of the default scanner to use if none is found
    for a particular language */

  private $descriptions = array();
  
  private $resolved_dependencies = array();

  /**
   * Adds a scanner into the table, or overwrites an existing scanner.
   *
   * \param language_name may be either a string or an array of strings, if
   *    multiple languages are to use the same scanner
   * \param $scanner the name of the LuminousScanner object as string, (not an
   * actual instance!). If the file is actually a dummy file (say for includes),
   * leave $scanner as \c null.
   * \param lang_description a human-readable description of the language.
   * \param file the path to the file in which the scanner is defined.
   * \param dependencies optional, a string or array of strings representing
   *    the language names (given in another call to AddScanner, as
   *    language_name), on which the instantiation of this scanner depends.
   *    i.e. any super-classes, and any classes which this scanner instantiates
   *    itself.
   *
   */
  public function AddScanner($language_name, $scanner,
    $lang_description, $file=null, $dependencies=null)
  {
    
    $dummy = $scanner === null;
    $d = array();
    if (is_array($dependencies))
      $d = $dependencies;
    elseif ($dependencies !== null)
      $d = array($dependencies);
    
    $insert = array('scanner'=>$scanner,
                    'file'=>$file, 
                    'dependencies'=>$d,        
                    );
    if (!is_array($language_name))
      $language_name = array($language_name);
    foreach($language_name as $l) {
      $this->lookup_table[$l] = $insert;
      if (!$dummy)
        $this->AddDescription($lang_description, $l);
    }
    
  }
  
  private function AddDescription($language_name, $language_code)
  {
    if (!isset($this->descriptions[$language_name]))
      $this->descriptions[$language_name] = array();
    $this->descriptions[$language_name][] = $language_code;
  }
  
  
  private function UnsetDescription($language_name)
  {
    foreach($this->descriptions as &$d)
    {
      foreach($d as $k=>$l)
      {
        if($l === $language_name)
          unset($d[$k]);
      }
    }    
  }

  /**
   * Removes a scanner from the table
   *
   * \param language_name may be either a string or an array of strings, each of
   *    which will be removed from the lookup table.
   */
  public function RemoveScanner($language_name)
  {
    if (is_array($language_name))
    {
      foreach($language_name as $l)
      {
        unset($this->lookup_table[$l]);
        $this->UnsetDescription($l);
      }
    }
    else
    {
      $this->UnsetDescription($language_name);
      unset($this->lookup_table[$language_name]);
    }
  }

  /**
   * Sets the default scanner. This is used when none matches a lookup
   * \param scanner the LuminousScanner object
   */
  public function SetDefaultScanner($scanner)
  {
    $this->default_scanner = $scanner;
  }
  
  
  /**
   * Method which retrives the desired scanner array, and
   * recursively settles the include dependencies while doing so.
   * \param language_name the name under which the gramar was originally indexed
   * \param default if true: if the scanner doesn't exist, return the default
   *    scanner. If false, return false
   * \return the scanner-array stored for the given language name
   * \internal
   * \see LuminousScanners::GetScanner
   */ 
  private function GetScannerArray($language_name, $default=true)
  {
    $g = null;
    if (array_key_exists($language_name, $this->lookup_table))
      $g =  $this->lookup_table[$language_name];
    elseif($this->default_scanner !== null && $default === true)
      $g = $this->lookup_table[$this->default_scanner];
    
    if ($g === null)
      return false;

    // Break on circular dependencies.
    if (!isset($this->resolved_dependencies[$language_name]))
    {
      $this->resolved_dependencies[$language_name] = true;    
      foreach($g['dependencies'] as $d)
      {
        $this->GetScannerArray($d, $default);
      }    
      if ($g['file'] !== null)
        require_once($g['file']);
    }
    return $g;
  }

  /**
   * Returns a scanner for a language
   * \param language_name the name under which the gramar was originally indexed
   * \param default if true: if the scanner doesn't exist, return the default
   *    scanner. If false, return false
   * \return The scanner, the default scanner, or null.
   */
  function GetScanner($language_name, $default=true, $instance=true)
  {
    $resolved_dependencies = array();
    $g = $this->GetScannerArray($language_name, $default);
    $resolved_dependencies = array();
    
    if ($g !== false) {
      return $instance? new $g['scanner'] : $g['scanner'];
    }
    return null;
  }
  /**
   * Returns a list of known aliases for scanners.
   * \return a list, the list is such that each item is itself a list whose
   *    elements are aliases of the same scanner. eg:
   * [  
   *    ['c', 'cpp'],
   *    ['java'],
   *    ['py', 'python']
   * ]
   * etc.
   * 
   */
  function ListScanners()
  {
    $l = $this->descriptions;    
    return $l;
  }
  
}

/// \endcond
