<?php

namespace MicroweberPackages\PhpQuery;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Exception;
use MicroweberPackages\PhpQuery\Events\DOMEvent;
use MicroweberPackages\PhpQuery\Events\PhpQueryEvents;
use MicroweberPackages\PhpQuery\Callbacks\Callback;
use MicroweberPackages\PhpQuery\Callbacks\CallbackParam;
use MicroweberPackages\PhpQuery\Callbacks\CallbackParameterToReference;
use MicroweberPackages\PhpQuery\Dom\DOMDocumentWrapper;

/**
 * Class representing phpQuery objects.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 *
 * @method PhpQueryObject clone() clone()
 * @method PhpQueryObject empty() empty()
 * @method PhpQueryObject next() next($selector = null)
 * @method PhpQueryObject prev() prev($selector = null)
 *
 * @property int $length
 */
class PhpQueryObject implements \Iterator, \Countable, \ArrayAccess
{
    public $documentID = null;

    /**
     * DOMDocument class.
     *
     * @var DOMDocument
     */
    public $document = null;
    public $charset = null;

    /**
     * @var DOMDocumentWrapper
     */
    public $documentWrapper = null;

    /**
     * XPath interface.
     *
     * @var DOMXPath
     */
    public $xpath = null;

    /**
     * Stack of selected elements.
     *
     * @TODO refactor to ->nodes
     *
     * @var array
     */
    public $elements = array();

    /**
     */
    protected $elementsBackup = array();

    /**
     */
    protected $previous = null;

    /**
     * @TODO deprecate
     */
    protected $root = array();

    /**
     * Indicated if doument is just a fragment (no <html> tag).
     *
     * Every document is realy a full document, so even documentFragments can
     * be queried against <html>, but getDocument(id)->htmlOuter() will return
     * only contents of <body>.
     *
     * @var bool
     */
    public $documentFragment = true;

    /**
     * Iterator interface helper.
     */
    protected $elementsInterator = array();

    /**
     * Iterator interface helper.
     */
    protected $valid = false;

    /**
     * Iterator interface helper.
     */
    protected $current = null;

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function __construct($documentID)
    {
        //		if ($documentID instanceof self)
        //			var_dump($documentID->getDocumentID());
        $id = $documentID instanceof self ? $documentID->getDocumentID() : $documentID;
        //		var_dump($id);
        if (!isset(PhpQuery::$documents[$id])) {
            //			var_dump(PhpQuery::$documents);
            throw new Exception("Document with ID '{$id}' isn't loaded. Use PhpQuery::newDocument(\$html) or PhpQuery::newDocumentFile(\$file) first.");
        }
        $this->documentID = $id;
        $this->documentWrapper = &PhpQuery::$documents[$id];
        $this->document = &$this->documentWrapper->document;
        $this->xpath = &$this->documentWrapper->xpath;
        $this->charset = &$this->documentWrapper->charset;
        $this->documentFragment = &$this->documentWrapper->isDocumentFragment;
        // TODO check $this->DOM->documentElement;
        //		$this->root = $this->document->documentElement;
        $this->root = &$this->documentWrapper->root;
        //		$this->toRoot();
        $this->elements = array($this->root);
    }

    public function __destruct()
    {
    }

    /**
     * @param $attr
     *
     * @return unknown_type
     */
    public function __get($attr)
    {
        switch ($attr) {
            case 'length':
                return $this->size();
            default:
                return $this->$attr;
        }
    }

    /**
     * Saves actual object to $var by reference.
     * Useful when need to break chain.
     *
     * @param PhpQueryObject $var
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function toReference(&$var)
    {
        return $var = $this;
    }

    public function documentFragment($state = null)
    {
        if ($state) {
            PhpQuery::$documents[$this->getDocumentID()]['documentFragment'] = $state;

            return $this;
        }

        return $this->documentFragment;
    }

    /**
     * @TODO documentWrapper
     */
    protected function isRoot($node)
    {
        //		return $node instanceof \DOMDocument || $node->tagName == 'html';
        return $node instanceof \DOMDocument
            || ($node instanceof \DOMElement && $node->tagName == 'html')
            || $this->root->isSameNode($node);
    }

    /**
     */
    protected function stackIsRoot()
    {
        return $this->size() == 1 && $this->isRoot($this->elements[0]);
    }

    /**
     * Enter description here...
     * NON JQUERY METHOD.
     *
     * Watch out, it doesn't creates new instance, can be reverted with end().
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function toRoot()
    {
        $this->elements = array($this->root);

        return $this;
        //		return $this->newInstance(array($this->root));
    }

    /**
     * Saves object's DocumentID to $var by reference.
     * <code>
     * $myDocumentId;
     * PhpQuery::newDocument('<div/>')
     *     ->getDocumentIDRef($myDocumentId)
     *     ->find('div')->...
     * </code>.
     *
     * @param unknown_type $domId
     *
     * @see PhpQuery::newDocument
     * @see PhpQuery::newDocumentFile
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function getDocumentIDRef(&$documentID)
    {
        $documentID = $this->getDocumentID();

        return $this;
    }

    /**
     * Returns object with stack set to document root.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function getDocument()
    {
        return PhpQuery::getDocument($this->getDocumentID());
    }

    /**
     * @return DOMDocument
     */
    public function getDOMDocument()
    {
        return $this->document;
    }

    /**
     * Get object's Document ID.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function getDocumentID()
    {
        return $this->documentID;
    }

    /**
     * Unloads whole document from memory.
     * CAUTION! None further operations will be possible on this document.
     * All objects refering to it will be useless.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function unloadDocument()
    {
        PhpQuery::unloadDocuments($this->getDocumentID());
    }

    public function isHTML()
    {
        return $this->documentWrapper->isHTML;
    }

    public function isXHTML()
    {
        return $this->documentWrapper->isXHTML;
    }

    public function isXML()
    {
        return $this->documentWrapper->isXML;
    }

    /**
     * Enter description here...
     *
     * @link http://docs.jquery.com/Ajax/serialize
     *
     * @return string
     */
    public function serialize()
    {
        return PhpQuery::param($this->serializeArray());
    }

    /**
     * Enter description here...
     *
     * @link http://docs.jquery.com/Ajax/serializeArray
     *
     * @return array
     */
    public function serializeArray($submit = null)
    {
        $source = $this->filter('form, input, select, textarea')
            ->find('input, select, textarea')
            ->andSelf()
            ->not('form');
        $return = array();
        //		$source->dumpDie();
        foreach ($source as $input) {
            $input = PhpQuery::pq($input);
            if ($input->is('[disabled]')) {
                continue;
            }
            if (!$input->is('[name]')) {
                continue;
            }
            if ($input->is('[type=checkbox]') && !$input->is('[checked]')) {
                continue;
            }
            // jquery diff
            if ($submit && $input->is('[type=submit]')) {
                if ($submit instanceof \DOMElement && !$input->elements[0]->isSameNode($submit)) {
                    continue;
                } elseif (is_string($submit) && $input->attr('name') != $submit) {
                    continue;
                }
            }
            $return[] = array(
                'name' => $input->attr('name'),
                'value' => $input->val(),
            );
        }

        return $return;
    }

    /**
     */
    protected function debug($in)
    {
        if (!PhpQuery::$debug) {
            return;
        }
        echo '<pre>';
        print_r($in);
        // file debug
        //		file_put_contents(dirname(__FILE__).'/phpQuery.log', print_r($in, true)."\n", FILE_APPEND);
        // quite handy debug trace
        //		if ( is_array($in))
        //			print_r(array_slice(debug_backtrace(), 3));
        echo "</pre>\n";
    }

    /**
     */
    protected function isRegexp($pattern)
    {
        return in_array(
            $pattern[mb_strlen($pattern) - 1], array('^', '*', '$')
        );
    }

    /**
     * Determines if $char is really a char.
     *
     * @param string $char
     *
     * @return bool
     *
     * @todo rewrite me to charcode range ! ;)
     */
    protected function isChar($char)
    {
        return extension_loaded('mbstring') && PhpQuery::$mbstringSupport ? mb_eregi('\w', $char) : preg_match('@\w@', $char);
    }

    /**
     */
    protected function parseSelector($query)
    {
        // clean spaces
        // TODO include this inside parsing ?
        $query = trim(
            preg_replace('@\s+@', ' ', preg_replace('@\s*(>|\\+|~)\s*@', '\\1', $query)
            )
        );
        $queries = array(array());
        if (!$query) {
            return $queries;
        }
        $return = &$queries[0];
        $specialChars = array('>', ' ');
        //		$specialCharsMapping = array('/' => '>');
        $specialCharsMapping = array();
        $strlen = mb_strlen($query);
        $classChars = array('.', '-');
        $pseudoChars = array('-');
        $tagChars = array('*', '|', '-');
        // split multibyte string
        // http://code.google.com/p/phpquery/issues/detail?id=76
        $_query = array();
        for ($i = 0; $i < $strlen; ++$i) {
            $_query[] = mb_substr($query, $i, 1);
        }
        $query = $_query;
        // it works, but i dont like it...
        $i = 0;
        while ($i < $strlen) {
            $c = $query[$i];
            $tmp = '';
            // TAG
            if ($this->isChar($c) || in_array($c, $tagChars)) {
                while (isset($query[$i])
                    && ($this->isChar($query[$i]) || in_array($query[$i], $tagChars))) {
                    $tmp .= $query[$i];
                    ++$i;
                }
                $return[] = $tmp;
                // IDs
            } elseif ($c == '#') {
                ++$i;
                while (isset($query[$i]) && ($this->isChar($query[$i]) || $query[$i] == '-')) {
                    $tmp .= $query[$i];
                    ++$i;
                }
                $return[] = '#'.$tmp;
                // SPECIAL CHARS
            } elseif (in_array($c, $specialChars)) {
                $return[] = $c;
                ++$i;
                // MAPPED SPECIAL MULTICHARS
                //			} else if ( $c.$query[$i+1] == '//') {
                //				$return[] = ' ';
                //				$i = $i+2;
                // MAPPED SPECIAL CHARS
            } elseif (isset($specialCharsMapping[$c])) {
                $return[] = $specialCharsMapping[$c];
                ++$i;
                // COMMA
            } elseif ($c == ',') {
                $queries[] = array();
                $return = &$queries[count($queries) - 1];
                ++$i;
                while (isset($query[$i]) && $query[$i] == ' ') {
                    $i++;
                }
                // CLASSES
            } elseif ($c == '.') {
                while (isset($query[$i]) && ($this->isChar($query[$i]) || in_array($query[$i], $classChars))) {
                    $tmp .= $query[$i];
                    ++$i;
                }
                $return[] = $tmp;
                // ~ General Sibling Selector
            } elseif ($c == '~') {
                $spaceAllowed = true;
                $tmp .= $query[$i++];
                while (isset($query[$i])
                    && ($this->isChar($query[$i])
                        || in_array($query[$i], $classChars)
                        || $query[$i] == '*'
                        || ($query[$i] == ' ' && $spaceAllowed)
                    )) {
                    if ($query[$i] != ' ') {
                        $spaceAllowed = false;
                    }
                    $tmp .= $query[$i];
                    ++$i;
                }
                $return[] = $tmp;
                // + Adjacent sibling selectors
            } elseif ($c == '+') {
                $spaceAllowed = true;
                $tmp .= $query[$i++];
                while (isset($query[$i])
                    && ($this->isChar($query[$i])
                        || in_array($query[$i], $classChars)
                        || $query[$i] == '*'
                        || ($spaceAllowed && $query[$i] == ' ')
                    )) {
                    if ($query[$i] != ' ') {
                        $spaceAllowed = false;
                    }
                    $tmp .= $query[$i];
                    ++$i;
                }
                $return[] = $tmp;
                // ATTRS
            } elseif ($c == '[') {
                $stack = 1;
                $tmp .= $c;
                while (isset($query[++$i])) {
                    $tmp .= $query[$i];
                    if ($query[$i] == '[') {
                        ++$stack;
                    } elseif ($query[$i] == ']') {
                        --$stack;
                        if (!$stack) {
                            break;
                        }
                    }
                }
                $return[] = $tmp;
                ++$i;
                // PSEUDO CLASSES
            } elseif ($c == ':') {
                $stack = 1;
                $tmp .= $query[$i++];
                while (isset($query[$i]) && ($this->isChar($query[$i]) || in_array($query[$i], $pseudoChars))) {
                    $tmp .= $query[$i];
                    ++$i;
                }
                // with arguments ?
                if (isset($query[$i]) && $query[$i] == '(') {
                    $tmp .= $query[$i];
                    $stack = 1;
                    while (isset($query[++$i])) {
                        $tmp .= $query[$i];
                        if ($query[$i] == '(') {
                            ++$stack;
                        } elseif ($query[$i] == ')') {
                            --$stack;
                            if (!$stack) {
                                break;
                            }
                        }
                    }
                    $return[] = $tmp;
                    ++$i;
                } else {
                    $return[] = $tmp;
                }
            } else {
                ++$i;
            }
        }
        foreach ($queries as $k => $q) {
            if (isset($q[0])) {
                if (isset($q[0][0]) && $q[0][0] == ':') {
                    array_unshift($queries[$k], '*');
                }
                if ($q[0] != '>') {
                    array_unshift($queries[$k], ' ');
                }
            }
        }

        return $queries;
    }

    /**
     * Return matched DOM nodes.
     *
     * @param int $index
     *
     * @return array|DOMElement Single DOMElement or array of DOMElement.
     */
    public function get($index = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        $return = isset($index) ? (isset($this->elements[$index]) ? $this->elements[$index] : null) : $this->elements;
        // pass thou callbacks
        $args = func_get_args();
        $args = array_slice($args, 1);
        foreach ($args as $callback) {
            if (is_array($return)) {
                foreach ($return as $k => $v) {
                    $return[$k] = PhpQuery::callbackRun($callback, array($v));
                }
            } else {
                $return = PhpQuery::callbackRun($callback, array($return));
            }
        }

        return $return;
    }

    /**
     * Return matched DOM nodes.
     * jQuery difference.
     *
     * @param int $index
     *
     * @return array|string Returns string if $index != null
     *
     * @todo implement callbacks
     * @todo return only arrays ?
     * @todo maybe other name...
     */
    public function getString($index = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        if ($index) {
            $return = $this->eq($index)->text();
        } else {
            $return = array();
            for ($i = 0; $i < $this->size(); ++$i) {
                $return[] = $this->eq($i)->text();
            }
        }
        // pass thou callbacks
        $args = func_get_args();
        $args = array_slice($args, 1);
        foreach ($args as $callback) {
            $return = PhpQuery::callbackRun($callback, array($return));
        }

        return $return;
    }

    /**
     * Return matched DOM nodes.
     * jQuery difference.
     *
     * @param int $index
     *
     * @return array|string Returns string if $index != null
     *
     * @todo implement callbacks
     * @todo return only arrays ?
     * @todo maybe other name...
     */
    public function getStrings($index = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        if ($index) {
            $return = $this->eq($index)->text();
        } else {
            $return = array();
            for ($i = 0; $i < $this->size(); ++$i) {
                $return[] = $this->eq($i)->text();
            }
            // pass thou callbacks
            $args = func_get_args();
            $args = array_slice($args, 1);
        }
        foreach ($args as $callback) {
            if (is_array($return)) {
                foreach ($return as $k => $v) {
                    $return[$k] = PhpQuery::callbackRun($callback, array($v));
                }
            } else {
                $return = PhpQuery::callbackRun($callback, array($return));
            }
        }

        return $return;
    }

    /**
     * Returns new instance of actual class.
     *
     * @param array $newStack Optional. Will replace old stack with new and move old one to history.c
     */
    public function newInstance($newStack = null)
    {
        $class = get_class($this);
        // support inheritance by passing old object to overloaded constructor
        $new = $class != 'phpQuery' && $class != 'MicroweberPackages\\PhpQuery\\PhpQuery' ? new $class($this, $this->getDocumentID()) : new self($this->getDocumentID());
        $new->previous = $this;
        if (is_null($newStack)) {
            $new->elements = $this->elements;
            if ($this->elementsBackup) {
                $this->elements = $this->elementsBackup;
            }
        } elseif (is_string($newStack)) {
            $new->elements = PhpQuery::pq($newStack, $this->getDocumentID())->stack();
        } else {
            $new->elements = $newStack;
        }

        return $new;
    }

    /**
     * Enter description here...
     *
     * In the future, when PHP will support XLS 2.0, then we would do that this way:
     * contains(tokenize(@class, '\s'), "something")
     *
     * @param unknown_type $class
     * @param unknown_type $node
     *
     * @return bool
     */
    protected function matchClasses($class, $node)
    {
        // multi-class
        if (mb_strpos($class, '.', 1)) {
            $classes = explode('.', substr($class, 1));
            $classesCount = count($classes);
            $nodeClasses = explode(' ', $node->getAttribute('class'));
            $nodeClassesCount = count($nodeClasses);
            if ($classesCount > $nodeClassesCount) {
                return false;
            }
            $diff = count(
                array_diff(
                    $classes, $nodeClasses
                )
            );
            if (!$diff) {
                return true;
            }
            // single-class
        } else {
            return in_array(
            // strip leading dot from class name
                substr($class, 1),
                // get classes for element as array
                explode(' ', $node->getAttribute('class'))
            );
        }
    }

    /**
     */
    protected function runQuery($XQuery, $selector = null, $compare = null)
    {
        if ($compare && !method_exists($this, $compare)) {
            return false;
        }
        $stack = array();
        if (!$this->elements) {
            $this->debug('Stack empty, skipping...');
        }
        //		var_dump($this->elements[0]->nodeType);
        // element, document
        foreach ($this->stack(array(1, 9, 13)) as $k => $stackNode) {
            $detachAfter = false;
            // to work on detached nodes we need temporary place them somewhere
            // thats because context xpath queries sucks ;]
            $testNode = $stackNode;
            while ($testNode) {
                if (!$testNode->parentNode && !$this->isRoot($testNode)) {
                    $this->root->appendChild($testNode);
                    $detachAfter = $testNode;
                    break;
                }
                $testNode = isset($testNode->parentNode) ? $testNode->parentNode : null;
            }
            // XXX tmp ?
            $xpath = $this->documentWrapper->isXHTML ? $this->getNodeXpath($stackNode, 'html') : $this->getNodeXpath($stackNode);
            // FIXME pseudoclasses-only query, support XML
            $query = $XQuery == '//' && $xpath == '/html[1]' ? '//*' : $xpath.$XQuery;
            $this->debug("XPATH: {$query}");
            // run query, get elements
            $nodes = $this->xpath->query($query);
            $this->debug('QUERY FETCHED');
            if (!$nodes->length) {
                $this->debug('Nothing found');
            }
            $debug = array();
            foreach ($nodes as $node) {
                $matched = false;
                if ($compare) {
                    PhpQuery::$debug ?
                        $this->debug('Found: '.$this->whois($node).", comparing with {$compare}()") : null;
                    $phpQueryDebug = PhpQuery::$debug;
                    PhpQuery::$debug = false;
                    // TODO ??? use PhpQuery::callbackRun()
                    if (call_user_func_array(array($this, $compare), array($selector, $node))) {
                        $matched = true;
                    }
                    PhpQuery::$debug = $phpQueryDebug;
                } else {
                    $matched = true;
                }
                if ($matched) {
                    if (PhpQuery::$debug) {
                        $debug[] = $this->whois($node);
                    }
                    $stack[] = $node;
                }
            }
            if (PhpQuery::$debug) {
                $this->debug('Matched '.count($debug).': '.implode(', ', $debug));
            }
            if ($detachAfter) {
                $this->root->removeChild($detachAfter);
            }
        }
        $this->elements = $stack;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function find($selectors, $context = null, $noHistory = false)
    {
        if (!$noHistory) {
            // backup last stack /for end()/
            $this->elementsBackup = $this->elements;
        }
        // allow to define context
        // TODO combine code below with PhpQuery::pq() context guessing code
        //   as generic function
        if ($context) {
            if (!is_array($context) && $context instanceof \DOMElement) {
                $this->elements = array($context);
            } elseif (is_array($context)) {
                $this->elements = array();
                foreach ($context as $c) {
                    if ($c instanceof \DOMElement) {
                        $this->elements[] = $c;
                    }
                }
            } elseif ($context instanceof self) {
                $this->elements = $context->elements;
            }
        }
        $queries = $this->parseSelector($selectors);
        $this->debug(array('FIND', $selectors, $queries));
        $XQuery = '';
        // remember stack state because of multi-queries
        $oldStack = $this->elements;
        // here we will be keeping found elements
        $stack = array();
        foreach ($queries as $selector) {
            $this->elements = $oldStack;
            $delimiterBefore = false;
            foreach ($selector as $s) {
                // TAG
                $isTag = extension_loaded('mbstring') && PhpQuery::$mbstringSupport ? mb_ereg_match('^[\w|\||-]+$', $s) || $s == '*' : preg_match('@^[\w|\||-]+$@', $s) || $s == '*';
                if ($isTag) {
                    if ($this->isXML()) {
                        // namespace support
                        if (mb_strpos($s, '|') !== false) {
                            $ns = $tag = null;
                            list($ns, $tag) = explode('|', $s);
                            $XQuery .= "$ns:$tag";
                        } elseif ($s == '*') {
                            $XQuery .= '*';
                        } else {
                            $XQuery .= "*[local-name()='$s']";
                        }
                    } else {
                        $XQuery .= $s;
                    }
                    // ID
                } elseif ($s[0] == '#') {
                    if ($delimiterBefore) {
                        $XQuery .= '*';
                    }
                    $XQuery .= "[@id='".substr($s, 1)."']";
                    // ATTRIBUTES
                } elseif ($s[0] == '[') {
                    if ($delimiterBefore) {
                        $XQuery .= '*';
                    }
                    // strip side brackets
                    $attr = trim($s, '][');
                    $execute = false;
                    // attr with specifed value
                    if (mb_strpos($s, '=')) {
                        $value = null;
                        list($attr, $value) = explode('=', $attr);
                        $value = trim($value, "'\"");
                        if ($this->isRegexp($attr)) {
                            // cut regexp character
                            $attr = substr($attr, 0, -1);
                            $execute = true;
                            $XQuery .= "[@{$attr}]";
                        } else {
                            $XQuery .= "[@{$attr}='{$value}']";
                        }
                        // attr without specified value
                    } else {
                        $XQuery .= "[@{$attr}]";
                    }
                    if ($execute) {
                        $this->runQuery($XQuery, $s, 'is');
                        $XQuery = '';
                        if (!$this->length()) {
                            break;
                        }
                    }
                    // CLASSES
                } elseif ($s[0] == '.') {
                    // TODO use return $this->find("./self::*[contains(concat(\" \",@class,\" \"), \" $class \")]");
                    // thx wizDom ;)
                    if ($delimiterBefore) {
                        $XQuery .= '*';
                    }
                    $XQuery .= '[@class]';
                    $this->runQuery($XQuery, $s, 'matchClasses');
                    $XQuery = '';
                    if (!$this->length()) {
                        break;
                    }
                    // ~ General Sibling Selector
                } elseif ($s[0] == '~') {
                    $this->runQuery($XQuery);
                    $XQuery = '';
                    $this->elements = $this
                        ->siblings(
                            substr($s, 1)
                        )->elements;
                    if (!$this->length()) {
                        break;
                    }
                    // + Adjacent sibling selectors
                } elseif ($s[0] == '+') {
                    // TODO /following-sibling::
                    $this->runQuery($XQuery);
                    $XQuery = '';
                    $subSelector = substr($s, 1);
                    $subElements = $this->elements;
                    $this->elements = array();
                    foreach ($subElements as $node) {
                        // search first DOMElement sibling
                        $test = $node->nextSibling;
                        while ($test && !($test instanceof \DOMElement)) {
                            $test = $test->nextSibling;
                        }
                        if ($test && $this->is($subSelector, $test)) {
                            $this->elements[] = $test;
                        }
                    }
                    if (!$this->length()) {
                        break;
                    }
                    // PSEUDO CLASSES
                } elseif ($s[0] == ':') {
                    // TODO optimization for :first :last
                    if ($XQuery) {
                        $this->runQuery($XQuery);
                        $XQuery = '';
                    }
                    if (!$this->length()) {
                        break;
                    }
                    $this->pseudoClasses($s);
                    if (!$this->length()) {
                        break;
                    }
                    // DIRECT DESCENDANDS
                } elseif ($s == '>') {
                    $XQuery .= '/';
                    $delimiterBefore = 2;
                    // ALL DESCENDANDS
                } elseif ($s == ' ') {
                    $XQuery .= '//';
                    $delimiterBefore = 2;
                    // ERRORS
                } else {
                    PhpQuery::debug("Unrecognized token '$s'");
                }
                $delimiterBefore = $delimiterBefore === 2;
            }
            // run query if any
            if ($XQuery && $XQuery != '//') {
                $this->runQuery($XQuery);
                $XQuery = '';
            }
            foreach ($this->elements as $node) {
                if (!$this->elementsContainsNode($node, $stack)) {
                    $stack[] = $node;
                }
            }
        }
        $this->elements = $stack;

        return $this->newInstance();
    }

    /**
     * @todo create API for classes with pseudoselectors
     */
    protected function pseudoClasses($class)
    {
        // TODO clean args parsing ?
        $class = ltrim($class, ':');
        $haveArgs = mb_strpos($class, '(');
        if ($haveArgs !== false) {
            $args = substr($class, $haveArgs + 1, -1);
            $class = substr($class, 0, $haveArgs);
        }
        switch ($class) {
            case 'even':
            case 'odd':
                $stack = array();
                foreach ($this->elements as $i => $node) {
                    if ($class == 'even' && ($i % 2) == 0) {
                        $stack[] = $node;
                    } elseif ($class == 'odd' && $i % 2) {
                        $stack[] = $node;
                    }
                }
                $this->elements = $stack;
                break;
            case 'eq':
                $k = intval($args);
                $this->elements = isset($this->elements[$k]) ? array($this->elements[$k]) : array();
                break;
            case 'gt':
                $this->elements = array_slice($this->elements, $args + 1);
                break;
            case 'lt':
                $this->elements = array_slice($this->elements, 0, $args + 1);
                break;
            case 'first':
                if (isset($this->elements[0])) {
                    $this->elements = array($this->elements[0]);
                }
                break;
            case 'last':
                if ($this->elements) {
                    $this->elements = array($this->elements[count($this->elements) - 1]);
                }
                break;
            /* case 'parent':
              $stack = array();
              foreach($this->elements as $node) {
              if ( $node->childNodes->length )
              $stack[] = $node;
              }
              $this->elements = $stack;
              break; */
            case 'contains':
                $text = trim($args, "\"'");
                $stack = array();
                foreach ($this->elements as $node) {
                    if (mb_stripos($node->textContent, $text) === false) {
                        continue;
                    }
                    $stack[] = $node;
                }
                $this->elements = $stack;
                break;
            case 'not':
                $selector = self::unQuote($args);
                $this->elements = $this->not($selector)->stack();
                break;
            case 'slice':
                // TODO jQuery difference ?
                $args = explode(',', str_replace(', ', ',', trim($args, "\"'"))
                );
                $start = $args[0];
                $end = isset($args[1]) ? $args[1] : null;
                if ($end > 0) {
                    $end = $end - $start;
                }
                $this->elements = array_slice($this->elements, $start, $end);
                break;
            case 'has':
                $selector = trim($args, "\"'");
                $stack = array();
                foreach ($this->stack(1) as $el) {
                    if ($this->find($selector, $el, true)->length) {
                        $stack[] = $el;
                    }
                }
                $this->elements = $stack;
                break;
            case 'submit':
            case 'reset':
                $this->elements = PhpQuery::merge(
                    $this->map(array($this, 'is'), "input[type=$class]", new CallbackParam()
                    ), $this->map(array($this, 'is'), "button[type=$class]", new CallbackParam()
                )
                );
                break;
            //				$stack = array();
            //				foreach($this->elements as $node)
            //					if ($node->is('input[type=submit]') || $node->is('button[type=submit]'))
            //						$stack[] = $el;
            //				$this->elements = $stack;
            case 'input':
                $this->elements = $this->map(
                    array($this, 'is'), 'input', new CallbackParam()
                )->elements;
                break;
            case 'password':
            case 'checkbox':
            case 'radio':
            case 'hidden':
            case 'image':
            case 'file':
                $this->elements = $this->map(
                    array($this, 'is'), "input[type=$class]", new CallbackParam()
                )->elements;
                break;
            case 'parent':
                $this->elements = $this->map(
                    create_function('$node', '
					return $node instanceof \DOMElement && $node->childNodes->length
						? $node : null;')
                )->elements;
                break;
            case 'empty':
                $this->elements = $this->map(
                    create_function('$node', '
					return $node instanceof \DOMElement && $node->childNodes->length
						? null : $node;')
                )->elements;
                break;
            case 'disabled':
            case 'selected':
            case 'checked':
                $this->elements = $this->map(
                    array($this, 'is'), "[$class]", new CallbackParam()
                )->elements;
                break;
            case 'enabled':
                $this->elements = $this->map(
                    create_function('$node', '
					return \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->not(":disabled") ? $node : null;')
                )->elements;
                break;
            case 'header':
                $this->elements = $this->map(
                    create_function('$node', '$isHeader = isset($node->tagName) && in_array($node->tagName, array(
					"h1", "h2", "h3", "h4", "h5", "h6", "h7"
				));
				return $isHeader
					? $node
					: null;')
                )->elements;
                //				$this->elements = $this->map(
                //					create_function('$node', '$node = \MicroweberPackages\PhpQuery\PhpQuery::pq($node);
                //						return $node->is("h1")
                //							|| $node->is("h2")
                //							|| $node->is("h3")
                //							|| $node->is("h4")
                //							|| $node->is("h5")
                //							|| $node->is("h6")
                //							|| $node->is("h7")
                //							? $node
                //							: null;')
                //				)->elements;
                break;
            case 'only-child':
                $this->elements = $this->map(
                    create_function('$node', 'return \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->siblings()->size() == 0 ? $node : null;')
                )->elements;
                break;
            case 'first-child':
                $this->elements = $this->map(
                    create_function('$node', 'return \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->prevAll()->size() == 0 ? $node : null;')
                )->elements;
                break;
            case 'last-child':
                $this->elements = $this->map(
                    create_function('$node', 'return \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->nextAll()->size() == 0 ? $node : null;')
                )->elements;
                break;
            case 'nth-child':
                $param = trim($args, "\"'");
                if (!$param) {
                    break;
                }
                // nth-child(n+b) to nth-child(1n+b)
                if ($param[0] == 'n') {
                    $param = '1'.$param;
                }
                // :nth-child(index/even/odd/equation)
                if ($param == 'even' || $param == 'odd') {
                    $mapped = $this->map(
                        create_function('$node, $param', '$index = \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->prevAll()->size()+1;
					if ($param == "even" && ($index%2) == 0)
						return $node;
					else if ($param == "odd" && $index%2 == 1)
						return $node;
					else
						return null;'), new CallbackParam(), $param
                    );
                } elseif (mb_strlen($param) > 1 && $param[1] == 'n') {
                    // an+b
                    $mapped = $this->map(
                        create_function('$node, $param', '$prevs = \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->prevAll()->size();
					$index = 1+$prevs;
					$b = mb_strlen($param) > 3
						? $param{3}
						: 0;
					$a = $param{0};
					if ($b && $param{2} == "-")
						$b = -$b;
					if ($a > 0) {
						return ($index-$b)%$a == 0
							? $node
							: null;
						PhpQuery::debug($a."*".floor($index/$a)."+$b-1 == ".($a*floor($index/$a)+$b-1)." ?= $prevs");
						return $a*floor($index/$a)+$b-1 == $prevs
								? $node
								: null;
					} else if ($a == 0)
						return $index == $b
								? $node
								: null;
					else
						// negative value
						return $index <= $b
								? $node
								: null;
//							if (! $b)
//								return $index%$a == 0
//									? $node
//									: null;
//							else
//								return ($index-$b)%$a == 0
//									? $node
//									: null;
					'), new CallbackParam(), $param
                    );
                } else {
                    // index
                    $mapped = $this->map(
                        create_function('$node, $index', '$prevs = \MicroweberPackages\PhpQuery\PhpQuery::pq($node)->prevAll()->size();
					if ($prevs && $prevs == $index-1)
						return $node;
					else if (! $prevs && $index == 1)
						return $node;
					else
						return null;'), new CallbackParam(), $param
                    );
                }
                $this->elements = $mapped->elements;
                break;
            default:
                $this->debug("Unknown pseudoclass '{$class}', skipping...");
        }
    }

    /**
     */
    protected function __pseudoClassParam($paramsString)
    {
        // TODO;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function is($selector, $nodes = null)
    {
        PhpQuery::debug(array('Is:', $selector));
        if (!$selector) {
            return false;
        }
        $oldStack = $this->elements;
        $returnArray = false;
        if ($nodes && is_array($nodes)) {
            $this->elements = $nodes;
        } elseif ($nodes) {
            $this->elements = array($nodes);
        }
        $this->filter($selector, true);
        $stack = $this->elements;
        $this->elements = $oldStack;
        if ($nodes) {
            return $stack ? $stack : null;
        }

        return (bool) count($stack);
    }

    /**
     * Enter description here...
     * jQuery difference.
     *
     * Callback:
     * - $index int
     * - $node DOMNode
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @link http://docs.jquery.com/Traversing/filter
     */
    public function filterCallback($callback, $_skipHistory = false)
    {
        if (!$_skipHistory) {
            $this->elementsBackup = $this->elements;
            $this->debug('Filtering by callback');
        }
        $newStack = array();
        foreach ($this->elements as $index => $node) {
            $result = PhpQuery::callbackRun($callback, array($index, $node));
            if (is_null($result) || (!is_null($result) && $result)) {
                $newStack[] = $node;
            }
        }
        $this->elements = $newStack;

        return $_skipHistory ? $this : $this->newInstance();
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @link http://docs.jquery.com/Traversing/filter
     */
    public function filter($selectors, $_skipHistory = false)
    {
        if ($selectors instanceof Callback or $selectors instanceof Closure) {
            return $this->filterCallback($selectors, $_skipHistory);
        }
        if (!$_skipHistory) {
            $this->elementsBackup = $this->elements;
        }
        $notSimpleSelector = array(' ', '>', '~', '+', '/');
        if (!is_array($selectors)) {
            $selectors = $this->parseSelector($selectors);
        }
        if (!$_skipHistory) {
            $this->debug(array('Filtering:', $selectors));
        }
        $finalStack = array();
        foreach ($selectors as $selector) {
            $stack = array();
            if (!$selector) {
                break;
            }
            // avoid first space or /
            if (in_array($selector[0], $notSimpleSelector)) {
                $selector = array_slice($selector, 1);
            }
            // PER NODE selector chunks
            foreach ($this->stack() as $node) {
                $break = false;
                foreach ($selector as $s) {
                    if (!($node instanceof \DOMElement)) {
                        // all besides DOMElement
                        if ($s[0] == '[') {
                            $attr = trim($s, '[]');
                            if (mb_strpos($attr, '=')) {
                                list($attr, $val) = explode('=', $attr);
                                if ($attr == 'nodeType' && $node->nodeType != $val) {
                                    $break = true;
                                }
                            }
                        } else {
                            $break = true;
                        }
                    } else {
                        // DOMElement only
                        // ID
                        if ($s[0] == '#') {
                            if ($node->getAttribute('id') != substr($s, 1)) {
                                $break = true;
                            }
                            // CLASSES
                        } elseif ($s[0] == '.') {
                            if (!$this->matchClasses($s, $node)) {
                                $break = true;
                            }
                            // ATTRS
                        } elseif ($s[0] == '[') {
                            // strip side brackets
                            $attr = trim($s, '[]');
                            if (mb_strpos($attr, '=')) {
                                list($attr, $val) = explode('=', $attr);
                                $val = self::unQuote($val);
                                if ($attr == 'nodeType') {
                                    if ($val != $node->nodeType) {
                                        $break = true;
                                    }
                                } elseif ($this->isRegexp($attr)) {
                                    $val = extension_loaded('mbstring') && PhpQuery::$mbstringSupport ? quotemeta(trim($val, '"\'')) : preg_quote(trim($val, '"\''), '@');
                                    // switch last character
                                    switch (substr($attr, -1)) {
                                        // quotemeta used insted of preg_quote
                                        // http://code.google.com/p/phpquery/issues/detail?id=76
                                        case '^':
                                            $pattern = '^'.$val;
                                            break;
                                        case '*':
                                            $pattern = '.*'.$val.'.*';
                                            break;
                                        case '$':
                                            $pattern = '.*'.$val.'$';
                                            break;
                                    }
                                    // cut last character
                                    $attr = substr($attr, 0, -1);
                                    $isMatch = extension_loaded('mbstring') && PhpQuery::$mbstringSupport ? mb_ereg_match($pattern, $node->getAttribute($attr)) : preg_match("@{$pattern}@", $node->getAttribute($attr));
                                    if (!$isMatch) {
                                        $break = true;
                                    }
                                } elseif ($node->getAttribute($attr) != $val) {
                                    $break = true;
                                }
                            } elseif (!$node->hasAttribute($attr)) {
                                $break = true;
                            }
                            // PSEUDO CLASSES
                        } elseif ($s[0] == ':') {
                            // skip
                            // TAG
                        } elseif (trim($s)) {
                            if ($s != '*') {
                                // TODO namespaces
                                if (isset($node->tagName)) {
                                    if ($node->tagName != $s) {
                                        $break = true;
                                    }
                                } elseif ($s == 'html' && !$this->isRoot($node)) {
                                    $break = true;
                                }
                            }
                            // AVOID NON-SIMPLE SELECTORS
                        } elseif (in_array($s, $notSimpleSelector)) {
                            $break = true;
                            $this->debug(array('Skipping non simple selector', $selector));
                        }
                    }
                    if ($break) {
                        break;
                    }
                }
                // if element passed all chunks of selector - add it to new stack
                if (!$break) {
                    $stack[] = $node;
                }
            }
            $tmpStack = $this->elements;
            $this->elements = $stack;
            // PER ALL NODES selector chunks
            foreach ($selector as $s) {
                // PSEUDO CLASSES
                if ($s[0] == ':') {
                    $this->pseudoClasses($s);
                }
            }
            foreach ($this->elements as $node) {
                // XXX it should be merged without duplicates
                // but jQuery doesnt do that
                $finalStack[] = $node;
            }
            $this->elements = $tmpStack;
        }
        $this->elements = $finalStack;
        if ($_skipHistory) {
            return $this;
        } else {
            $this->debug('Stack length after filter(): '.count($finalStack));

            return $this->newInstance();
        }
    }

    /**
     * @param $value
     *
     * @return unknown_type
     * @TODO implement in all methods using passed parameters
     */
    protected static function unQuote($value)
    {
        return $value[0] == '\'' || $value[0] == '"' ? substr($value, 1, -1) : $value;
    }

    /**
     * Enter description here...
     *
     * @link http://docs.jquery.com/Ajax/load
     *
     * @return phpQuery|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo Support $selector
     */
    public function load($url, $data = null, $callback = null)
    {
        if ($data && !is_array($data)) {
            $callback = $data;
            $data = null;
        }
        if (mb_strpos($url, ' ') !== false) {
            $matches = null;
            if (extension_loaded('mbstring') && PhpQuery::$mbstringSupport) {
                mb_ereg('^([^ ]+) (.*)$', $url, $matches);
            } else {
                preg_match('^([^ ]+) (.*)$', $url, $matches);
            }
            $url = $matches[1];
            $selector = $matches[2];
            // FIXME this sucks, pass as callback param
            $this->_loadSelector = $selector;
        }
        $ajax = array(
            'url' => $url,
            'type' => $data ? 'POST' : 'GET',
            'data' => $data,
            'complete' => $callback,
            'success' => array($this, '__loadSuccess'),
        );
        PhpQuery::ajax($ajax);

        return $this;
    }

    /**
     * @param $html
     *
     * @return unknown_type
     */
    public function __loadSuccess($html)
    {
        if ($this->_loadSelector) {
            $html = PhpQuery::newDocument($html)->find($this->_loadSelector);
            unset($this->_loadSelector);
        }
        foreach ($this->stack(1) as $node) {
            PhpQuery::pq($node, $this->getDocumentID())
                ->markup($html);
        }
    }

    /**
     * Enter description here...
     *
     * @return phpQuery|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo
     */
    public function css()
    {
        // TODO
        return $this;
    }

    /**
     * @todo
     */
    public function show()
    {
        // TODO
        return $this;
    }

    /**
     * @todo
     */
    public function hide()
    {
        // TODO
        return $this;
    }

    /**
     * Trigger a type of event on every matched element.
     *
     * @param unknown_type $type
     * @param unknown_type $data
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     * @TODO support more than event in $type (space-separated)
     */
    public function trigger($type, $data = array())
    {
        foreach ($this->elements as $node) {
            PhpQueryEvents::trigger($this->getDocumentID(), $type, $data, $node);
        }

        return $this;
    }

    /**
     * This particular method triggers all bound event handlers on an element (for a specific event type) WITHOUT executing the browsers default actions.
     *
     * @param unknown_type $type
     * @param unknown_type $data
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     * @TODO
     */
    public function triggerHandler($type, $data = array())
    {
        // TODO;
    }

    /**
     * Binds a handler to one or more events (like click) for each matched element.
     * Can also bind custom events.
     *
     * @param unknown_type $type
     * @param unknown_type $data     Optional
     * @param unknown_type $callback
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     * @TODO support '!' (exclusive) events
     * @TODO support more than event in $type (space-separated)
     */
    public function bind($type, $data, $callback = null)
    {
        // TODO check if $data is callable, not using is_callable
        if (!isset($callback)) {
            $callback = $data;
            $data = null;
        }
        foreach ($this->elements as $node) {
            PhpQueryEvents::add($this->getDocumentID(), $node, $type, $data, $callback);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $type
     * @param unknown_type $callback
     *
     * @return unknown
     * @TODO namespace events
     * @TODO support more than event in $type (space-separated)
     */
    public function unbind($type = null, $callback = null)
    {
        foreach ($this->elements as $node) {
            PhpQueryEvents::remove($this->getDocumentID(), $node, $type, $callback);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function change($callback = null)
    {
        if ($callback) {
            return $this->bind('change', $callback);
        }

        return $this->trigger('change');
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function submit($callback = null)
    {
        if ($callback) {
            return $this->bind('submit', $callback);
        }

        return $this->trigger('submit');
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function click($callback = null)
    {
        if ($callback) {
            return $this->bind('click', $callback);
        }

        return $this->trigger('click');
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrapAllOld($wrapper)
    {
        $wrapper = PhpQuery::pq($wrapper)->_clone();
        if (!$wrapper->length() || !$this->length()) {
            return $this;
        }
        $wrapper->insertBefore($this->elements[0]);
        $deepest = $wrapper->elements[0];
        while ($deepest->firstChild && $deepest->firstChild instanceof \DOMElement) {
            $deepest = $deepest->firstChild;
        }
        PhpQuery::pq($deepest)->append($this);

        return $this;
    }

    /**
     * Enter description here...
     *
     * TODO testme...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrapAll($wrapper)
    {
        if (!$this->length()) {
            return $this;
        }

        return PhpQuery::pq($wrapper, $this->getDocumentID())
            ->clone()
            ->insertBefore($this->get(0))
            ->map(array($this, '___wrapAllCallback'))
            ->append($this);
    }

    /**
     * @param $node
     *
     * @return unknown_type
     */
    public function ___wrapAllCallback($node)
    {
        $deepest = $node;
        while ($deepest->firstChild && $deepest->firstChild instanceof \DOMElement) {
            $deepest = $deepest->firstChild;
        }

        return $deepest;
    }

    /**
     * Enter description here...
     * NON JQUERY METHOD.
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrapAllPHP($codeBefore, $codeAfter)
    {
        return $this
            ->slice(0, 1)
            ->beforePHP($codeBefore)
            ->end()
            ->slice(-1)
            ->afterPHP($codeAfter)
            ->end();
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrap($wrapper)
    {
        foreach ($this->stack() as $node) {
            PhpQuery::pq($node, $this->getDocumentID())->wrapAll($wrapper);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrapPHP($codeBefore, $codeAfter)
    {
        foreach ($this->stack() as $node) {
            PhpQuery::pq($node, $this->getDocumentID())->wrapAllPHP($codeBefore, $codeAfter);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrapInner($wrapper)
    {
        foreach ($this->stack() as $node) {
            PhpQuery::pq($node, $this->getDocumentID())->contents()->wrapAll($wrapper);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function wrapInnerPHP($codeBefore, $codeAfter)
    {
        foreach ($this->stack(1) as $node) {
            PhpQuery::pq($node, $this->getDocumentID())->contents()
                ->wrapAllPHP($codeBefore, $codeAfter);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     * @testme Support for text nodes
     */
    public function contents()
    {
        $stack = array();
        foreach ($this->stack(1) as $el) {
            // FIXME (fixed) http://code.google.com/p/phpquery/issues/detail?id=56
            //			if (! isset($el->childNodes))
            //				continue;
            foreach ($el->childNodes as $node) {
                $stack[] = $node;
            }
        }

        return $this->newInstance($stack);
    }

    /**
     * Enter description here...
     *
     * jQuery difference.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function contentsUnwrap()
    {
        foreach ($this->stack(1) as $node) {
            if (!$node->parentNode) {
                continue;
            }
            $childNodes = array();
            // any modification in DOM tree breaks childNodes iteration, so cache them first
            foreach ($node->childNodes as $chNode) {
                $childNodes[] = $chNode;
            }
            foreach ($childNodes as $chNode) {
                //				$node->parentNode->appendChild($chNode);
                $node->parentNode->insertBefore($chNode, $node);
            }
            $node->parentNode->removeChild($node);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * jQuery difference.
     */
    public function switchWith($markup)
    {
        $markup = PhpQuery::pq($markup, $this->getDocumentID());
        $content = null;
        foreach ($this->stack(1) as $node) {
            \MicroweberPackages\PhpQuery\PhpQuery::pq($node)
                ->contents()->toReference($content)->end()
                ->replaceWith($markup->clone()->append($content));
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function eq($num)
    {
        $oldStack = $this->elements;
        $this->elementsBackup = $this->elements;
        $this->elements = array();
        if (isset($oldStack[$num])) {
            $this->elements[] = $oldStack[$num];
        }

        return $this->newInstance();
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function size()
    {
        return count($this->elements);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @deprecated Use length as attribute
     */
    public function length():int
    {
        return $this->size();
    }

    public function count() :int
    {
        return $this->size();
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo $level
     */
    public function end($level = 1)
    {
        //		$this->elements = array_pop( $this->history );
        //		return $this;
        //		$this->previous->DOM = $this->DOM;
        //		$this->previous->XPath = $this->XPath;
        return $this->previous ? $this->previous : $this;
    }

    /**
     * Enter description here...
     * Normal use ->clone() .
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function _clone()
    {
        $newStack = array();
        //pr(array('copy... ', $this->whois()));
        //$this->dumpHistory('copy');
        $this->elementsBackup = $this->elements;
        foreach ($this->elements as $node) {
            $newStack[] = $node->cloneNode(true);
        }
        $this->elements = $newStack;

        return $this->newInstance();
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function replaceWithPHP($code)
    {
        return $this->replaceWith(PhpQuery::php($code));
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery $content
     *
     * @link http://docs.jquery.com/Manipulation/replaceWith#content
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function replaceWith($content)
    {
        return $this->after($content)->remove();
    }

    /**
     * Enter description here...
     *
     * @param string $selector
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo this works ?
     */
    public function replaceAll($selector)
    {
        foreach (PhpQuery::pq($selector, $this->getDocumentID()) as $node) {
            PhpQuery::pq($node, $this->getDocumentID())
                ->after($this->_clone())
                ->remove();
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function remove($selector = null)
    {
        $loop = $selector ? $this->filter($selector)->elements : $this->elements;
        foreach ($loop as $node) {
            if (!$node->parentNode) {
                continue;
            }
            if (isset($node->tagName)) {
                $this->debug("Removing '{$node->tagName}'");
            }
            $node->parentNode->removeChild($node);
            // Mutation event
            $event = new \MicroweberPackages\PhpQuery\Events\DOMEvent(array(
                'target' => $node,
                'type' => 'DOMNodeRemoved',
            ));
            PhpQueryEvents::trigger($this->getDocumentID(), $event->type, array($event), $node
            );
        }

        return $this;
    }

    protected function markupEvents($newMarkup, $oldMarkup, $node)
    {
        if ($node->tagName == 'textarea' && $newMarkup != $oldMarkup) {
            $event = new \MicroweberPackages\PhpQuery\Events\DOMEvent(array(
                'target' => $node,
                'type' => 'change',
            ));
            PhpQueryEvents::trigger($this->getDocumentID(), $event->type, array($event), $node
            );
        }
    }

    /**
     * jQuey difference.
     *
     * @param $markup
     *
     * @return unknown_type
     * @TODO trigger change event for textarea
     */
    public function markup($markup = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        $args = func_get_args();
        if ($this->documentWrapper->isXML) {
            return call_user_func_array(array($this, 'xml'), $args);
        } else {
            return call_user_func_array(array($this, 'html'), $args);
        }
    }

    /**
     * jQuey difference.
     *
     * @param $markup
     *
     * @return unknown_type
     */
    public function markupOuter($callback1 = null, $callback2 = null, $callback3 = null)
    {
        $args = func_get_args();
        if ($this->documentWrapper->isXML) {
            return call_user_func_array(array($this, 'xmlOuter'), $args);
        } else {
            return call_user_func_array(array($this, 'htmlOuter'), $args);
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $html
     *
     * @return string|phpQuery|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     * @TODO force html result
     */
    public function html($html = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        if (isset($html)) {
            // INSERT
            $nodes = $this->documentWrapper->import($html);
            $this->empty();
            foreach ($this->stack(1) as $alreadyAdded => $node) {
                // for now, limit events for textarea
                if (($this->isXHTML() || $this->isHTML()) && $node->tagName == 'textarea') {
                    $oldHtml = \MicroweberPackages\PhpQuery\PhpQuery::pq($node, $this->getDocumentID())->markup();
                }
                foreach ($nodes as $newNode) {
                    $node->appendChild($alreadyAdded ? $newNode->cloneNode(true) : $newNode
                    );
                }
                // for now, limit events for textarea
                if (($this->isXHTML() || $this->isHTML()) && $node->tagName == 'textarea') {
                    $this->markupEvents($html, $oldHtml, $node);
                }
            }

            return $this;
        } else {
            // FETCH
            $return = $this->documentWrapper->markup($this->elements, true);
            $args = func_get_args();
            foreach (array_slice($args, 1) as $callback) {
                $return = PhpQuery::callbackRun($callback, array($return));
            }

            return $return;
        }
    }

    /**
     * @TODO force xml result
     */
    public function xml($xml = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        $args = func_get_args();

        return call_user_func_array(array($this, 'html'), $args);
    }

    /**
     * Enter description here...
     *
     * @TODO force html result
     *
     * @return string
     */
    public function htmlOuter($callback1 = null, $callback2 = null, $callback3 = null)
    {
        $markup = $this->documentWrapper->markup($this->elements);
        // pass thou callbacks
        $args = func_get_args();
        foreach ($args as $callback) {
            $markup = PhpQuery::callbackRun($callback, array($markup));
        }

        return $markup;
    }

    /**
     * @TODO force xml result
     */
    public function xmlOuter($callback1 = null, $callback2 = null, $callback3 = null)
    {
        $args = func_get_args();

        return call_user_func_array(array($this, 'htmlOuter'), $args);
    }

    public function __toString()
    {
        return $this->markupOuter();
    }

    /**
     * Just like html(), but returns markup with VALID (dangerous) PHP tags.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo support returning markup with PHP tags when called without param
     */
    public function php($code = null)
    {
        return $this->markupPHP($code);
    }

    /**
     * Enter description here...
     *
     * @param $code
     *
     * @return unknown_type
     */
    public function markupPHP($code = null)
    {
        return isset($code) ? $this->markup(PhpQuery::php($code)) : PhpQuery::markupToPHP($this->markup());
    }

    /**
     * Enter description here...
     *
     * @param $code
     *
     * @return unknown_type
     */
    public function markupOuterPHP()
    {
        return PhpQuery::markupToPHP($this->markupOuter());
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function children($selector = null)
    {
        $stack = array();
        foreach ($this->stack(1) as $node) {
            //			foreach($node->getElementsByTagName('*') as $newNode) {
            foreach ($node->childNodes as $newNode) {
                if ($newNode->nodeType != 1) {
                    continue;
                }
                if ($selector && !$this->is($selector, $newNode)) {
                    continue;
                }
                if ($this->elementsContainsNode($newNode, $stack)) {
                    continue;
                }
                $stack[] = $newNode;
            }
        }
        $this->elementsBackup = $this->elements;
        $this->elements = $stack;

        return $this->newInstance();
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function ancestors($selector = null)
    {
        return $this->children($selector);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function append($content)
    {
        return $this->insert($content, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function appendPHP($content)
    {
        return $this->insert("<php><!-- {$content} --></php>", 'append');
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function appendTo($seletor)
    {
        return $this->insert($seletor, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function prepend($content)
    {
        return $this->insert($content, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @todo accept many arguments, which are joined, arrays maybe also
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function prependPHP($content)
    {
        return $this->insert("<php><!-- {$content} --></php>", 'prepend');
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function prependTo($seletor)
    {
        return $this->insert($seletor, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function before($content)
    {
        return $this->insert($content, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function beforePHP($content)
    {
        return $this->insert("<php><!-- {$content} --></php>", 'before');
    }

    /**
     * Enter description here...
     *
     * @param string|phpQuery
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function insertBefore($seletor)
    {
        return $this->insert($seletor, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function after($content)
    {
        return $this->insert($content, __FUNCTION__);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function afterPHP($content)
    {
        return $this->insert("<php><!-- {$content} --></php>", 'after');
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function insertAfter($seletor)
    {
        return $this->insert($seletor, __FUNCTION__);
    }

    /**
     * Internal insert method. Don't use it.
     *
     * @param unknown_type $target
     * @param unknown_type $type
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function insert($target, $type)
    {
        $this->debug("Inserting data with '{$type}'");
        $to = false;
        switch ($type) {
            case 'appendTo':
            case 'prependTo':
            case 'insertBefore':
            case 'insertAfter':
                $to = true;
        }
        switch (gettype($target)) {
            case 'string':
                $insertFrom = $insertTo = array();
                if ($to) {
                    // INSERT TO
                    $insertFrom = $this->elements;
                    if (PhpQuery::isMarkup($target)) {
                        // $target is new markup, import it
                        $insertTo = $this->documentWrapper->import($target);
                        // insert into selected element
                    } else {
                        // $tagret is a selector
                        $thisStack = $this->elements;
                        $this->toRoot();
                        $insertTo = $this->find($target)->elements;
                        $this->elements = $thisStack;
                    }
                } else {
                    // INSERT FROM
                    $insertTo = $this->elements;
                    $insertFrom = $this->documentWrapper->import($target);
                }
                break;
            case 'object':
                $insertFrom = $insertTo = array();
                // phpQuery
                if ($target instanceof self) {
                    if ($to) {
                        $insertTo = $target->elements;
                        if ($this->documentFragment && $this->stackIsRoot()) {
                            // get all body children
                            //							$loop = $this->find('body > *')->elements;
                            // TODO test it, test it hard...
                            //							$loop = $this->newInstance($this->root)->find('> *')->elements;
                            $loop = $this->root->childNodes;
                        } else {
                            $loop = $this->elements;
                        }
                        // import nodes if needed
                        $insertFrom = $this->getDocumentID() == $target->getDocumentID() ? $loop : $target->documentWrapper->import($loop);
                    } else {
                        $insertTo = $this->elements;
                        if ($target->documentFragment && $target->stackIsRoot()) {
                            // get all body children
                            //							$loop = $target->find('body > *')->elements;
                            $loop = $target->root->childNodes;
                        } else {
                            $loop = $target->elements;
                        }
                        // import nodes if needed
                        $insertFrom = $this->getDocumentID() == $target->getDocumentID() ? $loop : $this->documentWrapper->import($loop);
                    }
                    // 'DOMNode'
                } elseif ($target instanceof \DOMNode) {
                    // import node if needed
                    //					if ( $target->ownerDocument != $this->DOM )
                    //						$target = $this->DOM->importNode($target, true);
                    if ($to) {
                        $insertTo = array($target);
                        if ($this->documentFragment && $this->stackIsRoot()) {
                            // get all body children
                            $loop = $this->root->childNodes;
                        }
                        //							$loop = $this->find('body > *')->elements;
                        else {
                            $loop = $this->elements;
                        }
                        foreach ($loop as $fromNode) {
                            // import nodes if needed
                            $insertFrom[] = !$fromNode->ownerDocument->isSameNode($target->ownerDocument) ? $target->ownerDocument->importNode($fromNode, true) : $fromNode;
                        }
                    } else {
                        // import node if needed
                        if (!$target->ownerDocument->isSameNode($this->document)) {
                            $target = $this->document->importNode($target, true);
                        }
                        $insertTo = $this->elements;
                        $insertFrom[] = $target;
                    }
                }
                break;
        }
        // PhpQuery::debug('From '.count($insertFrom).'; To '.count($insertTo).' nodes');
        foreach ($insertTo as $insertNumber => $toNode) {
            // we need static relative elements in some cases
            switch ($type) {
                case 'prependTo':
                case 'prepend':
                    $firstChild = $toNode->firstChild;
                    break;
                case 'insertAfter':
                case 'after':
                    $nextSibling = $toNode->nextSibling;
                    break;
            }
            foreach ($insertFrom as $fromNode) {
                // clone if inserted already before
                $insert = $insertNumber ? $fromNode->cloneNode(true) : $fromNode;
                switch ($type) {
                    case 'appendTo':
                    case 'append':
                        //						$toNode->insertBefore(
                        //							$fromNode,
                        //							$toNode->lastChild->nextSibling
                        //						);
                        $toNode->appendChild($insert);
                        $eventTarget = $insert;
                        break;
                    case 'prependTo':
                    case 'prepend':
                        $toNode->insertBefore(
                            $insert, $firstChild
                        );
                        break;
                    case 'insertBefore':
                    case 'before':
                        if (!isset($toNode->parentNode) or !$toNode->parentNode) {
                            throw new \Exception("No parentNode, can't do {$type}()");
                        } else {
                            $toNode->parentNode->insertBefore(
                                $insert, $toNode
                            );
                        }
                        break;
                    case 'insertAfter':
                    case 'after':
                        if (!isset($toNode->parentNode) or !$toNode->parentNode) {
                            throw new \Exception("No parentNode, can't do {$type}()");
                        } else {
                            $toNode->parentNode->insertBefore(
                                $insert, $nextSibling
                            );
                        }
                        break;
                }
                // Mutation event
                $event = new \MicroweberPackages\PhpQuery\Events\DOMEvent(array(
                    'target' => $insert,
                    'type' => 'DOMNodeInserted',
                ));
                PhpQueryEvents::trigger($this->getDocumentID(), $event->type, array($event), $insert
                );
            }
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return int
     */
    public function index($subject)
    {
        $index = -1;
        $subject = $subject instanceof self ? $subject->elements[0] : $subject;
        foreach ($this->newInstance() as $k => $node) {
            if ($node->isSameNode($subject)) {
                $index = $k;
            }
        }

        return $index;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $start
     * @param unknown_type $end
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     * @testme
     */
    public function slice($start, $end = null)
    {
        //		$last = count($this->elements)-1;
        //		$end = $end
        //			? min($end, $last)
        //			: $last;
        //		if ($start < 0)
        //			$start = $last+$start;
        //		if ($start > $last)
        //			return array();
        if ($end > 0) {
            $end = $end - $start;
        }

        return $this->newInstance(
            array_slice($this->elements, $start, $end)
        );
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function reverse()
    {
        $this->elementsBackup = $this->elements;
        $this->elements = array_reverse($this->elements);

        return $this->newInstance();
    }

    /**
     * Return joined text content.
     *
     * @return string
     */
    public function text($text = null, $callback1 = null, $callback2 = null, $callback3 = null)
    {
        if (isset($text)) {
            return $this->html(htmlspecialchars($text));
        }
        $args = func_get_args();
        $args = array_slice($args, 1);
        $return = '';
        foreach ($this->elements as $node) {
            $text = $node->textContent;
            if (count($this->elements) > 1 && $text) {
                $text .= "\n";
            }
            foreach ($args as $callback) {
                $text = PhpQuery::callbackRun($callback, array($text));
            }
            $return .= $text;
        }

        return $return;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function plugin($class, $file = null)
    {
        PhpQuery::plugin($class, $file);

        return $this;
    }

    /**
     * Deprecated, use $pq->plugin() instead.
     *
     * @deprecated
     *
     * @param $class
     * @param $file
     *
     * @return unknown_type
     */
    public static function extend($class, $file = null)
    {
        return $this->plugin($class, $file);
    }

    /**
     * @param $method
     * @param $args
     *
     * @return unknown_type
     */
    public function __call($method, $args)
    {
        $aliasMethods = array('clone', 'empty');
        if (isset(PhpQuery::$extendMethods[$method])) {
            array_unshift($args, $this);

            return PhpQuery::callbackRun(
                PhpQuery::$extendMethods[$method], $args
            );
        } elseif (isset(PhpQuery::$pluginsMethods[$method])) {
            array_unshift($args, $this);
            $class = PhpQuery::$pluginsMethods[$method];
            $realClass = "PhpQueryObjectPlugin_$class";
            $return = call_user_func_array(
                array($realClass, $method), $args
            );
            // XXX deprecate ?
            return is_null($return) ? $this : $return;
        } elseif (in_array($method, $aliasMethods)) {
            return call_user_func_array(array($this, '_'.$method), $args);
        } else {
            throw new Exception("Method '{$method}' doesnt exist");
        }
    }

    /**
     * Safe rename of next().
     *
     * Use it ONLY when need to call next() on an iterated object (in same time).
     * Normaly there is no need to do such thing ;)
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function _next($selector = null)
    {
        return $this->newInstance(
            $this->getElementSiblings('nextSibling', $selector, true)
        );
    }

    /**
     * Use prev() and next().
     *
     * @deprecated
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function _prev($selector = null)
    {
        return $this->prev($selector);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function prev($selector = null)
    {
        return $this->newInstance(
            $this->getElementSiblings('previousSibling', $selector, true)
        );
    }

    /**
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo
     */
    public function prevAll($selector = null)
    {
        return $this->newInstance(
            $this->getElementSiblings('previousSibling', $selector)
        );
    }

    /**
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo FIXME: returns source elements insted of next siblings
     */
    public function nextAll($selector = null)
    {
        return $this->newInstance(
            $this->getElementSiblings('nextSibling', $selector)
        );
    }

    /**
     */
    protected function getElementSiblings($direction, $selector = null, $limitToOne = false)
    {
        $stack = array();
        $count = 0;
        foreach ($this->stack() as $node) {
            $test = $node;
            while (isset($test->{$direction}) && $test->{$direction}) {
                $test = $test->{$direction};
                if (!$test instanceof \DOMElement) {
                    continue;
                }
                $stack[] = $test;
                if ($limitToOne) {
                    break;
                }
            }
        }
        if ($selector) {
            $stackOld = $this->elements;
            $this->elements = $stack;
            $stack = $this->filter($selector, true)->stack();
            $this->elements = $stackOld;
        }

        return $stack;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function siblings($selector = null)
    {
        $stack = array();
        $siblings = array_merge(
            $this->getElementSiblings('previousSibling', $selector), $this->getElementSiblings('nextSibling', $selector)
        );
        foreach ($siblings as $node) {
            if (!$this->elementsContainsNode($node, $stack)) {
                $stack[] = $node;
            }
        }

        return $this->newInstance($stack);
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function not($selector = null)
    {
        if (is_string($selector)) {
            PhpQuery::debug(array('not', $selector));
        } else {
            PhpQuery::debug('not');
        }
        $stack = array();
        if ($selector instanceof self || $selector instanceof \DOMNode) {
            foreach ($this->stack() as $node) {
                if ($selector instanceof self) {
                    $matchFound = false;
                    foreach ($selector->stack() as $notNode) {
                        if ($notNode->isSameNode($node)) {
                            $matchFound = true;
                        }
                    }
                    if (!$matchFound) {
                        $stack[] = $node;
                    }
                } elseif ($selector instanceof \DOMNode) {
                    if (!$selector->isSameNode($node)) {
                        $stack[] = $node;
                    }
                } else {
                    if (!$this->is($selector)) {
                        $stack[] = $node;
                    }
                }
            }
        } else {
            $orgStack = $this->stack();
            $matched = $this->filter($selector, true)->stack();
            //			$matched = array();
            //			// simulate OR in filter() instead of AND 5y
            //			foreach($this->parseSelector($selector) as $s) {
            //				$matched = array_merge($matched,
            //					$this->filter(array($s))->stack()
            //				);
            //			}
            foreach ($orgStack as $node) {
                if (!$this->elementsContainsNode($node, $matched)) {
                    $stack[] = $node;
                }
            }
        }

        return $this->newInstance($stack);
    }

    /**
     * Enter description here...
     *
     * @param string|PhpQueryObject
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function add($selector = null)
    {
        if (!$selector) {
            return $this;
        }
        $stack = array();
        $this->elementsBackup = $this->elements;
        $found = PhpQuery::pq($selector, $this->getDocumentID());
        $this->merge($found->elements);

        return $this->newInstance();
    }

    /**
     */
    protected function merge()
    {
        foreach (func_get_args() as $nodes) {
            foreach ($nodes as $newNode) {
                if (!$this->elementsContainsNode($newNode)) {
                    $this->elements[] = $newNode;
                }
            }
        }
    }

    /**
     */
    protected function elementsContainsNode($nodeToCheck, $elementsStack = null)
    {
        $loop = !is_null($elementsStack) ? $elementsStack : $this->elements;
        foreach ($loop as $node) {
            if ($node->isSameNode($nodeToCheck)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function parent($selector = null)
    {
        $stack = array();
        foreach ($this->elements as $node) {
            if ($node->parentNode && !$this->elementsContainsNode($node->parentNode, $stack)) {
                $stack[] = $node->parentNode;
            }
        }
        $this->elementsBackup = $this->elements;
        $this->elements = $stack;
        if ($selector) {
            $this->filter($selector, true);
        }

        return $this->newInstance();
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function parents($selector = null)
    {
        $stack = array();
        if (!$this->elements) {
            $this->debug('parents() - stack empty');
        }
        foreach ($this->elements as $node) {
            $test = $node;
            while ($test->parentNode) {
                $test = $test->parentNode;
                if ($this->isRoot($test)) {
                    break;
                }
                if (!$this->elementsContainsNode($test, $stack)) {
                    $stack[] = $test;
                    continue;
                }
            }
        }
        $this->elementsBackup = $this->elements;
        $this->elements = $stack;
        if ($selector) {
            $this->filter($selector, true);
        }

        return $this->newInstance();
    }

    /**
     * Internal stack iterator.
     */
    public function stack($nodeTypes = null)
    {
        if (!isset($nodeTypes)) {
            return $this->elements;
        }
        if (!is_array($nodeTypes)) {
            $nodeTypes = array($nodeTypes);
        }
        $return = array();
        foreach ($this->elements as $node) {
            if (in_array($node->nodeType, $nodeTypes)) {
                $return[] = $node;
            }
        }

        return $return;
    }

    // TODO phpdoc; $oldAttr is result of hasAttribute, before any changes
    protected function attrEvents($attr, $oldAttr, $oldValue, $node)
    {
        // skip events for XML documents
        if (!$this->isXHTML() && !$this->isHTML()) {
            return;
        }
        $event = null;
        // identify
        $isInputValue = $node->tagName == 'input'
            && (
                in_array($node->getAttribute('type'), array('text', 'password', 'hidden'))
                || !$node->getAttribute('type')
            );
        $isRadio = $node->tagName == 'input'
            && $node->getAttribute('type') == 'radio';
        $isCheckbox = $node->tagName == 'input'
            && $node->getAttribute('type') == 'checkbox';
        $isOption = $node->tagName == 'option';
        if ($isInputValue && $attr == 'value' && $oldValue != $node->getAttribute($attr)) {
            $event = new \MicroweberPackages\PhpQuery\Events\DOMEvent(array(
                'target' => $node,
                'type' => 'change',
            ));
        } elseif (($isRadio || $isCheckbox) && $attr == 'checked' && (
                // check
                (!$oldAttr && $node->hasAttribute($attr))
                // un-check
                || (!$node->hasAttribute($attr) && $oldAttr)
            )) {
            $event = new \MicroweberPackages\PhpQuery\Events\DOMEvent(array(
                'target' => $node,
                'type' => 'change',
            ));
        } elseif ($isOption && $node->parentNode && $attr == 'selected' && (
                // select
                (!$oldAttr && $node->hasAttribute($attr))
                // un-select
                || (!$node->hasAttribute($attr) && $oldAttr)
            )) {
            $event = new \MicroweberPackages\PhpQuery\Events\DOMEvent(array(
                'target' => $node->parentNode,
                'type' => 'change',
            ));
        }
        if ($event) {
            PhpQueryEvents::trigger($this->getDocumentID(), $event->type, array($event), $node
            );
        }
    }

    public function attr($attr = null, $value = null)
    {
        foreach ($this->stack(1) as $node) {
            if (!is_null($value)) {
                $loop = $attr == '*' ? $this->getNodeAttrs($node) : array($attr);
                foreach ($loop as $a) {
                    $oldValue = $node->getAttribute($a);
                    $oldAttr = $node->hasAttribute($a);
                    // TODO raises an error when charset other than UTF-8
                    // while document's charset is also not UTF-8
                    @$node->setAttribute($a, $value);
                    $this->attrEvents($a, $oldAttr, $oldValue, $node);
                }
            } elseif ($attr == '*') {
                // jQuery difference
                $return = array();
                foreach ($node->attributes as $n => $v) {
                    $return[$n] = $v->value;
                }

                return $return;
            } else {
                return $node->hasAttribute($attr) ? $node->getAttribute($attr) : null;
            }
        }

        return is_null($value) ? '' : $this;
    }

    /**
     */
    protected function getNodeAttrs($node)
    {
        $return = array();
        foreach ($node->attributes as $n => $o) {
            $return[] = $n;
        }

        return $return;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo check CDATA ???
     */
    public function attrPHP($attr, $code)
    {
        if (!is_null($code)) {
            $value = '<'.'?php '.$code.' ?'.'>';
            // TODO tempolary solution
            // http://code.google.com/p/phpquery/issues/detail?id=17
            //			if (function_exists('mb_detect_encoding') && mb_detect_encoding($value) == 'ASCII')
            //				$value	= mb_convert_encoding($value, 'UTF-8', 'HTML-ENTITIES');
        }
        foreach ($this->stack(1) as $node) {
            if (!is_null($code)) {
                //				$attrNode = $this->DOM->createAttribute($attr);
                $node->setAttribute($attr, $value);
                //				$attrNode->value = $value;
                //				$node->appendChild($attrNode);
            } elseif ($attr == '*') {
                // jQuery diff
                $return = array();
                foreach ($node->attributes as $n => $v) {
                    $return[$n] = $v->value;
                }

                return $return;
            } else {
                return $node->getAttribute($attr);
            }
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function removeAttr($attr)
    {
        foreach ($this->stack(1) as $node) {
            $loop = $attr == '*' ? $this->getNodeAttrs($node) : array($attr);
            foreach ($loop as $a) {
                $oldValue = $node->getAttribute($a);
                $node->removeAttribute($a);
                $this->attrEvents($a, $oldValue, null, $node);
            }
        }

        return $this;
    }

    /**
     * Return form element value.
     *
     * @return string Fields value.
     */
    public function val($val = null)
    {
        if (!isset($val)) {
            if ($this->eq(0)->is('select')) {
                $selected = $this->eq(0)->find('option[selected=selected]');
                if ($selected->is('[value]')) {
                    return $selected->attr('value');
                } else {
                    return $selected->text();
                }
            } elseif ($this->eq(0)->is('textarea')) {
                return $this->eq(0)->markup();
            } else {
                return $this->eq(0)->attr('value');
            }
        } else {
            $_val = null;
            foreach ($this->stack(1) as $node) {
                $node = \MicroweberPackages\PhpQuery\PhpQuery::pq($node, $this->getDocumentID());
                if (is_array($val) && in_array($node->attr('type'), array('checkbox', 'radio'))) {
                    $isChecked = in_array($node->attr('value'), $val)
                        || in_array($node->attr('name'), $val);
                    if ($isChecked) {
                        $node->attr('checked', 'checked');
                    } else {
                        $node->removeAttr('checked');
                    }
                } elseif ($node->get(0)->tagName == 'select') {
                    if (!isset($_val)) {
                        $_val = array();
                        if (!is_array($val)) {
                            $_val = array((string) $val);
                        } else {
                            foreach ($val as $v) {
                                $_val[] = $v;
                            }
                        }
                    }
                    foreach ($node['option']->stack(1) as $option) {
                        $option = PhpQuery::pq($option, $this->getDocumentID());
                        $selected = false;
                        // XXX: workaround for string comparsion, see issue #96
                        // http://code.google.com/p/phpquery/issues/detail?id=96
                        $selected = is_null($option->attr('value')) ? in_array($option->markup(), $_val) : in_array($option->attr('value'), $_val);
                        //						$optionValue = $option->attr('value');
                        //						$optionText = $option->text();
                        //						$optionTextLenght = mb_strlen($optionText);
                        //						foreach($_val as $v)
                        //							if ($optionValue == $v)
                        //								$selected = true;
                        //							else if ($optionText == $v && $optionTextLenght == mb_strlen($v))
                        //								$selected = true;
                        if ($selected) {
                            $option->attr('selected', 'selected');
                        } else {
                            $option->removeAttr('selected');
                        }
                    }
                } elseif ($node->get(0)->tagName == 'textarea') {
                    $node->markup($val);
                } else {
                    $node->attr('value', $val);
                }
            }
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function andSelf()
    {
        if ($this->previous) {
            $this->elements = array_merge($this->elements, $this->previous->elements);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function addClass($className)
    {
        if (!$className) {
            return $this;
        }
        foreach ($this->stack(1) as $node) {
            if (!$this->is(".$className", $node)) {
                $node->setAttribute(
                    'class', trim($node->getAttribute('class').' '.$className)
                );
            }
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function addClassPHP($className)
    {
        foreach ($this->stack(1) as $node) {
            $classes = $node->getAttribute('class');
            $newValue = $classes ? $classes.' <'.'?php '.$className.' ?'.'>' : '<'.'?php '.$className.' ?'.'>';
            $node->setAttribute('class', $newValue);
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param string $className
     *
     * @return bool
     */
    public function hasClass($className)
    {
        foreach ($this->stack(1) as $node) {
            if ($this->is(".$className", $node)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function removeClass($className)
    {
        foreach ($this->stack(1) as $node) {
            $classes = explode(' ', $node->getAttribute('class'));
            if (in_array($className, $classes)) {
                $classes = array_diff($classes, array($className));
                if ($classes) {
                    $node->setAttribute('class', implode(' ', $classes));
                } else {
                    $node->removeAttribute('class');
                }
            }
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function toggleClass($className)
    {
        foreach ($this->stack(1) as $node) {
            if ($this->is('.'.$className, $node)) {
                $this->removeClass($className);
            } else {
                $this->addClass($className);
            }
        }

        return $this;
    }

    /**
     * Proper name without underscore (just ->empty()) also works.
     *
     * Removes all child nodes from the set of matched elements.
     *
     * Example:
     * PhpQuery::pq("p")._empty()
     *
     * HTML:
     * <p>Hello, <span>Person</span> <a href="#">and person</a></p>
     *
     * Result:
     * [ <p></p> ]
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function _empty()
    {
        foreach ($this->stack(1) as $node) {
            // thx to 'dave at dgx dot cz'
            $node->nodeValue = '';
        }

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param array|string $callback Expects $node as first param, $index as second
     * @param array        $scope    External variables passed to callback. Use compact('varName1', 'varName2'...) and extract($scope)
     * @param array        $arg1     Will ba passed as third and futher args to callback.
     * @param array        $arg2     Will ba passed as fourth and futher args to callback, and so on...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function each($callback, $param1 = null, $param2 = null, $param3 = null)
    {
        $paramStructure = null;
        if (func_num_args() > 1) {
            $paramStructure = func_get_args();
            $paramStructure = array_slice($paramStructure, 1);
        }
        foreach ($this->elements as $v) {
            PhpQuery::callbackRun($callback, array($v), $paramStructure);
        }

        return $this;
    }

    /**
     * Run callback on actual object.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function callback($callback, $param1 = null, $param2 = null, $param3 = null)
    {
        $params = func_get_args();
        $params[0] = $this;
        PhpQuery::callbackRun($callback, $params);

        return $this;
    }

    /**
     * Enter description here...
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     *
     * @todo add $scope and $args as in each() ???
     */
    public function map($callback, $param1 = null, $param2 = null, $param3 = null)
    {
        //		$stack = array();
        ////		foreach($this->newInstance() as $node) {
        //		foreach($this->newInstance() as $node) {
        //			$result = call_user_func($callback, $node);
        //			if ($result)
        //				$stack[] = $result;
        //		}
        $params = func_get_args();
        array_unshift($params, $this->elements);

        return $this->newInstance(
            call_user_func_array(array('phpQuery', 'map'), $params)
        //			PhpQuery::map($this->elements, $callback)
        );
    }

    /**
     * Enter description here...
     *
     * @param <type> $key
     * @param <type> $value
     */
    public function data($key, $value = null)
    {
        if (!isset($value)) {
            // TODO? implement specific jQuery behavior od returning parent values
            // is child which we look up doesn't exist
            return PhpQuery::data($this->get(0), $key, $value, $this->getDocumentID());
        } else {
            foreach ($this as $node) {
                PhpQuery::data($node, $key, $value, $this->getDocumentID());
            }

            return $this;
        }
    }

    /**
     * Enter description here...
     *
     * @param <type> $key
     */
    public function removeData($key)
    {
        foreach ($this as $node) {
            PhpQuery::removeData($node, $key, $this->getDocumentID());
        }

        return $this;
    }

    // INTERFACE IMPLEMENTATIONS
    // ITERATOR INTERFACE
    /**
     */
    public function rewind() :void
    {
        $this->debug('iterating foreach');
        //		PhpQuery::selectDocument($this->getDocumentID());
        $this->elementsBackup = $this->elements;
        $this->elementsInterator = $this->elements;
        $this->valid = isset($this->elements[0]) ? 1 : 0;
        // 		$this->elements = $this->valid
        // 			? array($this->elements[0])
        // 			: array();
        $this->current = 0;
    }

    /**
     */
    public function current() :mixed
    {
        return $this->elementsInterator[$this->current];
    }

    /**
     */
    public function key() :mixed
    {
        return $this->current;
    }

    /**
     * Double-function method.
     *
     * First: main iterator interface method.
     * Second: Returning next sibling, alias for _next().
     *
     * Proper functionality is choosed automagicaly.
     *
     * @see PhpQueryObject::_next()
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    #[ReturnTypeWillChange]
    public function next($cssSelector = null)
    {
        //		if ($cssSelector || $this->valid)
        //			return $this->_next($cssSelector);
        $this->valid = isset($this->elementsInterator[$this->current + 1]) ? true : false;
        if (!$this->valid && $this->elementsInterator) {
            $this->elementsInterator = null;
        } elseif ($this->valid) {
            ++$this->current;
        } else {
            return $this->_next($cssSelector);
        }
    }

    /**
     */
    public function valid() :bool
    {
        return $this->valid;
    }

    // ITERATOR INTERFACE END
    // ARRAYACCESS INTERFACE
    /**
     */
    public function offsetExists(mixed $offset) :bool
    {
        return $this->find($offset)->size() > 0;
    }

    /**
     */
    public function offsetGet(mixed $offset) :mixed
    {
        return $this->find($offset);
    }

    /**
     */
    public function offsetSet(mixed $offset, mixed $value) :void
    {
        //		$this->find($offset)->replaceWith($value);
        $this->find($offset)->html($value);
    }

    /**
     */
    public function offsetUnset(mixed $offset):void
    {
        // empty
        throw new Exception("Can't do unset, use array interface only for calling queries and replacing HTML.");
    }

    // ARRAYACCESS INTERFACE END
    /**
     * Returns node's XPath.
     *
     * @param unknown_type $oneNode
     *
     * @return string
     * @TODO use native getNodePath is avaible
     */
    protected function getNodeXpath($oneNode = null, $namespace = null)
    {
        $return = array();
        $loop = $oneNode ? array($oneNode) : $this->elements;
        //		if ($namespace)
        //			$namespace .= ':';
        foreach ($loop as $node) {
            if ($node instanceof \DOMDocument) {
                $return[] = '';
                continue;
            }
            $xpath = array();
            while (!($node instanceof \DOMDocument)) {
                $i = 1;
                $sibling = $node;
                while ($sibling->previousSibling) {
                    $sibling = $sibling->previousSibling;
                    $isElement = $sibling instanceof \DOMElement;
                    if ($isElement && $sibling->tagName == $node->tagName) {
                        $i++;
                    }
                }
                $xpath[] = $this->isXML() ? "*[local-name()='{$node->tagName}'][{$i}]" : "{$node->tagName}[{$i}]";
                $node = $node->parentNode;
            }
            $xpath = implode('/', array_reverse($xpath));
            $return[] = '/'.$xpath;
        }

        return $oneNode ? $return[0] : $return;
    }

    // HELPERS
    public function whois($oneNode = null)
    {
        $return = array();
        $loop = $oneNode ? array($oneNode) : $this->elements;
        foreach ($loop as $node) {
            if (isset($node->tagName)) {
                $tag = in_array($node->tagName, array('php', 'js')) ? strtoupper($node->tagName) : $node->tagName;
                $return[] = $tag
                    .($node->getAttribute('id') ? '#'.$node->getAttribute('id') : '')
                    .($node->getAttribute('class') ? '.'.implode('.', split(' ', $node->getAttribute('class'))) : '')
                    .($node->getAttribute('name') ? '[name="'.$node->getAttribute('name').'"]' : '')
                    .($node->getAttribute('value') && strpos($node->getAttribute('value'), '<'.'?php') === false ? '[value="'.substr(str_replace("\n", '', $node->getAttribute('value')), 0, 15).'"]' : '')
                    .($node->getAttribute('value') && strpos($node->getAttribute('value'), '<'.'?php') !== false ? '[value=PHP]' : '')
                    .($node->getAttribute('selected') ? '[selected]' : '')
                    .($node->getAttribute('checked') ? '[checked]' : '')
                ;
            } elseif ($node instanceof DOMTEXT) {
                if (trim($node->textContent)) {
                    $return[] = 'Text:'.substr(str_replace("\n", ' ', $node->textContent), 0, 15);
                }
            } else {
            }
        }

        return $oneNode && isset($return[0]) ? $return[0] : $return;
    }

    /**
     * Dump htmlOuter and preserve chain. Usefull for debugging.
     *
     * @return PhpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function dump()
    {
        echo 'DUMP #'.(PhpQuery::$dumpCount++).' ';
        $debug = PhpQuery::$debug;
        PhpQuery::$debug = false;
        //		print __FILE__.':'.__LINE__."\n";
        var_dump($this->htmlOuter());

        return $this;
    }

    public function dumpWhois()
    {
        echo 'DUMP #'.(PhpQuery::$dumpCount++).' ';
        $debug = PhpQuery::$debug;
        PhpQuery::$debug = false;
        //		print __FILE__.':'.__LINE__."\n";
        var_dump('whois', $this->whois());
        PhpQuery::$debug = $debug;

        return $this;
    }

    public function dumpLength()
    {
        echo 'DUMP #'.(PhpQuery::$dumpCount++).' ';
        $debug = PhpQuery::$debug;
        PhpQuery::$debug = false;
        //		print __FILE__.':'.__LINE__."\n";
        var_dump('length', $this->length());
        PhpQuery::$debug = $debug;

        return $this;
    }

    public function dumpTree($html = true, $title = true)
    {
        $output = $title ? 'DUMP #'.(PhpQuery::$dumpCount++)." \n" : '';
        $debug = PhpQuery::$debug;
        PhpQuery::$debug = false;
        foreach ($this->stack() as $node) {
            $output .= $this->__dumpTree($node);
        }
        PhpQuery::$debug = $debug;
        echo $html ? nl2br(str_replace(' ', '&nbsp;', $output)) : $output;

        return $this;
    }

    private function __dumpTree($node, $intend = 0)
    {
        $whois = $this->whois($node);
        $return = '';
        if ($whois) {
            $return .= str_repeat(' - ', $intend).$whois."\n";
        }
        if (isset($node->childNodes)) {
            foreach ($node->childNodes as $chNode) {
                $return .= $this->__dumpTree($chNode, $intend + 1);
            }
        }

        return $return;
    }

    /**
     * Dump htmlOuter and stop script execution. Usefull for debugging.
     */
    public function dumpDie()
    {
        echo __FILE__.':'.__LINE__;
        var_dump($this->htmlOuter());
        die();
    }
}
