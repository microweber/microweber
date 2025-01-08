<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2018 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.github.io/scssphp
 */

namespace Leafo\ScssPhp;

use Leafo\ScssPhp\Base\Range;
use Leafo\ScssPhp\Block;
use Leafo\ScssPhp\Cache;
use Leafo\ScssPhp\Colors;
use Leafo\ScssPhp\Compiler\Environment;
use Leafo\ScssPhp\Exception\CompilerException;
use Leafo\ScssPhp\Formatter\OutputBlock;
use Leafo\ScssPhp\Node;
use Leafo\ScssPhp\SourceMap\SourceMapGenerator;
use Leafo\ScssPhp\Type;
use Leafo\ScssPhp\Parser;
use Leafo\ScssPhp\Util;

/**
 * The scss compiler and parser.
 *
 * Converting SCSS to CSS is a three stage process. The incoming file is parsed
 * by `Parser` into a syntax tree, then it is compiled into another tree
 * representing the CSS structure by `Compiler`. The CSS tree is fed into a
 * formatter, like `Formatter` which then outputs CSS as a string.
 *
 * During the first compile, all values are *reduced*, which means that their
 * types are brought to the lowest form before being dump as strings. This
 * handles math equations, variable dereferences, and the like.
 *
 * The `compile` function of `Compiler` is the entry point.
 *
 * In summary:
 *
 * The `Compiler` class creates an instance of the parser, feeds it SCSS code,
 * then transforms the resulting tree to a CSS tree. This class also holds the
 * evaluation context, such as all available mixins and variables at any given
 * time.
 *
 * The `Parser` class is only concerned with parsing its input.
 *
 * The `Formatter` takes a CSS tree, and dumps it to a formatted string,
 * handling things like indentation.
 */

/**
 * SCSS compiler
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class Compiler
{
    const LINE_COMMENTS = 1;
    const DEBUG_INFO    = 2;

    const WITH_RULE     = 1;
    const WITH_MEDIA    = 2;
    const WITH_SUPPORTS = 4;
    const WITH_ALL      = 7;

    const SOURCE_MAP_NONE   = 0;
    const SOURCE_MAP_INLINE = 1;
    const SOURCE_MAP_FILE   = 2;

    /**
     * @var array
     */
    static protected $operatorNames = [
        '+'   => 'add',
        '-'   => 'sub',
        '*'   => 'mul',
        '/'   => 'div',
        '%'   => 'mod',

        '=='  => 'eq',
        '!='  => 'neq',
        '<'   => 'lt',
        '>'   => 'gt',

        '<='  => 'lte',
        '>='  => 'gte',
        '<=>' => 'cmp',
    ];

    /**
     * @var array
     */
    static protected $namespaces = [
        'special'  => '%',
        'mixin'    => '@',
        'function' => '^',
    ];

    static public $true         = [Type::T_KEYWORD, 'true'];
    static public $false        = [Type::T_KEYWORD, 'false'];
    static public $null         = [Type::T_NULL];
    static public $nullString   = [Type::T_STRING, '', []];
    static public $defaultValue = [Type::T_KEYWORD, ''];
    static public $selfSelector = [Type::T_SELF];
    static public $emptyList    = [Type::T_LIST, '', []];
    static public $emptyMap     = [Type::T_MAP, [], []];
    static public $emptyString  = [Type::T_STRING, '"', []];
    static public $with         = [Type::T_KEYWORD, 'with'];
    static public $without      = [Type::T_KEYWORD, 'without'];

    protected $importPaths = [''];
    protected $importCache = [];
    protected $importedFiles = [];
    protected $userFunctions = [];
    protected $registeredVars = [];
    protected $registeredFeatures = [
        'extend-selector-pseudoclass' => false,
        'at-error'                    => true,
        'units-level-3'               => false,
        'global-variable-shadowing'   => false,
    ];

    protected $encoding = null;
    protected $lineNumberStyle = null;

    protected $sourceMap = self::SOURCE_MAP_NONE;
    protected $sourceMapOptions = [];

    /**
     * @var string|\Leafo\ScssPhp\Formatter
     */
    protected $formatter = 'Leafo\ScssPhp\Formatter\Nested';

    protected $rootEnv;
    protected $rootBlock;

    /**
     * @var \Leafo\ScssPhp\Compiler\Environment
     */
    protected $env;
    protected $scope;
    protected $storeEnv;
    protected $charsetSeen;
    protected $sourceNames;

    protected $cache;

    protected $indentLevel;
    protected $extends;
    protected $extendsMap;
    protected $parsedFiles;
    protected $parser;
    protected $sourceIndex;
    protected $sourceLine;
    protected $sourceColumn;
    protected $stderr;
    protected $shouldEvaluate;
    protected $ignoreErrors;

    protected $callStack = [];

    /**
     * Constructor
     */
    public function __construct($cacheOptions = null)
    {
        $this->parsedFiles = [];
        $this->sourceNames = [];

        if ($cacheOptions) {
            $this->cache = new Cache($cacheOptions);
        }
    }

    public function getCompileOptions()
    {
        $options = [
            'importPaths'        => $this->importPaths,
            'registeredVars'     => $this->registeredVars,
            'registeredFeatures' => $this->registeredFeatures,
            'encoding'           => $this->encoding,
            'sourceMap'          => serialize($this->sourceMap),
            'sourceMapOptions'   => $this->sourceMapOptions,
            'formatter'          => $this->formatter,
        ];

        return $options;
    }

    /**
     * Compile scss
     *
     * @api
     *
     * @param string $code
     * @param string $path
     *
     * @return string
     */
    public function compile($code, $path = null)
    {
        if ($this->cache) {
            $cacheKey = ($path ? $path : "(stdin)") . ":" . md5($code);
            $compileOptions = $this->getCompileOptions();
            $cache = $this->cache->getCache("compile", $cacheKey, $compileOptions);

            if (is_array($cache)
                && isset($cache['dependencies'])
                && isset($cache['out'])
            ) {
                // check if any dependency file changed before accepting the cache
                foreach ($cache['dependencies'] as $file => $mtime) {
                    if (! file_exists($file)
                        || filemtime($file) !== $mtime
                    ) {
                        unset($cache);
                        break;
                    }
                }

                if (isset($cache)) {
                    return $cache['out'];
                }
            }
        }


        $this->indentLevel    = -1;
        $this->extends        = [];
        $this->extendsMap     = [];
        $this->sourceIndex    = null;
        $this->sourceLine     = null;
        $this->sourceColumn   = null;
        $this->env            = null;
        $this->scope          = null;
        $this->storeEnv       = null;
        $this->charsetSeen    = null;
        $this->shouldEvaluate = null;
        $this->stderr         = fopen('php://stderr', 'w');

        $this->parser = $this->parserFactory($path);
        $tree = $this->parser->parse($code);
        $this->parser = null;

        $this->formatter = new $this->formatter();
        $this->rootBlock = null;
        $this->rootEnv   = $this->pushEnv($tree);

        $this->injectVariables($this->registeredVars);
        $this->compileRoot($tree);
        $this->popEnv();

        $sourceMapGenerator = null;

        if ($this->sourceMap) {
            if (is_object($this->sourceMap) && $this->sourceMap instanceof SourceMapGenerator) {
                $sourceMapGenerator = $this->sourceMap;
                $this->sourceMap = self::SOURCE_MAP_FILE;
            } elseif ($this->sourceMap !== self::SOURCE_MAP_NONE) {
                $sourceMapGenerator = new SourceMapGenerator($this->sourceMapOptions);
            }
        }

        $out = $this->formatter->format($this->scope, $sourceMapGenerator);

        if (! empty($out) && $this->sourceMap && $this->sourceMap !== self::SOURCE_MAP_NONE) {
            $sourceMap    = $sourceMapGenerator->generateJson();
            $sourceMapUrl = null;

            switch ($this->sourceMap) {
                case self::SOURCE_MAP_INLINE:
                    $sourceMapUrl = sprintf('data:application/json,%s', Util::encodeURIComponent($sourceMap));
                    break;

                case self::SOURCE_MAP_FILE:
                    $sourceMapUrl = $sourceMapGenerator->saveMap($sourceMap);
                    break;
            }

            $out .= sprintf('/*# sourceMappingURL=%s */', $sourceMapUrl);
        }

        if ($this->cache && isset($cacheKey) && isset($compileOptions)) {
            $v = [
                'dependencies' => $this->getParsedFiles(),
                'out' => &$out,
            ];

            $this->cache->setCache("compile", $cacheKey, $v, $compileOptions);
        }

        return $out;
    }

    /**
     * Instantiate parser
     *
     * @param string $path
     *
     * @return \Leafo\ScssPhp\Parser
     */
    protected function parserFactory($path)
    {
        $parser = new Parser($path, count($this->sourceNames), $this->encoding, $this->cache);

        $this->sourceNames[] = $path;
        $this->addParsedFile($path);

        return $parser;
    }

    /**
     * Is self extend?
     *
     * @param array $target
     * @param array $origin
     *
     * @return boolean
     */
    protected function isSelfExtend($target, $origin)
    {
        foreach ($origin as $sel) {
            if (in_array($target, $sel)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Push extends
     *
     * @param array     $target
     * @param array     $origin
     * @param \stdClass $block
     */
    protected function pushExtends($target, $origin, $block)
    {
        if ($this->isSelfExtend($target, $origin)) {
            return;
        }

        $i = count($this->extends);
        $this->extends[] = [$target, $origin, $block];

        foreach ($target as $part) {
            if (isset($this->extendsMap[$part])) {
                $this->extendsMap[$part][] = $i;
            } else {
                $this->extendsMap[$part] = [$i];
            }
        }
    }

    /**
     * Make output block
     *
     * @param string $type
     * @param array  $selectors
     *
     * @return \Leafo\ScssPhp\Formatter\OutputBlock
     */
    protected function makeOutputBlock($type, $selectors = null)
    {
        $out = new OutputBlock;
        $out->type         = $type;
        $out->lines        = [];
        $out->children     = [];
        $out->parent       = $this->scope;
        $out->selectors    = $selectors;
        $out->depth        = $this->env->depth;

        if ($this->env->block instanceof Block) {
            $out->sourceName   = $this->env->block->sourceName;
            $out->sourceLine   = $this->env->block->sourceLine;
            $out->sourceColumn = $this->env->block->sourceColumn;
        } else {
            $out->sourceName   = null;
            $out->sourceLine   = null;
            $out->sourceColumn = null;
        }

        return $out;
    }

    /**
     * Compile root
     *
     * @param \Leafo\ScssPhp\Block $rootBlock
     */
    protected function compileRoot(Block $rootBlock)
    {
        $this->rootBlock = $this->scope = $this->makeOutputBlock(Type::T_ROOT);

        $this->compileChildrenNoReturn($rootBlock->children, $this->scope);
        $this->flattenSelectors($this->scope);
        $this->missingSelectors();
    }

    /**
     * Report missing selectors
     */
    protected function missingSelectors()
    {
        foreach ($this->extends as $extend) {
            if (isset($extend[3])) {
                continue;
            }

            list($target, $origin, $block) = $extend;

            // ignore if !optional
            if ($block[2]) {
                continue;
            }

            $target = implode(' ', $target);
            $origin = $this->collapseSelectors($origin);

            $this->sourceLine = $block[Parser::SOURCE_LINE];
            $this->throwError("\"$origin\" failed to @extend \"$target\". The selector \"$target\" was not found.");
        }
    }

    /**
     * Flatten selectors
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $block
     * @param string                               $parentKey
     */
    protected function flattenSelectors(OutputBlock $block, $parentKey = null)
    {
        if ($block->selectors) {
            $selectors = [];

            foreach ($block->selectors as $s) {
                $selectors[] = $s;

                if (! is_array($s)) {
                    continue;
                }

                // check extends
                if (! empty($this->extendsMap)) {
                    $this->matchExtends($s, $selectors);

                    // remove duplicates
                    array_walk($selectors, function (&$value) {
                        $value = serialize($value);
                    });

                    $selectors = array_unique($selectors);

                    array_walk($selectors, function (&$value) {
                        $value = unserialize($value);
                    });
                }
            }

            $block->selectors = [];
            $placeholderSelector = false;

            foreach ($selectors as $selector) {
                if ($this->hasSelectorPlaceholder($selector)) {
                    $placeholderSelector = true;
                    continue;
                }

                $block->selectors[] = $this->compileSelector($selector);
            }

            if ($placeholderSelector && 0 === count($block->selectors) && null !== $parentKey) {
                unset($block->parent->children[$parentKey]);

                return;
            }
        }

        foreach ($block->children as $key => $child) {
            $this->flattenSelectors($child, $key);
        }
    }

    /**
     * Glue parts of :not( or :nth-child( ... that are in general splitted in selectors parts
     *
     * @param array $parts
     *
     * @return array
     */
    protected function glueFunctionSelectors($parts)
    {
        $new = [];

        foreach ($parts as $part) {
            if (is_array($part)) {
                $part = $this->glueFunctionSelectors($part);
                $new[] = $part;
            } else {
                // a selector part finishing with a ) is the last part of a :not( or :nth-child(
                // and need to be joined to this
                if (count($new) && is_string($new[count($new) - 1])
                    && strlen($part) && substr($part, -1) === ')' && strpos($part, '(') === false
                ) {
                    $new[count($new) - 1] .= $part;
                } else {
                    $new[] = $part;
                }
            }
        }

        return $new;
    }

    /**
     * Match extends
     *
     * @param array   $selector
     * @param array   $out
     * @param integer $from
     * @param boolean $initial
     */
    protected function matchExtends($selector, &$out, $from = 0, $initial = true)
    {
        static $partsPile = [];

        $selector = $this->glueFunctionSelectors($selector);

        foreach ($selector as $i => $part) {
            if ($i < $from) {
                continue;
            }

            // check that we are not building an infinite loop of extensions
            // if the new part is just including a previous part don't try to extend anymore
            if (count($part) > 1) {
                foreach ($partsPile as $previousPart) {
                    if (! count(array_diff($previousPart, $part))) {
                        continue 2;
                    }
                }
            }

            if ($this->matchExtendsSingle($part, $origin)) {
                $partsPile[] = $part;
                $after       = array_slice($selector, $i + 1);
                $before      = array_slice($selector, 0, $i);

                list($before, $nonBreakableBefore) = $this->extractRelationshipFromFragment($before);

                foreach ($origin as $new) {
                    $k = 0;

                    // remove shared parts
                    if (count($new) > 1) {
                        while ($k < $i && isset($new[$k]) && $selector[$k] === $new[$k]) {
                            $k++;
                        }
                    }

                    $replacement = [];
                    $tempReplacement = $k > 0 ? array_slice($new, $k) : $new;

                    for ($l = count($tempReplacement) - 1; $l >= 0; $l--) {
                        $slice = [];

                        foreach ($tempReplacement[$l] as $chunk) {
                            if (! in_array($chunk, $slice)) {
                                $slice[] = $chunk;
                            }
                        }

                        array_unshift($replacement, $slice);

                        if (! $this->isImmediateRelationshipCombinator(end($slice))) {
                            break;
                        }
                    }

                    $afterBefore = $l != 0 ? array_slice($tempReplacement, 0, $l) : [];

                    // Merge shared direct relationships.
                    $mergedBefore = $this->mergeDirectRelationships($afterBefore, $nonBreakableBefore);

                    $result = array_merge(
                        $before,
                        $mergedBefore,
                        $replacement,
                        $after
                    );

                    if ($result === $selector) {
                        continue;
                    }

                    $out[] = $result;

                    // recursively check for more matches
                    $startRecurseFrom = count($before) + min(count($nonBreakableBefore), count($mergedBefore));
                    $this->matchExtends($result, $out, $startRecurseFrom, false);

                    // selector sequence merging
                    if (! empty($before) && count($new) > 1) {
                        $preSharedParts = $k > 0 ? array_slice($before, 0, $k) : [];
                        $postSharedParts = $k > 0 ? array_slice($before, $k) : $before;

                        list($betweenSharedParts, $nonBreakable2) = $this->extractRelationshipFromFragment($afterBefore);

                        $result2 = array_merge(
                            $preSharedParts,
                            $betweenSharedParts,
                            $postSharedParts,
                            $nonBreakable2,
                            $nonBreakableBefore,
                            $replacement,
                            $after
                        );

                        $out[] = $result2;
                    }
                }

                array_pop($partsPile);
            }
        }
    }

    /**
     * Match extends single
     *
     * @param array $rawSingle
     * @param array $outOrigin
     *
     * @return boolean
     */
    protected function matchExtendsSingle($rawSingle, &$outOrigin)
    {
        $counts = [];
        $single = [];

        // simple usual cases, no need to do the whole trick
        if (in_array($rawSingle, [['>'],['+'],['~']])) {
            return false;
        }

        foreach ($rawSingle as $part) {
            // matches Number
            if (! is_string($part)) {
                return false;
            }

            if (! preg_match('/^[\[.:#%]/', $part) && count($single)) {
                $single[count($single) - 1] .= $part;
            } else {
                $single[] = $part;
            }
        }

        $extendingDecoratedTag = false;

        if (count($single) > 1) {
            $matches = null;
            $extendingDecoratedTag = preg_match('/^[a-z0-9]+$/i', $single[0], $matches) ? $matches[0] : false;
        }

        foreach ($single as $part) {
            if (isset($this->extendsMap[$part])) {
                foreach ($this->extendsMap[$part] as $idx) {
                    $counts[$idx] = isset($counts[$idx]) ? $counts[$idx] + 1 : 1;
                }
            }
        }

        $outOrigin = [];
        $found = false;

        foreach ($counts as $idx => $count) {
            list($target, $origin, /* $block */) = $this->extends[$idx];

            $origin = $this->glueFunctionSelectors($origin);

            // check count
            if ($count !== count($target)) {
                continue;
            }

            $this->extends[$idx][3] = true;

            $rem = array_diff($single, $target);

            foreach ($origin as $j => $new) {
                // prevent infinite loop when target extends itself
                if ($this->isSelfExtend($single, $origin)) {
                    return false;
                }

                $replacement = end($new);

                // Extending a decorated tag with another tag is not possible.
                if ($extendingDecoratedTag && $replacement[0] != $extendingDecoratedTag &&
                    preg_match('/^[a-z0-9]+$/i', $replacement[0])
                ) {
                    unset($origin[$j]);
                    continue;
                }

                $combined = $this->combineSelectorSingle($replacement, $rem);

                if (count(array_diff($combined, $origin[$j][count($origin[$j]) - 1]))) {
                    $origin[$j][count($origin[$j]) - 1] = $combined;
                }
            }

            $outOrigin = array_merge($outOrigin, $origin);

            $found = true;
        }

        return $found;
    }

    /**
     * Extract a relationship from the fragment.
     *
     * When extracting the last portion of a selector we will be left with a
     * fragment which may end with a direction relationship combinator. This
     * method will extract the relationship fragment and return it along side
     * the rest.
     *
     * @param array $fragment The selector fragment maybe ending with a direction relationship combinator.
     *
     * @return array The selector without the relationship fragment if any, the relationship fragment.
     */
    protected function extractRelationshipFromFragment(array $fragment)
    {
        $parents = [];
        $children = [];
        $j = $i = count($fragment);

        for (;;) {
            $children = $j != $i ? array_slice($fragment, $j, $i - $j) : [];
            $parents = array_slice($fragment, 0, $j);
            $slice = end($parents);

            if (empty($slice) || ! $this->isImmediateRelationshipCombinator($slice[0])) {
                break;
            }

            $j -= 2;
        }

        return [$parents, $children];
    }

    /**
     * Combine selector single
     *
     * @param array $base
     * @param array $other
     *
     * @return array
     */
    protected function combineSelectorSingle($base, $other)
    {
        $tag = [];
        $out = [];
        $wasTag = true;

        foreach ([$base, $other] as $single) {
            foreach ($single as $part) {
                if (preg_match('/^[\[.:#]/', $part)) {
                    $out[] = $part;
                    $wasTag = false;
                } elseif (preg_match('/^[^_-]/', $part)) {
                    $tag[] = $part;
                    $wasTag = true;
                } elseif ($wasTag) {
                    $tag[count($tag) - 1] .= $part;
                } else {
                    $out[count($out) - 1] .= $part;
                }
            }
        }

        if (count($tag)) {
            array_unshift($out, $tag[0]);
        }

        return $out;
    }

    /**
     * Compile media
     *
     * @param \Leafo\ScssPhp\Block $media
     */
    protected function compileMedia(Block $media)
    {
        $this->pushEnv($media);

        $mediaQueries = $this->compileMediaQuery($this->multiplyMedia($this->env));

        if (! empty($mediaQueries) && $mediaQueries) {
            $previousScope = $this->scope;
            $parentScope = $this->mediaParent($this->scope);

            foreach ($mediaQueries as $mediaQuery) {
                $this->scope = $this->makeOutputBlock(Type::T_MEDIA, [$mediaQuery]);

                $parentScope->children[] = $this->scope;
                $parentScope = $this->scope;
            }

            // top level properties in a media cause it to be wrapped
            $needsWrap = false;

            foreach ($media->children as $child) {
                $type = $child[0];

                if ($type !== Type::T_BLOCK &&
                    $type !== Type::T_MEDIA &&
                    $type !== Type::T_DIRECTIVE &&
                    $type !== Type::T_IMPORT
                ) {
                    $needsWrap = true;
                    break;
                }
            }

            if ($needsWrap) {
                $wrapped = new Block;
                $wrapped->sourceName = $media->sourceName;
                $wrapped->sourceIndex = $media->sourceIndex;
                $wrapped->sourceLine = $media->sourceLine;
                $wrapped->sourceColumn = $media->sourceColumn;
                $wrapped->selectors = [];
                $wrapped->comments = [];
                $wrapped->parent = $media;
                $wrapped->children = $media->children;

                $media->children = [[Type::T_BLOCK, $wrapped]];
                if (isset($this->lineNumberStyle)) {
                    $annotation = $this->makeOutputBlock(Type::T_COMMENT);
                    $annotation->depth = 0;

                    $file = $this->sourceNames[$media->sourceIndex];
                    $line = $media->sourceLine;

                    switch ($this->lineNumberStyle) {
                        case static::LINE_COMMENTS:
                            $annotation->lines[] = '/* line ' . $line
                                                 . ($file ? ', ' . $file : '')
                                                 . ' */';
                            break;

                        case static::DEBUG_INFO:
                            $annotation->lines[] = '@media -sass-debug-info{'
                                                 . ($file ? 'filename{font-family:"' . $file . '"}' : '')
                                                 . 'line{font-family:' . $line . '}}';
                            break;
                    }

                    $this->scope->children[] = $annotation;
                }
            }

            $this->compileChildrenNoReturn($media->children, $this->scope);

            $this->scope = $previousScope;
        }

        $this->popEnv();
    }

    /**
     * Media parent
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $scope
     *
     * @return \Leafo\ScssPhp\Formatter\OutputBlock
     */
    protected function mediaParent(OutputBlock $scope)
    {
        while (! empty($scope->parent)) {
            if (! empty($scope->type) && $scope->type !== Type::T_MEDIA) {
                break;
            }

            $scope = $scope->parent;
        }

        return $scope;
    }

    /**
     * Compile directive
     *
     * @param \Leafo\ScssPhp\Block $block
     */
    protected function compileDirective(Block $block)
    {
        $s = '@' . $block->name;

        if (! empty($block->value)) {
            $s .= ' ' . $this->compileValue($block->value);
        }

        if ($block->name === 'keyframes' || substr($block->name, -10) === '-keyframes') {
            $this->compileKeyframeBlock($block, [$s]);
        } else {
            $this->compileNestedBlock($block, [$s]);
        }
    }

    /**
     * Compile at-root
     *
     * @param \Leafo\ScssPhp\Block $block
     */
    protected function compileAtRoot(Block $block)
    {
        $env     = $this->pushEnv($block);
        $envs    = $this->compactEnv($env);
        $without = isset($block->with) ? $this->compileWith($block->with) : static::WITH_RULE;

        // wrap inline selector
        if ($block->selector) {
            $wrapped = new Block;
            $wrapped->sourceName   = $block->sourceName;
            $wrapped->sourceIndex  = $block->sourceIndex;
            $wrapped->sourceLine   = $block->sourceLine;
            $wrapped->sourceColumn = $block->sourceColumn;
            $wrapped->selectors    = $block->selector;
            $wrapped->comments     = [];
            $wrapped->parent       = $block;
            $wrapped->children     = $block->children;
            $wrapped->selfParent   = $block->selfParent;

            $block->children = [[Type::T_BLOCK, $wrapped]];
            $block->selector = null;
        }

        $selfParent = $block->selfParent;

        if (! $block->selfParent->selectors && isset($block->parent) && $block->parent &&
            isset($block->parent->selectors) && $block->parent->selectors
        ) {
            $selfParent = $block->parent;
        }

        $this->env = $this->filterWithout($envs, $without);

        $saveScope   = $this->scope;
        $this->scope = $this->filterScopeWithout($saveScope, $without);

        // propagate selfParent to the children where they still can be useful
        $this->compileChildrenNoReturn($block->children, $this->scope, $selfParent);

        $this->scope = $this->completeScope($this->scope, $saveScope);
        $this->scope = $saveScope;
        $this->env   = $this->extractEnv($envs);

        $this->popEnv();
    }

    /**
     * Filter at-root scope depending of with/without option
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $scope
     * @param mixed                                $without
     *
     * @return mixed
     */
    protected function filterScopeWithout($scope, $without)
    {
        $filteredScopes = [];

        if ($scope->type === TYPE::T_ROOT) {
            return $scope;
        }

        // start from the root
        while ($scope->parent && $scope->parent->type !== TYPE::T_ROOT) {
            $scope = $scope->parent;
        }

        for (;;) {
            if (! $scope) {
                break;
            }

            if (! $this->isWithout($without, $scope)) {
                $s = clone $scope;
                $s->children = [];
                $s->lines = [];
                $s->parent = null;

                if ($s->type !== Type::T_MEDIA && $s->type !== Type::T_DIRECTIVE) {
                    $s->selectors = [];
                }

                $filteredScopes[] = $s;
            }

            if ($scope->children) {
                $scope = end($scope->children);
            } else {
                $scope = null;
            }
        }

        if (! count($filteredScopes)) {
            return $this->rootBlock;
        }

        $newScope = array_shift($filteredScopes);
        $newScope->parent = $this->rootBlock;

        $this->rootBlock->children[] = $newScope;

        $p = &$newScope;

        while (count($filteredScopes)) {
            $s = array_shift($filteredScopes);
            $s->parent = $p;
            $p->children[] = &$s;
            $p = $s;
        }

        return $newScope;
    }

    /**
     * found missing selector from a at-root compilation in the previous scope
     * (if at-root is just enclosing a property, the selector is in the parent tree)
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $scope
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $previousScope
     *
     * @return mixed
     */
    protected function completeScope($scope, $previousScope)
    {
        if (! $scope->type && (! $scope->selectors || ! count($scope->selectors)) && count($scope->lines)) {
            $scope->selectors = $this->findScopeSelectors($previousScope, $scope->depth);
        }

        if ($scope->children) {
            foreach ($scope->children as $k => $c) {
                $scope->children[$k] = $this->completeScope($c, $previousScope);
            }
        }

        return $scope;
    }

    /**
     * Find a selector by the depth node in the scope
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $scope
     * @param integer                              $depth
     *
     * @return array
     */
    protected function findScopeSelectors($scope, $depth)
    {
        if ($scope->depth === $depth && $scope->selectors) {
            return $scope->selectors;
        }

        if ($scope->children) {
            foreach (array_reverse($scope->children) as $c) {
                if ($s = $this->findScopeSelectors($c, $depth)) {
                    return $s;
                }
            }
        }

        return [];
    }

    /**
     * Compile @at-root's with: inclusion / without: exclusion into filter flags
     *
     * @param array $with
     *
     * @return integer
     */
    protected function compileWith($with)
    {
        static $mapping = [
            'rule'     => self::WITH_RULE,
            'media'    => self::WITH_MEDIA,
            'supports' => self::WITH_SUPPORTS,
            'all'      => self::WITH_ALL,
        ];

        // exclude selectors by default
        $without = static::WITH_RULE;

        if ($this->libMapHasKey([$with, static::$with])) {
            $without = static::WITH_ALL;

            $list = $this->coerceList($this->libMapGet([$with, static::$with]));

            foreach ($list[2] as $item) {
                $keyword = $this->compileStringContent($this->coerceString($item));

                if (array_key_exists($keyword, $mapping)) {
                    $without &= ~($mapping[$keyword]);
                }
            }
        }

        if ($this->libMapHasKey([$with, static::$without])) {
            $without = 0;

            $list = $this->coerceList($this->libMapGet([$with, static::$without]));

            foreach ($list[2] as $item) {
                $keyword = $this->compileStringContent($this->coerceString($item));

                if (array_key_exists($keyword, $mapping)) {
                    $without |= $mapping[$keyword];
                }
            }
        }

        return $without;
    }

    /**
     * Filter env stack
     *
     * @param array   $envs
     * @param integer $without
     *
     * @return \Leafo\ScssPhp\Compiler\Environment
     */
    protected function filterWithout($envs, $without)
    {
        $filtered = [];

        foreach ($envs as $e) {
            if ($e->block && $this->isWithout($without, $e->block)) {
                $ec = clone $e;
                $ec->block = null;
                $ec->selectors = [];
                $filtered[] = $ec;
            } else {
                $filtered[] = $e;
            }
        }

        return $this->extractEnv($filtered);
    }

    /**
     * Filter WITH rules
     *
     * @param integer                                                   $without
     * @param \Leafo\ScssPhp\Block|\Leafo\ScssPhp\Formatter\OutputBlock $block
     *
     * @return boolean
     */
    protected function isWithout($without, $block)
    {
        if (isset($block->type)) {
            if ($block->type === Type::T_MEDIA) {
                return ($without & static::WITH_MEDIA) ? true : false;
            }

            if ($block->type === Type::T_DIRECTIVE) {
                if (isset($block->name) && $block->name === 'supports') {
                    return ($without & static::WITH_SUPPORTS) ? true : false;
                }

                if (isset($block->selectors) && strpos(serialize($block->selectors), '@supports') !== false) {
                    return ($without & static::WITH_SUPPORTS) ? true : false;
                }
            }
        }

        if ((($without & static::WITH_RULE) && isset($block->selectors))) {
            return true;
        }

        return false;
    }

    /**
     * Compile keyframe block
     *
     * @param \Leafo\ScssPhp\Block $block
     * @param array                $selectors
     */
    protected function compileKeyframeBlock(Block $block, $selectors)
    {
        $env = $this->pushEnv($block);

        $envs = $this->compactEnv($env);

        $this->env = $this->extractEnv(array_filter($envs, function (Environment $e) {
            return ! isset($e->block->selectors);
        }));

        $this->scope = $this->makeOutputBlock($block->type, $selectors);
        $this->scope->depth = 1;
        $this->scope->parent->children[] = $this->scope;

        $this->compileChildrenNoReturn($block->children, $this->scope);

        $this->scope = $this->scope->parent;
        $this->env   = $this->extractEnv($envs);

        $this->popEnv();
    }

    /**
     * Compile nested block
     *
     * @param \Leafo\ScssPhp\Block $block
     * @param array                $selectors
     */
    protected function compileNestedBlock(Block $block, $selectors)
    {
        $this->pushEnv($block);

        $this->scope = $this->makeOutputBlock($block->type, $selectors);
        $this->scope->parent->children[] = $this->scope;

        // wrap assign children in a block
        // except for @font-face
        if ($block->type !== Type::T_DIRECTIVE || $block->name !== "font-face") {
            // need wrapping?
            $needWrapping = false;

            foreach ($block->children as $child) {
                if ($child[0] === Type::T_ASSIGN) {
                    $needWrapping = true;
                    break;
                }
            }

            if ($needWrapping) {
                $wrapped = new Block;
                $wrapped->sourceName = $block->sourceName;
                $wrapped->sourceIndex = $block->sourceIndex;
                $wrapped->sourceLine = $block->sourceLine;
                $wrapped->sourceColumn = $block->sourceColumn;
                $wrapped->selectors = [];
                $wrapped->comments = [];
                $wrapped->parent = $block;
                $wrapped->children = $block->children;
                $wrapped->selfParent = $block->selfParent;

                $block->children = [[Type::T_BLOCK, $wrapped]];
            }
        }

        $this->compileChildrenNoReturn($block->children, $this->scope);

        $this->scope = $this->scope->parent;

        $this->popEnv();
    }

    /**
     * Recursively compiles a block.
     *
     * A block is analogous to a CSS block in most cases. A single SCSS document
     * is encapsulated in a block when parsed, but it does not have parent tags
     * so all of its children appear on the root level when compiled.
     *
     * Blocks are made up of selectors and children.
     *
     * The children of a block are just all the blocks that are defined within.
     *
     * Compiling the block involves pushing a fresh environment on the stack,
     * and iterating through the props, compiling each one.
     *
     * @see Compiler::compileChild()
     *
     * @param \Leafo\ScssPhp\Block $block
     */
    protected function compileBlock(Block $block)
    {
        $env = $this->pushEnv($block);
        $env->selectors = $this->evalSelectors($block->selectors);

        $out = $this->makeOutputBlock(null);

        if (isset($this->lineNumberStyle) && count($env->selectors) && count($block->children)) {
            $annotation = $this->makeOutputBlock(Type::T_COMMENT);
            $annotation->depth = 0;

            $file = $this->sourceNames[$block->sourceIndex];
            $line = $block->sourceLine;

            switch ($this->lineNumberStyle) {
                case static::LINE_COMMENTS:
                    $annotation->lines[] = '/* line ' . $line
                                         . ($file ? ', ' . $file : '')
                                         . ' */';
                    break;

                case static::DEBUG_INFO:
                    $annotation->lines[] = '@media -sass-debug-info{'
                                         . ($file ? 'filename{font-family:"' . $file . '"}' : '')
                                         . 'line{font-family:' . $line . '}}';
                    break;
            }

            $this->scope->children[] = $annotation;
        }

        $this->scope->children[] = $out;

        if (count($block->children)) {
            $out->selectors = $this->multiplySelectors($env, $block->selfParent);

            // propagate selfParent to the children where they still can be useful
            $selfParentSelectors = null;

            if (isset($block->selfParent->selectors)) {
                $selfParentSelectors = $block->selfParent->selectors;
                $block->selfParent->selectors = $out->selectors;
            }

            $this->compileChildrenNoReturn($block->children, $out, $block->selfParent);

            // and revert for the following childs of the same block
            if ($selfParentSelectors) {
                $block->selfParent->selectors = $selfParentSelectors;
            }
        }

        $this->formatter->stripSemicolon($out->lines);

        $this->popEnv();
    }

    /**
     * Compile root level comment
     *
     * @param array $block
     */
    protected function compileComment($block)
    {
        $out = $this->makeOutputBlock(Type::T_COMMENT);
        $out->lines[] = is_string($block[1]) ? $block[1] : $this->compileValue($block[1]);

        $this->scope->children[] = $out;
    }

    /**
     * Evaluate selectors
     *
     * @param array $selectors
     *
     * @return array
     */
    protected function evalSelectors($selectors)
    {
        $this->shouldEvaluate = false;

        $selectors = array_map([$this, 'evalSelector'], $selectors);

        // after evaluating interpolates, we might need a second pass
        if ($this->shouldEvaluate) {
            $selectors = $this->revertSelfSelector($selectors);
            $buffer = $this->collapseSelectors($selectors);
            $parser = $this->parserFactory(__METHOD__);

            if ($parser->parseSelector($buffer, $newSelectors)) {
                $selectors = array_map([$this, 'evalSelector'], $newSelectors);
            }
        }

        return $selectors;
    }

    /**
     * Evaluate selector
     *
     * @param array $selector
     *
     * @return array
     */
    protected function evalSelector($selector)
    {
        return array_map([$this, 'evalSelectorPart'], $selector);
    }

    /**
     * Evaluate selector part; replaces all the interpolates, stripping quotes
     *
     * @param array $part
     *
     * @return array
     */
    protected function evalSelectorPart($part)
    {
        foreach ($part as &$p) {
            if (is_array($p) && ($p[0] === Type::T_INTERPOLATE || $p[0] === Type::T_STRING)) {
                $p = $this->compileValue($p);

                // force re-evaluation
                if (strpos($p, '&') !== false || strpos($p, ',') !== false) {
                    $this->shouldEvaluate = true;
                }
            } elseif (is_string($p) && strlen($p) >= 2 &&
                ($first = $p[0]) && ($first === '"' || $first === "'") &&
                substr($p, -1) === $first
            ) {
                $p = substr($p, 1, -1);
            }
        }

        return $this->flattenSelectorSingle($part);
    }

    /**
     * Collapse selectors
     *
     * @param array   $selectors
     * @param boolean $selectorFormat
     *   if false return a collapsed string
     *   if true return an array description of a structured selector
     *
     * @return string
     */
    protected function collapseSelectors($selectors, $selectorFormat = false)
    {
        $parts = [];

        foreach ($selectors as $selector) {
            $output = [];
            $glueNext = false;

            foreach ($selector as $node) {
                $compound = '';

                array_walk_recursive(
                    $node,
                    function ($value, $key) use (&$compound) {
                        $compound .= $value;
                    }
                );

                if ($selectorFormat && $this->isImmediateRelationshipCombinator($compound)) {
                    if (count($output)) {
                        $output[count($output) - 1] .= ' ' . $compound;
                    } else {
                        $output[] = $compound;
                    }
                    $glueNext = true;
                } elseif ($glueNext) {
                    $output[count($output) - 1] .= ' ' . $compound;
                    $glueNext = false;
                } else {
                    $output[] = $compound;
                }
            }

            if ($selectorFormat) {
                foreach ($output as &$o) {
                    $o = [Type::T_STRING, '', [$o]];
                }
                $output = [Type::T_LIST, ' ', $output];
            } else {
                $output = implode(' ', $output);
            }

            $parts[] = $output;
        }

        if ($selectorFormat) {
            $parts = [Type::T_LIST, ',', $parts];
        } else {
            $parts = implode(', ', $parts);
        }

        return $parts;
    }

    /**
     * Parse down the selector and revert [self] to "&" before a reparsing
     *
     * @param array $selectors
     *
     * @return array
     */
    protected function revertSelfSelector($selectors)
    {
        foreach ($selectors as &$part) {
            if (is_array($part)) {
                if ($part === [Type::T_SELF]) {
                    $part = '&';
                } else {
                    $part = $this->revertSelfSelector($part);
                }
            }
        }

        return $selectors;
    }

    /**
     * Flatten selector single; joins together .classes and #ids
     *
     * @param array $single
     *
     * @return array
     */
    protected function flattenSelectorSingle($single)
    {
        $joined = [];

        foreach ($single as $part) {
            if (empty($joined) ||
                ! is_string($part) ||
                preg_match('/[\[.:#%]/', $part)
            ) {
                $joined[] = $part;
                continue;
            }

            if (is_array(end($joined))) {
                $joined[] = $part;
            } else {
                $joined[count($joined) - 1] .= $part;
            }
        }

        return $joined;
    }

    /**
     * Compile selector to string; self(&) should have been replaced by now
     *
     * @param string|array $selector
     *
     * @return string
     */
    protected function compileSelector($selector)
    {
        if (! is_array($selector)) {
            return $selector; // media and the like
        }

        return implode(
            ' ',
            array_map(
                [$this, 'compileSelectorPart'],
                $selector
            )
        );
    }

    /**
     * Compile selector part
     *
     * @param array $piece
     *
     * @return string
     */
    protected function compileSelectorPart($piece)
    {
        foreach ($piece as &$p) {
            if (! is_array($p)) {
                continue;
            }

            switch ($p[0]) {
                case Type::T_SELF:
                    $p = '&';
                    break;

                default:
                    $p = $this->compileValue($p);
                    break;
            }
        }

        return implode($piece);
    }

    /**
     * Has selector placeholder?
     *
     * @param array $selector
     *
     * @return boolean
     */
    protected function hasSelectorPlaceholder($selector)
    {
        if (! is_array($selector)) {
            return false;
        }

        foreach ($selector as $parts) {
            foreach ($parts as $part) {
                if (strlen($part) && '%' === $part[0]) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function pushCallStack($name = '')
    {
        $this->callStack[] = [
          'n' => $name,
          Parser::SOURCE_INDEX => $this->sourceIndex,
          Parser::SOURCE_LINE => $this->sourceLine,
          Parser::SOURCE_COLUMN => $this->sourceColumn
        ];

        // infinite calling loop
        if (count($this->callStack) > 25000) {
            // not displayed but you can var_dump it to deep debug
            $msg = $this->callStackMessage(true, 100);
            $msg = "Infinite calling loop";
            $this->throwError($msg);
        }
    }

    protected function popCallStack()
    {
        array_pop($this->callStack);
    }

    /**
     * Compile children and return result
     *
     * @param array                                $stms
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $out
     * @param string                               $traceName
     *
     * @return array|null
     */
    protected function compileChildren($stms, OutputBlock $out, $traceName = '')
    {
        $this->pushCallStack($traceName);

        foreach ($stms as $stm) {
            $ret = $this->compileChild($stm, $out);

            if (isset($ret)) {
                return $ret;
            }
        }

        $this->popCallStack();

        return null;
    }

    /**
     * Compile children and throw exception if unexpected @return
     *
     * @param array                                $stms
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $out
     * @param \Leafo\ScssPhp\Block                 $selfParent
     * @param string                               $traceName
     *
     * @throws \Exception
     */
    protected function compileChildrenNoReturn($stms, OutputBlock $out, $selfParent = null, $traceName = '')
    {
        $this->pushCallStack($traceName);

        foreach ($stms as $stm) {
            if ($selfParent && isset($stm[1]) && is_object($stm[1]) && $stm[1] instanceof Block) {
                $stm[1]->selfParent = $selfParent;
                $ret = $this->compileChild($stm, $out);
                $stm[1]->selfParent = null;
            } elseif ($selfParent && $stm[0] === TYPE::T_INCLUDE) {
                $stm['selfParent'] = $selfParent;
                $ret = $this->compileChild($stm, $out);
                unset($stm['selfParent']);
            } else {
                $ret = $this->compileChild($stm, $out);
            }

            if (isset($ret)) {
                $this->throwError('@return may only be used within a function');

                return;
            }
        }

        $this->popCallStack();
    }


    /**
     * evaluate media query : compile internal value keeping the structure inchanged
     *
     * @param array $queryList
     *
     * @return array
     */
    protected function evaluateMediaQuery($queryList)
    {
        foreach ($queryList as $kql => $query) {
            foreach ($query as $kq => $q) {
                for ($i = 1; $i < count($q); $i++) {
                    $value = $this->compileValue($q[$i]);

                    // the parser had no mean to know if media type or expression if it was an interpolation
                    if ($q[0] == Type::T_MEDIA_TYPE &&
                        (strpos($value, '(') !== false ||
                        strpos($value, ')') !== false ||
                        strpos($value, ':') !== false)
                    ) {
                        $queryList[$kql][$kq][0] = Type::T_MEDIA_EXPRESSION;

                        if (strpos($value, 'and') !== false) {
                            $values = explode('and', $value);
                            $value = trim(array_pop($values));

                            while ($v = trim(array_pop($values))) {
                                $type = Type::T_MEDIA_EXPRESSION;

                                if (strpos($v, '(') === false &&
                                    strpos($v, ')') === false &&
                                    strpos($v, ':') === false
                                ) {
                                    $type = Type::T_MEDIA_TYPE;
                                }

                                if (substr($v, 0, 1) === '(' && substr($v, -1) === ')') {
                                    $v = substr($v, 1, -1);
                                }

                                $queryList[$kql][] = [$type,[Type::T_KEYWORD, $v]];
                            }
                        }

                        if (substr($value, 0, 1) === '(' && substr($value, -1) === ')') {
                            $value = substr($value, 1, -1);
                        }
                    }

                    $queryList[$kql][$kq][$i] = [Type::T_KEYWORD, $value];
                }
            }
        }

        return $queryList;
    }

    /**
     * Compile media query
     *
     * @param array $queryList
     *
     * @return array
     */
    protected function compileMediaQuery($queryList)
    {
        $start = '@media ';
        $default = trim($start);
        $out = [];
        $current = "";

        foreach ($queryList as $query) {
            $type = null;
            $parts = [];

            $mediaTypeOnly = true;

            foreach ($query as $q) {
                if ($q[0] !== Type::T_MEDIA_TYPE) {
                    $mediaTypeOnly = false;
                    break;
                }
            }

            foreach ($query as $q) {
                switch ($q[0]) {
                    case Type::T_MEDIA_TYPE:
                        $newType = array_map([$this, 'compileValue'], array_slice($q, 1));
                        // combining not and anything else than media type is too risky and should be avoided
                        if (! $mediaTypeOnly) {
                            if (in_array(Type::T_NOT, $newType) || ($type && in_array(Type::T_NOT, $type) )) {
                                if ($type) {
                                    array_unshift($parts, implode(' ', array_filter($type)));
                                }

                                if (! empty($parts)) {
                                    if (strlen($current)) {
                                        $current .= $this->formatter->tagSeparator;
                                    }

                                    $current .= implode(' and ', $parts);
                                }

                                if ($current) {
                                    $out[] = $start . $current;
                                }

                                $current = "";
                                $type = null;
                                $parts = [];
                            }
                        }

                        if ($newType === ['all'] && $default) {
                            $default = $start . 'all';
                        }

                        // all can be safely ignored and mixed with whatever else
                        if ($newType !== ['all']) {
                            if ($type) {
                                $type = $this->mergeMediaTypes($type, $newType);

                                if (empty($type)) {
                                    // merge failed : ignore this query that is not valid, skip to the next one
                                    $parts = [];
                                    $default = ''; // if everything fail, no @media at all
                                    continue 3;
                                }
                            } else {
                                $type = $newType;
                            }
                        }
                        break;

                    case Type::T_MEDIA_EXPRESSION:
                        if (isset($q[2])) {
                            $parts[] = '('
                                . $this->compileValue($q[1])
                                . $this->formatter->assignSeparator
                                . $this->compileValue($q[2])
                                . ')';
                        } else {
                            $parts[] = '('
                                . $this->compileValue($q[1])
                                . ')';
                        }
                        break;

                    case Type::T_MEDIA_VALUE:
                        $parts[] = $this->compileValue($q[1]);
                        break;
                }
            }

            if ($type) {
                array_unshift($parts, implode(' ', array_filter($type)));
            }

            if (! empty($parts)) {
                if (strlen($current)) {
                    $current .= $this->formatter->tagSeparator;
                }

                $current .= implode(' and ', $parts);
            }
        }

        if ($current) {
            $out[] = $start . $current;
        }

        // no @media type except all, and no conflict?
        if (! $out && $default) {
            $out[] = $default;
        }

        return $out;
    }

    /**
     * Merge direct relationships between selectors
     *
     * @param array $selectors1
     * @param array $selectors2
     *
     * @return array
     */
    protected function mergeDirectRelationships($selectors1, $selectors2)
    {
        if (empty($selectors1) || empty($selectors2)) {
            return array_merge($selectors1, $selectors2);
        }

        $part1 = end($selectors1);
        $part2 = end($selectors2);

        if (! $this->isImmediateRelationshipCombinator($part1[0]) && $part1 !== $part2) {
            return array_merge($selectors1, $selectors2);
        }

        $merged = [];

        do {
            $part1 = array_pop($selectors1);
            $part2 = array_pop($selectors2);

            if (! $this->isImmediateRelationshipCombinator($part1[0]) && $part1 !== $part2) {
                if ($this->isImmediateRelationshipCombinator(reset($merged)[0])) {
                    array_unshift($merged, [$part1[0] . $part2[0]]);
                    $merged = array_merge($selectors1, $selectors2, $merged);
                } else {
                    $merged = array_merge($selectors1, [$part1], $selectors2, [$part2], $merged);
                }

                break;
            }

            array_unshift($merged, $part1);
        } while (! empty($selectors1) && ! empty($selectors2));

        return $merged;
    }

    /**
     * Merge media types
     *
     * @param array $type1
     * @param array $type2
     *
     * @return array|null
     */
    protected function mergeMediaTypes($type1, $type2)
    {
        if (empty($type1)) {
            return $type2;
        }

        if (empty($type2)) {
            return $type1;
        }

        $m1 = '';
        $t1 = '';

        if (count($type1) > 1) {
            $m1= strtolower($type1[0]);
            $t1= strtolower($type1[1]);
        } else {
            $t1 = strtolower($type1[0]);
        }

        $m2 = '';
        $t2 = '';

        if (count($type2) > 1) {
            $m2 = strtolower($type2[0]);
            $t2 = strtolower($type2[1]);
        } else {
            $t2 = strtolower($type2[0]);
        }

        if (($m1 === Type::T_NOT) ^ ($m2 === Type::T_NOT)) {
            if ($t1 === $t2) {
                return null;
            }

            return [
                $m1 === Type::T_NOT ? $m2 : $m1,
                $m1 === Type::T_NOT ? $t2 : $t1,
            ];
        }

        if ($m1 === Type::T_NOT && $m2 === Type::T_NOT) {
            // CSS has no way of representing "neither screen nor print"
            if ($t1 !== $t2) {
                return null;
            }

            return [Type::T_NOT, $t1];
        }

        if ($t1 !== $t2) {
            return null;
        }

        // t1 == t2, neither m1 nor m2 are "not"
        return [empty($m1)? $m2 : $m1, $t1];
    }

    /**
     * Compile import; returns true if the value was something that could be imported
     *
     * @param array                                $rawPath
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $out
     * @param boolean                              $once
     *
     * @return boolean
     */
    protected function compileImport($rawPath, OutputBlock $out, $once = false)
    {
        if ($rawPath[0] === Type::T_STRING) {
            $path = $this->compileStringContent($rawPath);

            if ($path = $this->findImport($path)) {
                if (! $once || ! in_array($path, $this->importedFiles)) {
                    $this->importFile($path, $out);
                    $this->importedFiles[] = $path;
                }

                return true;
            }

            return false;
        }

        if ($rawPath[0] === Type::T_LIST) {
            // handle a list of strings
            if (count($rawPath[2]) === 0) {
                return false;
            }

            foreach ($rawPath[2] as $path) {
                if ($path[0] !== Type::T_STRING) {
                    return false;
                }
            }

            foreach ($rawPath[2] as $path) {
                $this->compileImport($path, $out);
            }

            return true;
        }

        return false;
    }

    /**
     * Compile child; returns a value to halt execution
     *
     * @param array                                $child
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $out
     *
     * @return array
     */
    protected function compileChild($child, OutputBlock $out)
    {
        if (isset($child[Parser::SOURCE_LINE])) {
            $this->sourceIndex = isset($child[Parser::SOURCE_INDEX]) ? $child[Parser::SOURCE_INDEX] : null;
            $this->sourceLine = isset($child[Parser::SOURCE_LINE]) ? $child[Parser::SOURCE_LINE] : -1;
            $this->sourceColumn = isset($child[Parser::SOURCE_COLUMN]) ? $child[Parser::SOURCE_COLUMN] : -1;
        } elseif (is_array($child) && isset($child[1]->sourceLine)) {
            $this->sourceIndex = $child[1]->sourceIndex;
            $this->sourceLine = $child[1]->sourceLine;
            $this->sourceColumn = $child[1]->sourceColumn;
        } elseif (! empty($out->sourceLine) && ! empty($out->sourceName)) {
            $this->sourceLine = $out->sourceLine;
            $this->sourceIndex = array_search($out->sourceName, $this->sourceNames);

            if ($this->sourceIndex === false) {
                $this->sourceIndex = null;
            }
        }

        switch ($child[0]) {
            case Type::T_SCSSPHP_IMPORT_ONCE:
                $rawPath = $this->reduce($child[1]);

                if (! $this->compileImport($rawPath, $out, true)) {
                    $out->lines[] = '@import ' . $this->compileValue($rawPath) . ';';
                }
                break;

            case Type::T_IMPORT:
                $rawPath = $this->reduce($child[1]);

                if (! $this->compileImport($rawPath, $out)) {
                    $out->lines[] = '@import ' . $this->compileValue($rawPath) . ';';
                }
                break;

            case Type::T_DIRECTIVE:
                $this->compileDirective($child[1]);
                break;

            case Type::T_AT_ROOT:
                $this->compileAtRoot($child[1]);
                break;

            case Type::T_MEDIA:
                $this->compileMedia($child[1]);
                break;

            case Type::T_BLOCK:
                $this->compileBlock($child[1]);
                break;

            case Type::T_CHARSET:
                if (! $this->charsetSeen) {
                    $this->charsetSeen = true;

                    $out->lines[] = '@charset ' . $this->compileValue($child[1]) . ';';
                }
                break;

            case Type::T_ASSIGN:
                list(, $name, $value) = $child;

                if ($name[0] === Type::T_VARIABLE) {
                    $flags = isset($child[3]) ? $child[3] : [];
                    $isDefault = in_array('!default', $flags);
                    $isGlobal = in_array('!global', $flags);

                    if ($isGlobal) {
                        $this->set($name[1], $this->reduce($value), false, $this->rootEnv, $value);
                        break;
                    }

                    $shouldSet = $isDefault &&
                        (($result = $this->get($name[1], false)) === null
                        || $result === static::$null);

                    if (! $isDefault || $shouldSet) {
                        $this->set($name[1], $this->reduce($value), true, null, $value);
                    }
                    break;
                }

                $compiledName = $this->compileValue($name);

                // handle shorthand syntax: size / line-height
                if ($compiledName === 'font' || $compiledName === 'grid-row' || $compiledName === 'grid-column') {
                    if ($value[0] === Type::T_VARIABLE) {
                        // if the font value comes from variable, the content is already reduced
                        // (i.e., formulas were already calculated), so we need the original unreduced value
                        $value = $this->get($value[1], true, null, true);
                    }

                    $fontValue=&$value;

                    if ($value[0] === Type::T_LIST && $value[1]==',') {
                        // this is the case if more than one font is given: example: "font: 400 1em/1.3 arial,helvetica"
                        // we need to handle the first list element
                        $fontValue=&$value[2][0];
                    }

                    if ($fontValue[0] === Type::T_EXPRESSION && $fontValue[1] === '/') {
                        $fontValue = $this->expToString($fontValue);
                    } elseif ($fontValue[0] === Type::T_LIST) {
                        foreach ($fontValue[2] as &$item) {
                            if ($item[0] === Type::T_EXPRESSION && $item[1] === '/') {
                                $item = $this->expToString($item);
                            }
                        }
                    }
                }

                // if the value reduces to null from something else then
                // the property should be discarded
                if ($value[0] !== Type::T_NULL) {
                    $value = $this->reduce($value);

                    if ($value[0] === Type::T_NULL || $value === static::$nullString) {
                        break;
                    }
                }

                $compiledValue = $this->compileValue($value);

                $out->lines[] = $this->formatter->property(
                    $compiledName,
                    $compiledValue
                );
                break;

            case Type::T_COMMENT:
                if ($out->type === Type::T_ROOT) {
                    $this->compileComment($child);
                    break;
                }

                $out->lines[] = $child[1];
                break;

            case Type::T_MIXIN:
            case Type::T_FUNCTION:
                list(, $block) = $child;

                $this->set(static::$namespaces[$block->type] . $block->name, $block);
                break;

            case Type::T_EXTEND:
                foreach ($child[1] as $sel) {
                    $results = $this->evalSelectors([$sel]);

                    foreach ($results as $result) {
                        // only use the first one
                        $result = current($result);

                        $this->pushExtends($result, $out->selectors, $child);
                    }
                }
                break;

            case Type::T_IF:
                list(, $if) = $child;

                if ($this->isTruthy($this->reduce($if->cond, true))) {
                    return $this->compileChildren($if->children, $out);
                }

                foreach ($if->cases as $case) {
                    if ($case->type === Type::T_ELSE ||
                        $case->type === Type::T_ELSEIF && $this->isTruthy($this->reduce($case->cond))
                    ) {
                        return $this->compileChildren($case->children, $out);
                    }
                }
                break;

            case Type::T_EACH:
                list(, $each) = $child;

                $list = $this->coerceList($this->reduce($each->list));

                $this->pushEnv();

                foreach ($list[2] as $item) {
                    if (count($each->vars) === 1) {
                        $this->set($each->vars[0], $item, true);
                    } else {
                        list(,, $values) = $this->coerceList($item);

                        foreach ($each->vars as $i => $var) {
                            $this->set($var, isset($values[$i]) ? $values[$i] : static::$null, true);
                        }
                    }

                    $ret = $this->compileChildren($each->children, $out);

                    if ($ret) {
                        if ($ret[0] !== Type::T_CONTROL) {
                            $this->popEnv();

                            return $ret;
                        }

                        if ($ret[1]) {
                            break;
                        }
                    }
                }

                $this->popEnv();
                break;

            case Type::T_WHILE:
                list(, $while) = $child;

                while ($this->isTruthy($this->reduce($while->cond, true))) {
                    $ret = $this->compileChildren($while->children, $out);

                    if ($ret) {
                        if ($ret[0] !== Type::T_CONTROL) {
                            return $ret;
                        }

                        if ($ret[1]) {
                            break;
                        }
                    }
                }
                break;

            case Type::T_FOR:
                list(, $for) = $child;

                $start = $this->reduce($for->start, true);
                $end   = $this->reduce($for->end, true);

                if (! ($start[2] == $end[2] || $end->unitless())) {
                    $this->throwError('Incompatible units: "%s" and "%s".', $start->unitStr(), $end->unitStr());

                    break;
                }

                $unit  = $start[2];
                $start = $start[1];
                $end   = $end[1];

                $d = $start < $end ? 1 : -1;

                for (;;) {
                    if ((! $for->until && $start - $d == $end) ||
                        ($for->until && $start == $end)
                    ) {
                        break;
                    }

                    $this->set($for->var, new Node\Number($start, $unit));
                    $start += $d;

                    $ret = $this->compileChildren($for->children, $out);

                    if ($ret) {
                        if ($ret[0] !== Type::T_CONTROL) {
                            return $ret;
                        }

                        if ($ret[1]) {
                            break;
                        }
                    }
                }
                break;

            case Type::T_BREAK:
                return [Type::T_CONTROL, true];

            case Type::T_CONTINUE:
                return [Type::T_CONTROL, false];

            case Type::T_RETURN:
                return $this->reduce($child[1], true);

            case Type::T_NESTED_PROPERTY:
                list(, $prop) = $child;

                $prefixed = [];
                $prefix = $this->compileValue($prop->prefix) . '-';

                foreach ($prop->children as $child) {
                    switch ($child[0]) {
                        case Type::T_ASSIGN:
                            array_unshift($child[1][2], $prefix);
                            break;

                        case Type::T_NESTED_PROPERTY:
                            array_unshift($child[1]->prefix[2], $prefix);
                            break;
                    }

                    $prefixed[] = $child;
                }

                $this->compileChildrenNoReturn($prefixed, $out);
                break;

            case Type::T_INCLUDE:
                // including a mixin
                list(, $name, $argValues, $content) = $child;

                $mixin = $this->get(static::$namespaces['mixin'] . $name, false);

                if (! $mixin) {
                    $this->throwError("Undefined mixin $name");
                    break;
                }

                $callingScope = $this->getStoreEnv();

                // push scope, apply args
                $this->pushEnv();
                $this->env->depth--;

                $storeEnv = $this->storeEnv;
                $this->storeEnv = $this->env;

                // Find the parent selectors in the env to be able to know what '&' refers to in the mixin
                // and assign this fake parent to childs
                $selfParent = null;

                if (isset($child['selfParent']) && isset($child['selfParent']->selectors)) {
                    $selfParent = $child['selfParent'];
                } else {
                    $parentSelectors = $this->multiplySelectors($this->env);

                    if ($parentSelectors) {
                        $parent = new Block();
                        $parent->selectors = $parentSelectors;

                        foreach ($mixin->children as $k => $child) {
                            if (isset($child[1]) && is_object($child[1]) && $child[1] instanceof Block) {
                                $mixin->children[$k][1]->parent = $parent;
                            }
                        }
                    }
                }

                // clone the stored content to not have its scope spoiled by a further call to the same mixin
                // i.e., recursive @include of the same mixin
                if (isset($content)) {
                    $copyContent = clone $content;
                    $copyContent->scope = $callingScope;

                    $this->setRaw(static::$namespaces['special'] . 'content', $copyContent, $this->env);
                }

                if (isset($mixin->args)) {
                    $this->applyArguments($mixin->args, $argValues);
                }

                $this->env->marker = 'mixin';

                $this->compileChildrenNoReturn($mixin->children, $out, $selfParent, $this->env->marker . " " . $name);

                $this->storeEnv = $storeEnv;

                $this->popEnv();
                break;

            case Type::T_MIXIN_CONTENT:
                $env = isset($this->storeEnv) ? $this->storeEnv : $this->env;
                $content = $this->get(static::$namespaces['special'] . 'content', false, $env);

                if (! $content) {
                    $content = new \stdClass();
                    $content->scope = new \stdClass();
                    $content->children = $this->storeEnv->parent->block->children;
                    break;
                }

                $storeEnv = $this->storeEnv;
                $this->storeEnv = $content->scope;
                $this->compileChildrenNoReturn($content->children, $out);

                $this->storeEnv = $storeEnv;
                break;

            case Type::T_DEBUG:
                list(, $value) = $child;

                $fname = $this->sourceNames[$this->sourceIndex];
                $line = $this->sourceLine;
                $value = $this->compileValue($this->reduce($value, true));
                fwrite($this->stderr, "File $fname on line $line DEBUG: $value\n");
                break;

            case Type::T_WARN:
                list(, $value) = $child;

                $fname = $this->sourceNames[$this->sourceIndex];
                $line = $this->sourceLine;
                $value = $this->compileValue($this->reduce($value, true));
                fwrite($this->stderr, "File $fname on line $line WARN: $value\n");
                break;

            case Type::T_ERROR:
                list(, $value) = $child;

                $fname = $this->sourceNames[$this->sourceIndex];
                $line = $this->sourceLine;
                $value = $this->compileValue($this->reduce($value, true));
                $this->throwError("File $fname on line $line ERROR: $value\n");
                break;

            case Type::T_CONTROL:
                $this->throwError('@break/@continue not permitted in this scope');
                break;

            default:
                $this->throwError("unknown child type: $child[0]");
        }
    }

    /**
     * Reduce expression to string
     *
     * @param array $exp
     *
     * @return array
     */
    protected function expToString($exp)
    {
        list(, $op, $left, $right, /* $inParens */, $whiteLeft, $whiteRight) = $exp;

        $content = [$this->reduce($left)];

        if ($whiteLeft) {
            $content[] = ' ';
        }

        $content[] = $op;

        if ($whiteRight) {
            $content[] = ' ';
        }

        $content[] = $this->reduce($right);

        return [Type::T_STRING, '', $content];
    }

    /**
     * Is truthy?
     *
     * @param array $value
     *
     * @return boolean
     */
    protected function isTruthy($value)
    {
        return $value !== static::$false && $value !== static::$null;
    }

    /**
     * Is the value a direct relationship combinator?
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function isImmediateRelationshipCombinator($value)
    {
        return $value === '>' || $value === '+' || $value === '~';
    }

    /**
     * Should $value cause its operand to eval
     *
     * @param array $value
     *
     * @return boolean
     */
    protected function shouldEval($value)
    {
        switch ($value[0]) {
            case Type::T_EXPRESSION:
                if ($value[1] === '/') {
                    return $this->shouldEval($value[2]) || $this->shouldEval($value[3]);
                }

                // fall-thru
            case Type::T_VARIABLE:
            case Type::T_FUNCTION_CALL:
                return true;
        }

        return false;
    }

    /**
     * Reduce value
     *
     * @param array   $value
     * @param boolean $inExp
     *
     * @return array|\Leafo\ScssPhp\Node\Number
     */
    protected function reduce($value, $inExp = false)
    {

        switch ($value[0]) {
            case Type::T_EXPRESSION:
                list(, $op, $left, $right, $inParens) = $value;

                $opName = isset(static::$operatorNames[$op]) ? static::$operatorNames[$op] : $op;
                $inExp = $inExp || $this->shouldEval($left) || $this->shouldEval($right);

                $left = $this->reduce($left, true);

                if ($op !== 'and' && $op !== 'or') {
                    $right = $this->reduce($right, true);
                }

                // special case: looks like css shorthand
                if ($opName == 'div' && ! $inParens && ! $inExp && isset($right[2])
                    && (($right[0] !== Type::T_NUMBER && $right[2] != '')
                    || ($right[0] === Type::T_NUMBER && ! $right->unitless()))
                ) {
                    return $this->expToString($value);
                }

                $left = $this->coerceForExpression($left);
                $right = $this->coerceForExpression($right);

                $ltype = $left[0];
                $rtype = $right[0];

                $ucOpName = ucfirst($opName);
                $ucLType  = ucfirst($ltype);
                $ucRType  = ucfirst($rtype);

                // this tries:
                // 1. op[op name][left type][right type]
                // 2. op[left type][right type] (passing the op as first arg
                // 3. op[op name]
                $fn = "op${ucOpName}${ucLType}${ucRType}";

                if (is_callable([$this, $fn]) ||
                    (($fn = "op${ucLType}${ucRType}") &&
                        is_callable([$this, $fn]) &&
                        $passOp = true) ||
                    (($fn = "op${ucOpName}") &&
                        is_callable([$this, $fn]) &&
                        $genOp = true)
                ) {
                    $coerceUnit = false;

                    if (! isset($genOp) &&
                        $left[0] === Type::T_NUMBER && $right[0] === Type::T_NUMBER
                    ) {
                        $coerceUnit = true;

                        switch ($opName) {
                            case 'mul':
                                $targetUnit = $left[2];

                                foreach ($right[2] as $unit => $exp) {
                                    $targetUnit[$unit] = (isset($targetUnit[$unit]) ? $targetUnit[$unit] : 0) + $exp;
                                }
                                break;

                            case 'div':
                                $targetUnit = $left[2];

                                foreach ($right[2] as $unit => $exp) {
                                    $targetUnit[$unit] = (isset($targetUnit[$unit]) ? $targetUnit[$unit] : 0) - $exp;
                                }
                                break;

                            case 'mod':
                                $targetUnit = $left[2];
                                break;

                            default:
                                $targetUnit = $left->unitless() ? $right[2] : $left[2];
                        }

                        if (! $left->unitless() && ! $right->unitless()) {
                            $left = $left->normalize();
                            $right = $right->normalize();
                        }
                    }

                    $shouldEval = $inParens || $inExp;

                    if (isset($passOp)) {
                        $out = $this->$fn($op, $left, $right, $shouldEval);
                    } else {
                        $out = $this->$fn($left, $right, $shouldEval);
                    }

                    if (isset($out)) {
                        if ($coerceUnit && $out[0] === Type::T_NUMBER) {
                            $out = $out->coerce($targetUnit);
                        }

                        return $out;
                    }
                }

                return $this->expToString($value);

            case Type::T_UNARY:
                list(, $op, $exp, $inParens) = $value;

                $inExp = $inExp || $this->shouldEval($exp);
                $exp = $this->reduce($exp);

                if ($exp[0] === Type::T_NUMBER) {
                    switch ($op) {
                        case '+':
                            return new Node\Number($exp[1], $exp[2]);

                        case '-':
                            return new Node\Number(-$exp[1], $exp[2]);
                    }
                }

                if ($op === 'not') {
                    if ($inExp || $inParens) {
                        if ($exp === static::$false || $exp === static::$null) {
                            return static::$true;
                        }

                        return static::$false;
                    }

                    $op = $op . ' ';
                }

                return [Type::T_STRING, '', [$op, $exp]];

            case Type::T_VARIABLE:
                return $this->reduce($this->get($value[1]));

            case Type::T_LIST:
                foreach ($value[2] as &$item) {
                    $item = $this->reduce($item);
                }

                return $value;

            case Type::T_MAP:
                foreach ($value[1] as &$item) {
                    $item = $this->reduce($item);
                }

                foreach ($value[2] as &$item) {
                    $item = $this->reduce($item);
                }

                return $value;

            case Type::T_STRING:
                foreach ($value[2] as &$item) {
                    if (is_array($item) || $item instanceof \ArrayAccess) {
                        $item = $this->reduce($item);
                    }
                }

                return $value;

            case Type::T_INTERPOLATE:
                $value[1] = $this->reduce($value[1]);
                if ($inExp) {
                    return $value[1];
                }

                return $value;

            case Type::T_FUNCTION_CALL:
                return $this->fncall($value[1], $value[2]);

            case Type::T_SELF:
                $selfSelector = $this->multiplySelectors($this->env);
                $selfSelector = $this->collapseSelectors($selfSelector, true);
                return $selfSelector;

            default:
                return $value;
        }
    }

    /**
     * Function caller
     *
     * @param string $name
     * @param array  $argValues
     *
     * @return array|null
     */
    protected function fncall($name, $argValues)
    {
        // SCSS @function
        if ($this->callScssFunction($name, $argValues, $returnValue)) {
            return $returnValue;
        }

        // native PHP functions
        if ($this->callNativeFunction($name, $argValues, $returnValue)) {
            return $returnValue;
        }

        // for CSS functions, simply flatten the arguments into a list
        $listArgs = [];

        foreach ((array) $argValues as $arg) {
            if (empty($arg[0])) {
                $listArgs[] = $this->reduce($arg[1]);
            }
        }

        return [Type::T_FUNCTION, $name, [Type::T_LIST, ',', $listArgs]];
    }

    /**
     * Normalize name
     *
     * @param string $name
     *
     * @return string
     */
    protected function normalizeName($name)
    {
        return str_replace('-', '_', $name);
    }

    /**
     * Normalize value
     *
     * @param array $value
     *
     * @return array
     */
    public function normalizeValue($value)
    {
        $value = $this->coerceForExpression($this->reduce($value));

        switch ($value[0]) {
            case Type::T_LIST:
                $value = $this->extractInterpolation($value);

                if ($value[0] !== Type::T_LIST) {
                    return [Type::T_KEYWORD, $this->compileValue($value)];
                }

                foreach ($value[2] as $key => $item) {
                    $value[2][$key] = $this->normalizeValue($item);
                }

                return $value;

            case Type::T_STRING:
                return [$value[0], '"', [$this->compileStringContent($value)]];

            case Type::T_NUMBER:
                return $value->normalize();

            case Type::T_INTERPOLATE:
                return [Type::T_KEYWORD, $this->compileValue($value)];

            default:
                return $value;
        }
    }

    /**
     * Add numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \Leafo\ScssPhp\Node\Number
     */
    protected function opAddNumberNumber($left, $right)
    {
        return new Node\Number($left[1] + $right[1], $left[2]);
    }

    /**
     * Multiply numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \Leafo\ScssPhp\Node\Number
     */
    protected function opMulNumberNumber($left, $right)
    {
        return new Node\Number($left[1] * $right[1], $left[2]);
    }

    /**
     * Subtract numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \Leafo\ScssPhp\Node\Number
     */
    protected function opSubNumberNumber($left, $right)
    {
        return new Node\Number($left[1] - $right[1], $left[2]);
    }

    /**
     * Divide numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return array|\Leafo\ScssPhp\Node\Number
     */
    protected function opDivNumberNumber($left, $right)
    {
        if ($right[1] == 0) {
            return [Type::T_STRING, '', [$left[1] . $left[2] . '/' . $right[1] . $right[2]]];
        }

        return new Node\Number($left[1] / $right[1], $left[2]);
    }

    /**
     * Mod numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \Leafo\ScssPhp\Node\Number
     */
    protected function opModNumberNumber($left, $right)
    {
        return new Node\Number($left[1] % $right[1], $left[2]);
    }

    /**
     * Add strings
     *
     * @param array $left
     * @param array $right
     *
     * @return array|null
     */
    protected function opAdd($left, $right)
    {
        if ($strLeft = $this->coerceString($left)) {
            if ($right[0] === Type::T_STRING) {
                $right[1] = '';
            }

            $strLeft[2][] = $right;

            return $strLeft;
        }

        if ($strRight = $this->coerceString($right)) {
            if ($left[0] === Type::T_STRING) {
                $left[1] = '';
            }

            array_unshift($strRight[2], $left);

            return $strRight;
        }

        return null;
    }

    /**
     * Boolean and
     *
     * @param array   $left
     * @param array   $right
     * @param boolean $shouldEval
     *
     * @return array|null
     */
    protected function opAnd($left, $right, $shouldEval)
    {
        $truthy = ($left === static::$null || $right === static::$null) ||
                  ($left === static::$false || $left === static::$true) &&
                  ($right === static::$false || $right === static::$true);

        if (! $shouldEval) {
            if (! $truthy) {
                return null;
            }
        }

        if ($left !== static::$false && $left !== static::$null) {
            return $this->reduce($right, true);
        }

        return $left;
    }

    /**
     * Boolean or
     *
     * @param array   $left
     * @param array   $right
     * @param boolean $shouldEval
     *
     * @return array|null
     */
    protected function opOr($left, $right, $shouldEval)
    {
        $truthy = ($left === static::$null || $right === static::$null) ||
                  ($left === static::$false || $left === static::$true) &&
                  ($right === static::$false || $right === static::$true);

        if (! $shouldEval) {
            if (! $truthy) {
                return null;
            }
        }

        if ($left !== static::$false && $left !== static::$null) {
            return $left;
        }

        return $this->reduce($right, true);
    }

    /**
     * Compare colors
     *
     * @param string $op
     * @param array  $left
     * @param array  $right
     *
     * @return array
     */
    protected function opColorColor($op, $left, $right)
    {
        $out = [Type::T_COLOR];

        foreach ([1, 2, 3] as $i) {
            $lval = isset($left[$i]) ? $left[$i] : 0;
            $rval = isset($right[$i]) ? $right[$i] : 0;

            switch ($op) {
                case '+':
                    $out[] = $lval + $rval;
                    break;

                case '-':
                    $out[] = $lval - $rval;
                    break;

                case '*':
                    $out[] = $lval * $rval;
                    break;

                case '%':
                    $out[] = $lval % $rval;
                    break;

                case '/':
                    if ($rval == 0) {
                        $this->throwError("color: Can't divide by zero");
                        break 2;
                    }

                    $out[] = (int) ($lval / $rval);
                    break;

                case '==':
                    return $this->opEq($left, $right);

                case '!=':
                    return $this->opNeq($left, $right);

                default:
                    $this->throwError("color: unknown op $op");
                    break 2;
            }
        }

        if (isset($left[4])) {
            $out[4] = $left[4];
        } elseif (isset($right[4])) {
            $out[4] = $right[4];
        }

        return $this->fixColor($out);
    }

    /**
     * Compare color and number
     *
     * @param string $op
     * @param array  $left
     * @param array  $right
     *
     * @return array
     */
    protected function opColorNumber($op, $left, $right)
    {
        $value = $right[1];

        return $this->opColorColor(
            $op,
            $left,
            [Type::T_COLOR, $value, $value, $value]
        );
    }

    /**
     * Compare number and color
     *
     * @param string $op
     * @param array  $left
     * @param array  $right
     *
     * @return array
     */
    protected function opNumberColor($op, $left, $right)
    {
        $value = $left[1];

        return $this->opColorColor(
            $op,
            [Type::T_COLOR, $value, $value, $value],
            $right
        );
    }

    /**
     * Compare number1 == number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opEq($left, $right)
    {
        if (($lStr = $this->coerceString($left)) && ($rStr = $this->coerceString($right))) {
            $lStr[1] = '';
            $rStr[1] = '';

            $left = $this->compileValue($lStr);
            $right = $this->compileValue($rStr);
        }

        return $this->toBool($left === $right);
    }

    /**
     * Compare number1 != number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opNeq($left, $right)
    {
        if (($lStr = $this->coerceString($left)) && ($rStr = $this->coerceString($right))) {
            $lStr[1] = '';
            $rStr[1] = '';

            $left = $this->compileValue($lStr);
            $right = $this->compileValue($rStr);
        }

        return $this->toBool($left !== $right);
    }

    /**
     * Compare number1 >= number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opGteNumberNumber($left, $right)
    {
        return $this->toBool($left[1] >= $right[1]);
    }

    /**
     * Compare number1 > number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opGtNumberNumber($left, $right)
    {
        return $this->toBool($left[1] > $right[1]);
    }

    /**
     * Compare number1 <= number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opLteNumberNumber($left, $right)
    {
        return $this->toBool($left[1] <= $right[1]);
    }

    /**
     * Compare number1 < number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opLtNumberNumber($left, $right)
    {
        return $this->toBool($left[1] < $right[1]);
    }

    /**
     * Three-way comparison, aka spaceship operator
     *
     * @param array $left
     * @param array $right
     *
     * @return \Leafo\ScssPhp\Node\Number
     */
    protected function opCmpNumberNumber($left, $right)
    {
        $n = $left[1] - $right[1];

        return new Node\Number($n ? $n / abs($n) : 0, '');
    }

    /**
     * Cast to boolean
     *
     * @api
     *
     * @param mixed $thing
     *
     * @return array
     */
    public function toBool($thing)
    {
        return $thing ? static::$true : static::$false;
    }

    /**
     * Compiles a primitive value into a CSS property value.
     *
     * Values in scssphp are typed by being wrapped in arrays, their format is
     * typically:
     *
     *     array(type, contents [, additional_contents]*)
     *
     * The input is expected to be reduced. This function will not work on
     * things like expressions and variables.
     *
     * @api
     *
     * @param array $value
     *
     * @return string
     */
    public function compileValue($value)
    {
        $value = $this->reduce($value);

        switch ($value[0]) {
            case Type::T_KEYWORD:
                return $value[1];

            case Type::T_COLOR:
                // [1] - red component (either number for a %)
                // [2] - green component
                // [3] - blue component
                // [4] - optional alpha component
                list(, $r, $g, $b) = $value;

                $r = round($r);
                $g = round($g);
                $b = round($b);

                if (count($value) === 5 && $value[4] !== 1) { // rgba
                    $a = new Node\Number($value[4], '');

                    return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $a . ')';
                }

                $h = sprintf('#%02x%02x%02x', $r, $g, $b);

                // Converting hex color to short notation (e.g. #003399 to #039)
                if ($h[1] === $h[2] && $h[3] === $h[4] && $h[5] === $h[6]) {
                    $h = '#' . $h[1] . $h[3] . $h[5];
                }

                return $h;

            case Type::T_NUMBER:
                return $value->output($this);

            case Type::T_STRING:
                return $value[1] . $this->compileStringContent($value) . $value[1];

            case Type::T_FUNCTION:
                $args = ! empty($value[2]) ? $this->compileValue($value[2]) : '';

                return "$value[1]($args)";

            case Type::T_LIST:
                $value = $this->extractInterpolation($value);

                if ($value[0] !== Type::T_LIST) {
                    return $this->compileValue($value);
                }

                list(, $delim, $items) = $value;

                if ($delim !== ' ') {
                    $delim .= ' ';
                }

                $filtered = [];

                foreach ($items as $item) {
                    if ($item[0] === Type::T_NULL) {
                        continue;
                    }

                    $filtered[] = $this->compileValue($item);
                }

                return implode("$delim", $filtered);

            case Type::T_MAP:
                $keys = $value[1];
                $values = $value[2];
                $filtered = [];

                for ($i = 0, $s = count($keys); $i < $s; $i++) {
                    $filtered[$this->compileValue($keys[$i])] = $this->compileValue($values[$i]);
                }

                array_walk($filtered, function (&$value, $key) {
                    $value = $key . ': ' . $value;
                });

                return '(' . implode(', ', $filtered) . ')';

            case Type::T_INTERPOLATED:
                // node created by extractInterpolation
                list(, $interpolate, $left, $right) = $value;
                list(,, $whiteLeft, $whiteRight) = $interpolate;

                $left = count($left[2]) > 0 ?
                    $this->compileValue($left) . $whiteLeft : '';

                $right = count($right[2]) > 0 ?
                    $whiteRight . $this->compileValue($right) : '';

                return $left . $this->compileValue($interpolate) . $right;

            case Type::T_INTERPOLATE:
                // strip quotes if it's a string
                $reduced = $this->reduce($value[1]);

                switch ($reduced[0]) {
                    case Type::T_LIST:
                        $reduced = $this->extractInterpolation($reduced);

                        if ($reduced[0] !== Type::T_LIST) {
                            break;
                        }

                        list(, $delim, $items) = $reduced;

                        if ($delim !== ' ') {
                            $delim .= ' ';
                        }

                        $filtered = [];

                        foreach ($items as $item) {
                            if ($item[0] === Type::T_NULL) {
                                continue;
                            }

                            $temp = $this->compileValue([Type::T_KEYWORD, $item]);
                            if ($temp[0] === Type::T_STRING) {
                                $filtered[] = $this->compileStringContent($temp);
                            } elseif ($temp[0] === Type::T_KEYWORD) {
                                $filtered[] = $temp[1];
                            } else {
                                $filtered[] = $this->compileValue($temp);
                            }
                        }

                        $reduced = [Type::T_KEYWORD, implode("$delim", $filtered)];
                        break;

                    case Type::T_STRING:
                        $reduced = [Type::T_KEYWORD, $this->compileStringContent($reduced)];
                        break;

                    case Type::T_NULL:
                        $reduced = [Type::T_KEYWORD, ''];
                }

                return $this->compileValue($reduced);

            case Type::T_NULL:
                return 'null';

            default:
                $this->throwError("unknown value type: $value[0]");
        }
    }

    /**
     * Flatten list
     *
     * @param array $list
     *
     * @return string
     */
    protected function flattenList($list)
    {
        return $this->compileValue($list);
    }

    /**
     * Compile string content
     *
     * @param array $string
     *
     * @return string
     */
    protected function compileStringContent($string)
    {
        $parts = [];

        foreach ($string[2] as $part) {
            if (is_array($part) || $part instanceof \ArrayAccess) {
                $parts[] = $this->compileValue($part);
            } else {
                $parts[] = $part;
            }
        }

        return implode($parts);
    }

    /**
     * Extract interpolation; it doesn't need to be recursive, compileValue will handle that
     *
     * @param array $list
     *
     * @return array
     */
    protected function extractInterpolation($list)
    {
        $items = $list[2];

        foreach ($items as $i => $item) {
            if ($item[0] === Type::T_INTERPOLATE) {
                $before = [Type::T_LIST, $list[1], array_slice($items, 0, $i)];
                $after  = [Type::T_LIST, $list[1], array_slice($items, $i + 1)];

                return [Type::T_INTERPOLATED, $item, $before, $after];
            }
        }

        return $list;
    }

    /**
     * Find the final set of selectors
     *
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     * @param \Leafo\ScssPhp\Block                $selfParent
     *
     * @return array
     */
    protected function multiplySelectors(Environment $env, $selfParent = null)
    {
        $envs            = $this->compactEnv($env);
        $selectors       = [];
        $parentSelectors = [[]];

        $selfParentSelectors = null;

        if (! is_null($selfParent) && $selfParent->selectors) {
            $selfParentSelectors = $this->evalSelectors($selfParent->selectors);
        }

        while ($env = array_pop($envs)) {
            if (empty($env->selectors)) {
                continue;
            }

            $selectors = $env->selectors;

            do {
                $stillHasSelf = false;
                $prevSelectors = $selectors;
                $selectors = [];

                foreach ($prevSelectors as $selector) {
                    foreach ($parentSelectors as $parent) {
                        if ($selfParentSelectors) {
                            foreach ($selfParentSelectors as $selfParent) {
                                // if no '&' in the selector, each call will give same result, only add once
                                $s = $this->joinSelectors($parent, $selector, $stillHasSelf, $selfParent);
                                $selectors[serialize($s)] = $s;
                            }
                        } else {
                            $s = $this->joinSelectors($parent, $selector, $stillHasSelf);
                            $selectors[serialize($s)] = $s;
                        }
                    }
                }
            } while ($stillHasSelf);

            $parentSelectors = $selectors;
        }

        $selectors = array_values($selectors);

        return $selectors;
    }

    /**
     * Join selectors; looks for & to replace, or append parent before child
     *
     * @param array   $parent
     * @param array   $child
     * @param boolean &$stillHasSelf
     * @param array   $selfParentSelectors

     * @return array
     */
    protected function joinSelectors($parent, $child, &$stillHasSelf, $selfParentSelectors = null)
    {
        $setSelf = false;
        $out = [];

        foreach ($child as $part) {
            $newPart = [];

            foreach ($part as $p) {
                // only replace & once and should be recalled to be able to make combinations
                if ($p === static::$selfSelector && $setSelf) {
                    $stillHasSelf = true;
                }

                if ($p === static::$selfSelector && ! $setSelf) {
                    $setSelf = true;

                    if (is_null($selfParentSelectors)) {
                        $selfParentSelectors = $parent;
                    }

                    foreach ($selfParentSelectors as $i => $parentPart) {
                        if ($i > 0) {
                            $out[] = $newPart;
                            $newPart = [];
                        }

                        foreach ($parentPart as $pp) {
                            if (is_array($pp)) {
                                $flatten = [];
                                array_walk_recursive($pp, function ($a) use (&$flatten) {
                                    $flatten[] = $a;
                                });
                                $pp = implode($flatten);
                            }

                            $newPart[] = $pp;
                        }
                    }
                } else {
                    $newPart[] = $p;
                }
            }

            $out[] = $newPart;
        }

        return $setSelf ? $out : array_merge($parent, $child);
    }

    /**
     * Multiply media
     *
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     * @param array                               $childQueries
     *
     * @return array
     */
    protected function multiplyMedia(Environment $env = null, $childQueries = null)
    {
        if (! isset($env) ||
            ! empty($env->block->type) && $env->block->type !== Type::T_MEDIA
        ) {
            return $childQueries;
        }

        // plain old block, skip
        if (empty($env->block->type)) {
            return $this->multiplyMedia($env->parent, $childQueries);
        }

        $parentQueries = isset($env->block->queryList)
            ? $env->block->queryList
            : [[[Type::T_MEDIA_VALUE, $env->block->value]]];

        $store = [$this->env, $this->storeEnv];
        $this->env = $env;
        $this->storeEnv = null;
        $parentQueries = $this->evaluateMediaQuery($parentQueries);
        list($this->env, $this->storeEnv) = $store;

        if ($childQueries === null) {
            $childQueries = $parentQueries;
        } else {
            $originalQueries = $childQueries;
            $childQueries = [];

            foreach ($parentQueries as $parentQuery) {
                foreach ($originalQueries as $childQuery) {
                    $childQueries[] = array_merge(
                        $parentQuery,
                        [[Type::T_MEDIA_TYPE, [Type::T_KEYWORD, 'all']]],
                        $childQuery
                    );
                }
            }
        }

        return $this->multiplyMedia($env->parent, $childQueries);
    }

    /**
     * Convert env linked list to stack
     *
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     *
     * @return array
     */
    protected function compactEnv(Environment $env)
    {
        for ($envs = []; $env; $env = $env->parent) {
            $envs[] = $env;
        }

        return $envs;
    }

    /**
     * Convert env stack to singly linked list
     *
     * @param array $envs
     *
     * @return \Leafo\ScssPhp\Compiler\Environment
     */
    protected function extractEnv($envs)
    {
        for ($env = null; $e = array_pop($envs);) {
            $e->parent = $env;
            $env = $e;
        }

        return $env;
    }

    /**
     * Push environment
     *
     * @param \Leafo\ScssPhp\Block $block
     *
     * @return \Leafo\ScssPhp\Compiler\Environment
     */
    protected function pushEnv(Block $block = null)
    {
        $env = new Environment;
        $env->parent = $this->env;
        $env->store  = [];
        $env->block  = $block;
        $env->depth  = isset($this->env->depth) ? $this->env->depth + 1 : 0;

        $this->env = $env;

        return $env;
    }

    /**
     * Pop environment
     */
    protected function popEnv()
    {
        $this->env = $this->env->parent;
    }

    /**
     * Get store environment
     *
     * @return \Leafo\ScssPhp\Compiler\Environment
     */
    protected function getStoreEnv()
    {
        return isset($this->storeEnv) ? $this->storeEnv : $this->env;
    }

    /**
     * Set variable
     *
     * @param string                              $name
     * @param mixed                               $value
     * @param boolean                             $shadow
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     * @param mixed                               $valueUnreduced
     */
    protected function set($name, $value, $shadow = false, Environment $env = null, $valueUnreduced = null)
    {
        $name = $this->normalizeName($name);

        if (! isset($env)) {
            $env = $this->getStoreEnv();
        }

        if ($shadow) {
            $this->setRaw($name, $value, $env, $valueUnreduced);
        } else {
            $this->setExisting($name, $value, $env, $valueUnreduced);
        }
    }

    /**
     * Set existing variable
     *
     * @param string                              $name
     * @param mixed                               $value
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     * @param mixed                               $valueUnreduced
     */
    protected function setExisting($name, $value, Environment $env, $valueUnreduced = null)
    {
        $storeEnv = $env;

        $hasNamespace = $name[0] === '^' || $name[0] === '@' || $name[0] === '%';

        for (;;) {
            if (array_key_exists($name, $env->store)) {
                break;
            }

            if (! $hasNamespace && isset($env->marker)) {
                $env = $storeEnv;
                break;
            }

            if (! isset($env->parent)) {
                $env = $storeEnv;
                break;
            }

            $env = $env->parent;
        }

        $env->store[$name] = $value;

        if ($valueUnreduced) {
            $env->storeUnreduced[$name] = $valueUnreduced;
        }
    }

    /**
     * Set raw variable
     *
     * @param string                              $name
     * @param mixed                               $value
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     * @param mixed                               $valueUnreduced
     */
    protected function setRaw($name, $value, Environment $env, $valueUnreduced = null)
    {
        $env->store[$name] = $value;

        if ($valueUnreduced) {
            $env->storeUnreduced[$name] = $valueUnreduced;
        }
    }

    /**
     * Get variable
     *
     * @api
     *
     * @param string                              $name
     * @param boolean                             $shouldThrow
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     * @param boolean                             $unreduced
     *
     * @return mixed|null
     */
    public function get($name, $shouldThrow = true, Environment $env = null, $unreduced = false)
    {
        $normalizedName = $this->normalizeName($name);
        $specialContentKey = static::$namespaces['special'] . 'content';

        if (! isset($env)) {
            $env = $this->getStoreEnv();
        }

        $nextIsRoot = false;
        $hasNamespace = $normalizedName[0] === '^' || $normalizedName[0] === '@' || $normalizedName[0] === '%';

        $maxDepth = 10000;

        for (;;) {
            if ($maxDepth-- <= 0) {
                break;
            }

            if (array_key_exists($normalizedName, $env->store)) {
                if ($unreduced && isset($env->storeUnreduced[$normalizedName])) {
                    return $env->storeUnreduced[$normalizedName];
                }

                return $env->store[$normalizedName];
            }

            if (! $hasNamespace && isset($env->marker)) {
                if (! $nextIsRoot && ! empty($env->store[$specialContentKey])) {
                    $env = $env->store[$specialContentKey]->scope;
                    continue;
                }

                $env = $this->rootEnv;
                continue;
            }

            if (! isset($env->parent)) {
                break;
            }

            $env = $env->parent;
        }

        if ($shouldThrow) {
            $this->throwError("Undefined variable \$$name" . ($maxDepth<=0 ? " (infinite recursion)" : ""));
        }

        // found nothing
        return null;
    }

    /**
     * Has variable?
     *
     * @param string                              $name
     * @param \Leafo\ScssPhp\Compiler\Environment $env
     *
     * @return boolean
     */
    protected function has($name, Environment $env = null)
    {
        return $this->get($name, false, $env) !== null;
    }

    /**
     * Inject variables
     *
     * @param array $args
     */
    protected function injectVariables(array $args)
    {
        if (empty($args)) {
            return;
        }

        $parser = $this->parserFactory(__METHOD__);

        foreach ($args as $name => $strValue) {
            if ($name[0] === '$') {
                $name = substr($name, 1);
            }

            if (! $parser->parseValue($strValue, $value)) {
                $value = $this->coerceValue($strValue);
            }

            $this->set($name, $value);
        }
    }

    /**
     * Set variables
     *
     * @api
     *
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        $this->registeredVars = array_merge($this->registeredVars, $variables);
    }

    /**
     * Unset variable
     *
     * @api
     *
     * @param string $name
     */
    public function unsetVariable($name)
    {
        unset($this->registeredVars[$name]);
    }

    /**
     * Returns list of variables
     *
     * @api
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->registeredVars;
    }

    /**
     * Adds to list of parsed files
     *
     * @api
     *
     * @param string $path
     */
    public function addParsedFile($path)
    {
        if (isset($path) && file_exists($path)) {
            $this->parsedFiles[realpath($path)] = filemtime($path);
        }
    }

    /**
     * Returns list of parsed files
     *
     * @api
     *
     * @return array
     */
    public function getParsedFiles()
    {
        return $this->parsedFiles;
    }

    /**
     * Add import path
     *
     * @api
     *
     * @param string|callable $path
     */
    public function addImportPath($path)
    {
        if (! in_array($path, $this->importPaths)) {
            $this->importPaths[] = $path;
        }
    }

    /**
     * Set import paths
     *
     * @api
     *
     * @param string|array $path
     */
    public function setImportPaths($path)
    {
        $this->importPaths = (array) $path;
    }

    /**
     * Set number precision
     *
     * @api
     *
     * @param integer $numberPrecision
     */
    public function setNumberPrecision($numberPrecision)
    {
        Node\Number::$precision = $numberPrecision;
    }

    /**
     * Set formatter
     *
     * @api
     *
     * @param string $formatterName
     */
    public function setFormatter($formatterName)
    {
        $this->formatter = $formatterName;
    }

    /**
     * Set line number style
     *
     * @api
     *
     * @param string $lineNumberStyle
     */
    public function setLineNumberStyle($lineNumberStyle)
    {
        $this->lineNumberStyle = $lineNumberStyle;
    }

    /**
     * Enable/disable source maps
     *
     * @api
     *
     * @param integer $sourceMap
     */
    public function setSourceMap($sourceMap)
    {
        $this->sourceMap = $sourceMap;
    }

    /**
     * Set source map options
     *
     * @api
     *
     * @param array $sourceMapOptions
     */
    public function setSourceMapOptions($sourceMapOptions)
    {
        $this->sourceMapOptions = $sourceMapOptions;
    }

    /**
     * Register function
     *
     * @api
     *
     * @param string   $name
     * @param callable $func
     * @param array    $prototype
     */
    public function registerFunction($name, $func, $prototype = null)
    {
        $this->userFunctions[$this->normalizeName($name)] = [$func, $prototype];
    }

    /**
     * Unregister function
     *
     * @api
     *
     * @param string $name
     */
    public function unregisterFunction($name)
    {
        unset($this->userFunctions[$this->normalizeName($name)]);
    }

    /**
     * Add feature
     *
     * @api
     *
     * @param string $name
     */
    public function addFeature($name)
    {
        $this->registeredFeatures[$name] = true;
    }

    /**
     * Import file
     *
     * @param string                               $path
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $out
     */
    protected function importFile($path, OutputBlock $out)
    {
        // see if tree is cached
        $realPath = realpath($path);

        if (isset($this->importCache[$realPath])) {
            $this->handleImportLoop($realPath);

            $tree = $this->importCache[$realPath];
        } else {
            $code   = file_get_contents($path);
            $parser = $this->parserFactory($path);
            $tree   = $parser->parse($code);

            $this->importCache[$realPath] = $tree;
        }

        $pi = pathinfo($path);
        array_unshift($this->importPaths, $pi['dirname']);
        $this->compileChildrenNoReturn($tree->children, $out);
        array_shift($this->importPaths);
    }

    /**
     * Return the file path for an import url if it exists
     *
     * @api
     *
     * @param string $url
     *
     * @return string|null
     */
    public function findImport($url)
    {
        $urls = [];

        // for "normal" scss imports (ignore vanilla css and external requests)
        if (! preg_match('/\.css$|^https?:\/\//', $url)) {
            // try both normal and the _partial filename
            $urls = [$url, preg_replace('/[^\/]+$/', '_\0', $url)];
        }

        $hasExtension = preg_match('/[.]s?css$/', $url);

        foreach ($this->importPaths as $dir) {
            if (is_string($dir)) {
                // check urls for normal import paths
                foreach ($urls as $full) {
                    $separator = (
                        ! empty($dir) &&
                        substr($dir, -1) !== '/' &&
                        substr($full, 0, 1) !== '/'
                    ) ? '/' : '';
                    $full = $dir . $separator . $full;

                    if ($this->fileExists($file = $full . '.scss') ||
                        ($hasExtension && $this->fileExists($file = $full))
                    ) {
                        return $file;
                    }
                }
            } elseif (is_callable($dir)) {
                // check custom callback for import path
                $file = call_user_func($dir, $url);

                if ($file !== null) {
                    return $file;
                }
            }
        }

        return null;
    }

    /**
     * Set encoding
     *
     * @api
     *
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Ignore errors?
     *
     * @api
     *
     * @param boolean $ignoreErrors
     *
     * @return \Leafo\ScssPhp\Compiler
     */
    public function setIgnoreErrors($ignoreErrors)
    {
        $this->ignoreErrors = $ignoreErrors;

        return $this;
    }

    /**
     * Throw error (exception)
     *
     * @api
     *
     * @param string $msg Message with optional sprintf()-style vararg parameters
     *
     * @throws \Leafo\ScssPhp\Exception\CompilerException
     */
    public function throwError($msg)
    {
        if ($this->ignoreErrors) {
            return;
        }

        $line   = $this->sourceLine;
        $column = $this->sourceColumn;

        $loc = isset($this->sourceNames[$this->sourceIndex])
             ? $this->sourceNames[$this->sourceIndex] . " on line $line, at column $column"
             : "line: $line, column: $column";

        if (func_num_args() > 1) {
            $msg = call_user_func_array('sprintf', func_get_args());
        }

        $msg = "$msg: $loc";

        $callStackMsg = $this->callStackMessage();

        if ($callStackMsg) {
            $msg .= "\nCall Stack:\n" . $callStackMsg;
        }

        throw new CompilerException($msg);
    }

    /**
     * Beautify call stack for output
     *
     * @param boolean $all
     * @param null    $limit
     *
     * @return string
     */
    protected function callStackMessage($all = false, $limit = null)
    {
        $callStackMsg = [];
        $ncall = 0;

        if ($this->callStack) {
            foreach (array_reverse($this->callStack) as $call) {
                if ($all || (isset($call['n']) && $call['n'])) {
                    $msg = "#" . $ncall++ . " " . $call['n'] . " ";
                    $msg .= (isset($this->sourceNames[$call[Parser::SOURCE_INDEX]])
                          ? $this->sourceNames[$call[Parser::SOURCE_INDEX]]
                          : '(unknown file)');
                    $msg .= " on line " . $call[Parser::SOURCE_LINE];
                    $callStackMsg[] = $msg;

                    if (! is_null($limit) && $ncall>$limit) {
                        break;
                    }
                }
            }
        }

        return implode("\n", $callStackMsg);
    }

    /**
     * Handle import loop
     *
     * @param string $name
     *
     * @throws \Exception
     */
    protected function handleImportLoop($name)
    {
        for ($env = $this->env; $env; $env = $env->parent) {
            $file = $this->sourceNames[$env->block->sourceIndex];

            if (realpath($file) === $name) {
                $this->throwError('An @import loop has been found: %s imports %s', $file, basename($file));
                break;
            }
        }
    }

    /**
     * Does file exist?
     *
     * @param string $name
     *
     * @return boolean
     */
    protected function fileExists($name)
    {
        return file_exists($name) && is_file($name);
    }

    /**
     * Call SCSS @function
     *
     * @param string $name
     * @param array  $argValues
     * @param array  $returnValue
     *
     * @return boolean Returns true if returnValue is set; otherwise, false
     */
    protected function callScssFunction($name, $argValues, &$returnValue)
    {
        $func = $this->get(static::$namespaces['function'] . $name, false);

        if (! $func) {
            return false;
        }

        $this->pushEnv();

        $storeEnv = $this->storeEnv;
        $this->storeEnv = $this->env;

        // set the args
        if (isset($func->args)) {
            $this->applyArguments($func->args, $argValues);
        }

        // throw away lines and children
        $tmp = new OutputBlock;
        $tmp->lines    = [];
        $tmp->children = [];

        $this->env->marker = 'function';

        $ret = $this->compileChildren($func->children, $tmp, $this->env->marker . " " . $name);

        $this->storeEnv = $storeEnv;

        $this->popEnv();

        $returnValue = ! isset($ret) ? static::$defaultValue : $ret;

        return true;
    }

    /**
     * Call built-in and registered (PHP) functions
     *
     * @param string $name
     * @param array  $args
     * @param array  $returnValue
     *
     * @return boolean Returns true if returnValue is set; otherwise, false
     */
    protected function callNativeFunction($name, $args, &$returnValue)
    {
        // try a lib function
        $name = $this->normalizeName($name);

        if (isset($this->userFunctions[$name])) {
            // see if we can find a user function
            list($f, $prototype) = $this->userFunctions[$name];
        } elseif (($f = $this->getBuiltinFunction($name)) && is_callable($f)) {
            $libName   = $f[1];
            $prototype = isset(static::$$libName) ? static::$$libName : null;
        } else {
            return false;
        }

        @list($sorted, $kwargs) = $this->sortArgs($prototype, $args);

        if ($name !== 'if' && $name !== 'call') {
            foreach ($sorted as &$val) {
                $val = $this->reduce($val, true);
            }
        }

        $returnValue = call_user_func($f, $sorted, $kwargs);

        if (! isset($returnValue)) {
            return false;
        }

        $returnValue = $this->coerceValue($returnValue);

        return true;
    }

    /**
     * Get built-in function
     *
     * @param string $name Normalized name
     *
     * @return array
     */
    protected function getBuiltinFunction($name)
    {
        $libName = 'lib' . preg_replace_callback(
            '/_(.)/',
            function ($m) {
                return ucfirst($m[1]);
            },
            ucfirst($name)
        );

        return [$this, $libName];
    }

    /**
     * Sorts keyword arguments
     *
     * @param array $prototype
     * @param array $args
     *
     * @return array
     */
    protected function sortArgs($prototype, $args)
    {
        $keyArgs = [];
        $posArgs = [];

        // separate positional and keyword arguments
        foreach ($args as $arg) {
            list($key, $value) = $arg;

            $key = $key[1];

            if (empty($key)) {
                $posArgs[] = empty($arg[2]) ? $value : $arg;
            } else {
                $keyArgs[$key] = $value;
            }
        }

        if (! isset($prototype)) {
            return [$posArgs, $keyArgs];
        }

        // copy positional args
        $finalArgs = array_pad($posArgs, count($prototype), null);

        // overwrite positional args with keyword args
        foreach ($prototype as $i => $names) {
            foreach ((array) $names as $name) {
                if (isset($keyArgs[$name])) {
                    $finalArgs[$i] = $keyArgs[$name];
                }
            }
        }

        return [$finalArgs, $keyArgs];
    }

    /**
     * Apply argument values per definition
     *
     * @param array $argDef
     * @param array $argValues
     *
     * @throws \Exception
     */
    protected function applyArguments($argDef, $argValues)
    {
        $storeEnv = $this->getStoreEnv();

        $env = new Environment;
        $env->store = $storeEnv->store;

        $hasVariable = false;
        $args = [];

        foreach ($argDef as $i => $arg) {
            list($name, $default, $isVariable) = $argDef[$i];

            $args[$name] = [$i, $name, $default, $isVariable];
            $hasVariable |= $isVariable;
        }

        $keywordArgs = [];
        $deferredKeywordArgs = [];
        $remaining = [];

        // assign the keyword args
        foreach ((array) $argValues as $arg) {
            if (! empty($arg[0])) {
                if (! isset($args[$arg[0][1]])) {
                    if ($hasVariable) {
                        $deferredKeywordArgs[$arg[0][1]] = $arg[1];
                    } else {
                        $this->throwError("Mixin or function doesn't have an argument named $%s.", $arg[0][1]);
                        break;
                    }
                } elseif ($args[$arg[0][1]][0] < count($remaining)) {
                    $this->throwError("The argument $%s was passed both by position and by name.", $arg[0][1]);
                    break;
                } else {
                    $keywordArgs[$arg[0][1]] = $arg[1];
                }
            } elseif (count($keywordArgs)) {
                $this->throwError('Positional arguments must come before keyword arguments.');
                break;
            } elseif ($arg[2] === true) {
                $val = $this->reduce($arg[1], true);

                if ($val[0] === Type::T_LIST) {
                    foreach ($val[2] as $name => $item) {
                        if (! is_numeric($name)) {
                            $keywordArgs[$name] = $item;
                        } else {
                            $remaining[] = $item;
                        }
                    }
                } elseif ($val[0] === Type::T_MAP) {
                    foreach ($val[1] as $i => $name) {
                        $name = $this->compileStringContent($this->coerceString($name));
                        $item = $val[2][$i];

                        if (! is_numeric($name)) {
                            $keywordArgs[$name] = $item;
                        } else {
                            $remaining[] = $item;
                        }
                    }
                } else {
                    $remaining[] = $val;
                }
            } else {
                $remaining[] = $arg[1];
            }
        }

        foreach ($args as $arg) {
            list($i, $name, $default, $isVariable) = $arg;

            if ($isVariable) {
                $val = [Type::T_LIST, ',', [], $isVariable];

                for ($count = count($remaining); $i < $count; $i++) {
                    $val[2][] = $remaining[$i];
                }

                foreach ($deferredKeywordArgs as $itemName => $item) {
                    $val[2][$itemName] = $item;
                }
            } elseif (isset($remaining[$i])) {
                $val = $remaining[$i];
            } elseif (isset($keywordArgs[$name])) {
                $val = $keywordArgs[$name];
            } elseif (! empty($default)) {
                continue;
            } else {
                $this->throwError("Missing argument $name");
                break;
            }

            $this->set($name, $this->reduce($val, true), true, $env);
        }

        $storeEnv->store = $env->store;

        foreach ($args as $arg) {
            list($i, $name, $default, $isVariable) = $arg;

            if ($isVariable || isset($remaining[$i]) || isset($keywordArgs[$name]) || empty($default)) {
                continue;
            }

            $this->set($name, $this->reduce($default, true), true);
        }
    }

    /**
     * Coerce a php value into a scss one
     *
     * @param mixed $value
     *
     * @return array|\Leafo\ScssPhp\Node\Number
     */
    protected function coerceValue($value)
    {
        if (is_array($value) || $value instanceof \ArrayAccess) {
            return $value;
        }

        if (is_bool($value)) {
            return $this->toBool($value);
        }

        if ($value === null) {
            return static::$null;
        }

        if (is_numeric($value)) {
            return new Node\Number($value, '');
        }

        if ($value === '') {
            return static::$emptyString;
        }

        if (preg_match('/^(#([0-9a-f]{6})|#([0-9a-f]{3}))$/i', $value, $m)) {
            $color = [Type::T_COLOR];

            if (isset($m[3])) {
                $num = hexdec($m[3]);

                foreach ([3, 2, 1] as $i) {
                    $t = $num & 0xf;
                    $color[$i] = $t << 4 | $t;
                    $num >>= 4;
                }
            } else {
                $num = hexdec($m[2]);

                foreach ([3, 2, 1] as $i) {
                    $color[$i] = $num & 0xff;
                    $num >>= 8;
                }
            }

            return $color;
        }

        return [Type::T_KEYWORD, $value];
    }

    /**
     * Coerce something to map
     *
     * @param array $item
     *
     * @return array
     */
    protected function coerceMap($item)
    {
        if ($item[0] === Type::T_MAP) {
            return $item;
        }

        if ($item === static::$emptyList) {
            return static::$emptyMap;
        }

        return [Type::T_MAP, [$item], [static::$null]];
    }

    /**
     * Coerce something to list
     *
     * @param array  $item
     * @param string $delim
     *
     * @return array
     */
    protected function coerceList($item, $delim = ',')
    {
        if (isset($item) && $item[0] === Type::T_LIST) {
            return $item;
        }

        if (isset($item) && $item[0] === Type::T_MAP) {
            $keys = $item[1];
            $values = $item[2];
            $list = [];

            for ($i = 0, $s = count($keys); $i < $s; $i++) {
                $key = $keys[$i];
                $value = $values[$i];

                $list[] = [
                    Type::T_LIST,
                    '',
                    [[Type::T_KEYWORD, $this->compileStringContent($this->coerceString($key))], $value]
                ];
            }

            return [Type::T_LIST, ',', $list];
        }

        return [Type::T_LIST, $delim, ! isset($item) ? []: [$item]];
    }

    /**
     * Coerce color for expression
     *
     * @param array $value
     *
     * @return array|null
     */
    protected function coerceForExpression($value)
    {
        if ($color = $this->coerceColor($value)) {
            return $color;
        }

        return $value;
    }

    /**
     * Coerce value to color
     *
     * @param array $value
     *
     * @return array|null
     */
    protected function coerceColor($value)
    {
        switch ($value[0]) {
            case Type::T_COLOR:
                return $value;

            case Type::T_KEYWORD:
                $name = strtolower($value[1]);

                if (isset(Colors::$cssColors[$name])) {
                    $rgba = explode(',', Colors::$cssColors[$name]);

                    return isset($rgba[3])
                        ? [Type::T_COLOR, (int) $rgba[0], (int) $rgba[1], (int) $rgba[2], (int) $rgba[3]]
                        : [Type::T_COLOR, (int) $rgba[0], (int) $rgba[1], (int) $rgba[2]];
                }

                return null;
        }

        return null;
    }

    /**
     * Coerce value to string
     *
     * @param array $value
     *
     * @return array|null
     */
    protected function coerceString($value)
    {
        if ($value[0] === Type::T_STRING) {
            return $value;
        }

        return [Type::T_STRING, '', [$this->compileValue($value)]];
    }

    /**
     * Coerce value to a percentage
     *
     * @param array $value
     *
     * @return integer|float
     */
    protected function coercePercent($value)
    {
        if ($value[0] === Type::T_NUMBER) {
            if (! empty($value[2]['%'])) {
                return $value[1] / 100;
            }

            return $value[1];
        }

        return 0;
    }

    /**
     * Assert value is a map
     *
     * @api
     *
     * @param array $value
     *
     * @return array
     *
     * @throws \Exception
     */
    public function assertMap($value)
    {
        $value = $this->coerceMap($value);

        if ($value[0] !== Type::T_MAP) {
            $this->throwError('expecting map, %s received', $value[0]);
        }

        return $value;
    }

    /**
     * Assert value is a list
     *
     * @api
     *
     * @param array $value
     *
     * @return array
     *
     * @throws \Exception
     */
    public function assertList($value)
    {
        if ($value[0] !== Type::T_LIST) {
            $this->throwError('expecting list, %s received', $value[0]);
        }

        return $value;
    }

    /**
     * Assert value is a color
     *
     * @api
     *
     * @param array $value
     *
     * @return array
     *
     * @throws \Exception
     */
    public function assertColor($value)
    {
        if ($color = $this->coerceColor($value)) {
            return $color;
        }

        $this->throwError('expecting color, %s received', $value[0]);
    }

    /**
     * Assert value is a number
     *
     * @api
     *
     * @param array $value
     *
     * @return integer|float
     *
     * @throws \Exception
     */
    public function assertNumber($value)
    {
        if ($value[0] !== Type::T_NUMBER) {
            $this->throwError('expecting number, %s received', $value[0]);
        }

        return $value[1];
    }

    /**
     * Make sure a color's components don't go out of bounds
     *
     * @param array $c
     *
     * @return array
     */
    protected function fixColor($c)
    {
        foreach ([1, 2, 3] as $i) {
            if ($c[$i] < 0) {
                $c[$i] = 0;
            }

            if ($c[$i] > 255) {
                $c[$i] = 255;
            }
        }

        return $c;
    }

    /**
     * Convert RGB to HSL
     *
     * @api
     *
     * @param integer $red
     * @param integer $green
     * @param integer $blue
     *
     * @return array
     */
    public function toHSL($red, $green, $blue)
    {
        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);

        $l = $min + $max;
        $d = $max - $min;

        if ((int) $d === 0) {
            $h = $s = 0;
        } else {
            if ($l < 255) {
                $s = $d / $l;
            } else {
                $s = $d / (510 - $l);
            }

            if ($red == $max) {
                $h = 60 * ($green - $blue) / $d;
            } elseif ($green == $max) {
                $h = 60 * ($blue - $red) / $d + 120;
            } elseif ($blue == $max) {
                $h = 60 * ($red - $green) / $d + 240;
            }
        }

        return [Type::T_HSL, fmod($h, 360), $s * 100, $l / 5.1];
    }

    /**
     * Hue to RGB helper
     *
     * @param float $m1
     * @param float $m2
     * @param float $h
     *
     * @return float
     */
    protected function hueToRGB($m1, $m2, $h)
    {
        if ($h < 0) {
            $h += 1;
        } elseif ($h > 1) {
            $h -= 1;
        }

        if ($h * 6 < 1) {
            return $m1 + ($m2 - $m1) * $h * 6;
        }

        if ($h * 2 < 1) {
            return $m2;
        }

        if ($h * 3 < 2) {
            return $m1 + ($m2 - $m1) * (2/3 - $h) * 6;
        }

        return $m1;
    }

    /**
     * Convert HSL to RGB
     *
     * @api
     *
     * @param integer $hue        H from 0 to 360
     * @param integer $saturation S from 0 to 100
     * @param integer $lightness  L from 0 to 100
     *
     * @return array
     */
    public function toRGB($hue, $saturation, $lightness)
    {
        if ($hue < 0) {
            $hue += 360;
        }

        $h = $hue / 360;
        $s = min(100, max(0, $saturation)) / 100;
        $l = min(100, max(0, $lightness)) / 100;

        $m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;
        $m1 = $l * 2 - $m2;

        $r = $this->hueToRGB($m1, $m2, $h + 1/3) * 255;
        $g = $this->hueToRGB($m1, $m2, $h) * 255;
        $b = $this->hueToRGB($m1, $m2, $h - 1/3) * 255;

        $out = [Type::T_COLOR, $r, $g, $b];

        return $out;
    }

    // Built in functions

    //protected static $libCall = ['name', 'args...'];
    protected function libCall($args, $kwargs)
    {
        $name = $this->compileStringContent($this->coerceString($this->reduce(array_shift($args), true)));

        $posArgs = [];

        foreach ($args as $arg) {
            if (empty($arg[0])) {
                if ($arg[2] === true) {
                    $tmp = $this->reduce($arg[1]);

                    if ($tmp[0] === Type::T_LIST) {
                        foreach ($tmp[2] as $item) {
                            $posArgs[] = [null, $item, false];
                        }
                    } else {
                        $posArgs[] = [null, $tmp, true];
                    }

                    continue;
                }

                $posArgs[] = [null, $this->reduce($arg), false];
                continue;
            }

            $posArgs[] = [null, $arg, false];
        }

        if (count($kwargs)) {
            foreach ($kwargs as $key => $value) {
                $posArgs[] = [[Type::T_VARIABLE, $key], $value, false];
            }
        }

        return $this->reduce([Type::T_FUNCTION_CALL, $name, $posArgs]);
    }

    protected static $libIf = ['condition', 'if-true', 'if-false'];
    protected function libIf($args)
    {
        list($cond, $t, $f) = $args;

        if (! $this->isTruthy($this->reduce($cond, true))) {
            return $this->reduce($f, true);
        }

        return $this->reduce($t, true);
    }

    protected static $libIndex = ['list', 'value'];
    protected function libIndex($args)
    {
        list($list, $value) = $args;

        if ($value[0] === Type::T_MAP) {
            return static::$null;
        }

        if ($list[0] === Type::T_MAP ||
            $list[0] === Type::T_STRING ||
            $list[0] === Type::T_KEYWORD ||
            $list[0] === Type::T_INTERPOLATE
        ) {
            $list = $this->coerceList($list, ' ');
        }

        if ($list[0] !== Type::T_LIST) {
            return static::$null;
        }

        $values = [];

        foreach ($list[2] as $item) {
            $values[] = $this->normalizeValue($item);
        }

        $key = array_search($this->normalizeValue($value), $values);

        return false === $key ? static::$null : $key + 1;
    }

    protected static $libRgb = ['red', 'green', 'blue'];
    protected function libRgb($args)
    {
        list($r, $g, $b) = $args;

        return [Type::T_COLOR, $r[1], $g[1], $b[1]];
    }

    protected static $libRgba = [
        ['red', 'color'],
        'green', 'blue', 'alpha'];
    protected function libRgba($args)
    {
        if ($color = $this->coerceColor($args[0])) {
            $num = isset($args[3]) ? $args[3] : $args[1];
            $alpha = $this->assertNumber($num);
            $color[4] = $alpha;

            return $color;
        }

        list($r, $g, $b, $a) = $args;

        return [Type::T_COLOR, $r[1], $g[1], $b[1], $a[1]];
    }

    // helper function for adjust_color, change_color, and scale_color
    protected function alterColor($args, $fn)
    {
        $color = $this->assertColor($args[0]);

        foreach ([1, 2, 3, 7] as $i) {
            if (isset($args[$i])) {
                $val = $this->assertNumber($args[$i]);
                $ii = $i === 7 ? 4 : $i; // alpha
                $color[$ii] = call_user_func($fn, isset($color[$ii]) ? $color[$ii] : 0, $val, $i);
            }
        }

        if (isset($args[4]) || isset($args[5]) || isset($args[6])) {
            $hsl = $this->toHSL($color[1], $color[2], $color[3]);

            foreach ([4, 5, 6] as $i) {
                if (isset($args[$i])) {
                    $val = $this->assertNumber($args[$i]);
                    $hsl[$i - 3] = call_user_func($fn, $hsl[$i - 3], $val, $i);
                }
            }

            $rgb = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);

            if (isset($color[4])) {
                $rgb[4] = $color[4];
            }

            $color = $rgb;
        }

        return $color;
    }

    protected static $libAdjustColor = [
        'color', 'red', 'green', 'blue',
        'hue', 'saturation', 'lightness', 'alpha'
    ];
    protected function libAdjustColor($args)
    {
        return $this->alterColor($args, function ($base, $alter, $i) {
            return $base + $alter;
        });
    }

    protected static $libChangeColor = [
        'color', 'red', 'green', 'blue',
        'hue', 'saturation', 'lightness', 'alpha'
    ];
    protected function libChangeColor($args)
    {
        return $this->alterColor($args, function ($base, $alter, $i) {
            return $alter;
        });
    }

    protected static $libScaleColor = [
        'color', 'red', 'green', 'blue',
        'hue', 'saturation', 'lightness', 'alpha'
    ];
    protected function libScaleColor($args)
    {
        return $this->alterColor($args, function ($base, $scale, $i) {
            // 1, 2, 3 - rgb
            // 4, 5, 6 - hsl
            // 7 - a
            switch ($i) {
                case 1:
                case 2:
                case 3:
                    $max = 255;
                    break;

                case 4:
                    $max = 360;
                    break;

                case 7:
                    $max = 1;
                    break;

                default:
                    $max = 100;
            }

            $scale = $scale / 100;

            if ($scale < 0) {
                return $base * $scale + $base;
            }

            return ($max - $base) * $scale + $base;
        });
    }

    protected static $libIeHexStr = ['color'];
    protected function libIeHexStr($args)
    {
        $color = $this->coerceColor($args[0]);
        $color[4] = isset($color[4]) ? round(255 * $color[4]) : 255;

        return sprintf('#%02X%02X%02X%02X', $color[4], $color[1], $color[2], $color[3]);
    }

    protected static $libRed = ['color'];
    protected function libRed($args)
    {
        $color = $this->coerceColor($args[0]);

        return $color[1];
    }

    protected static $libGreen = ['color'];
    protected function libGreen($args)
    {
        $color = $this->coerceColor($args[0]);

        return $color[2];
    }

    protected static $libBlue = ['color'];
    protected function libBlue($args)
    {
        $color = $this->coerceColor($args[0]);

        return $color[3];
    }

    protected static $libAlpha = ['color'];
    protected function libAlpha($args)
    {
        if ($color = $this->coerceColor($args[0])) {
            return isset($color[4]) ? $color[4] : 1;
        }

        // this might be the IE function, so return value unchanged
        return null;
    }

    protected static $libOpacity = ['color'];
    protected function libOpacity($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        return $this->libAlpha($args);
    }

    // mix two colors
    protected static $libMix = ['color-1', 'color-2', 'weight'];
    protected function libMix($args)
    {
        list($first, $second, $weight) = $args;

        $first = $this->assertColor($first);
        $second = $this->assertColor($second);

        if (! isset($weight)) {
            $weight = 0.5;
        } else {
            $weight = $this->coercePercent($weight);
        }

        $firstAlpha = isset($first[4]) ? $first[4] : 1;
        $secondAlpha = isset($second[4]) ? $second[4] : 1;

        $w = $weight * 2 - 1;
        $a = $firstAlpha - $secondAlpha;

        $w1 = (($w * $a === -1 ? $w : ($w + $a) / (1 + $w * $a)) + 1) / 2.0;
        $w2 = 1.0 - $w1;

        $new = [Type::T_COLOR,
            $w1 * $first[1] + $w2 * $second[1],
            $w1 * $first[2] + $w2 * $second[2],
            $w1 * $first[3] + $w2 * $second[3],
        ];

        if ($firstAlpha != 1.0 || $secondAlpha != 1.0) {
            $new[] = $firstAlpha * $weight + $secondAlpha * (1 - $weight);
        }

        return $this->fixColor($new);
    }

    protected static $libHsl = ['hue', 'saturation', 'lightness'];
    protected function libHsl($args)
    {
        list($h, $s, $l) = $args;

        return $this->toRGB($h[1], $s[1], $l[1]);
    }

    protected static $libHsla = ['hue', 'saturation', 'lightness', 'alpha'];
    protected function libHsla($args)
    {
        list($h, $s, $l, $a) = $args;

        $color = $this->toRGB($h[1], $s[1], $l[1]);
        $color[4] = $a[1];

        return $color;
    }

    protected static $libHue = ['color'];
    protected function libHue($args)
    {
        $color = $this->assertColor($args[0]);
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);

        return new Node\Number($hsl[1], 'deg');
    }

    protected static $libSaturation = ['color'];
    protected function libSaturation($args)
    {
        $color = $this->assertColor($args[0]);
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);

        return new Node\Number($hsl[2], '%');
    }

    protected static $libLightness = ['color'];
    protected function libLightness($args)
    {
        $color = $this->assertColor($args[0]);
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);

        return new Node\Number($hsl[3], '%');
    }

    protected function adjustHsl($color, $idx, $amount)
    {
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);
        $hsl[$idx] += $amount;
        $out = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);

        if (isset($color[4])) {
            $out[4] = $color[4];
        }

        return $out;
    }

    protected static $libAdjustHue = ['color', 'degrees'];
    protected function libAdjustHue($args)
    {
        $color = $this->assertColor($args[0]);
        $degrees = $this->assertNumber($args[1]);

        return $this->adjustHsl($color, 1, $degrees);
    }

    protected static $libLighten = ['color', 'amount'];
    protected function libLighten($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = Util::checkRange('amount', new Range(0, 100), $args[1], '%');

        return $this->adjustHsl($color, 3, $amount);
    }

    protected static $libDarken = ['color', 'amount'];
    protected function libDarken($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = Util::checkRange('amount', new Range(0, 100), $args[1], '%');

        return $this->adjustHsl($color, 3, -$amount);
    }

    protected static $libSaturate = ['color', 'amount'];
    protected function libSaturate($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        $color = $this->assertColor($value);
        $amount = 100 * $this->coercePercent($args[1]);

        return $this->adjustHsl($color, 2, $amount);
    }

    protected static $libDesaturate = ['color', 'amount'];
    protected function libDesaturate($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = 100 * $this->coercePercent($args[1]);

        return $this->adjustHsl($color, 2, -$amount);
    }

    protected static $libGrayscale = ['color'];
    protected function libGrayscale($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        return $this->adjustHsl($this->assertColor($value), 2, -100);
    }

    protected static $libComplement = ['color'];
    protected function libComplement($args)
    {
        return $this->adjustHsl($this->assertColor($args[0]), 1, 180);
    }

    protected static $libInvert = ['color'];
    protected function libInvert($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        $color = $this->assertColor($value);
        $color[1] = 255 - $color[1];
        $color[2] = 255 - $color[2];
        $color[3] = 255 - $color[3];

        return $color;
    }

    // increases opacity by amount
    protected static $libOpacify = ['color', 'amount'];
    protected function libOpacify($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = $this->coercePercent($args[1]);

        $color[4] = (isset($color[4]) ? $color[4] : 1) + $amount;
        $color[4] = min(1, max(0, $color[4]));

        return $color;
    }

    protected static $libFadeIn = ['color', 'amount'];
    protected function libFadeIn($args)
    {
        return $this->libOpacify($args);
    }

    // decreases opacity by amount
    protected static $libTransparentize = ['color', 'amount'];
    protected function libTransparentize($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = $this->coercePercent($args[1]);

        $color[4] = (isset($color[4]) ? $color[4] : 1) - $amount;
        $color[4] = min(1, max(0, $color[4]));

        return $color;
    }

    protected static $libFadeOut = ['color', 'amount'];
    protected function libFadeOut($args)
    {
        return $this->libTransparentize($args);
    }

    protected static $libUnquote = ['string'];
    protected function libUnquote($args)
    {
        $str = $args[0];

        if ($str[0] === Type::T_STRING) {
            $str[1] = '';
        }

        return $str;
    }

    protected static $libQuote = ['string'];
    protected function libQuote($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_STRING && ! empty($value[1])) {
            return $value;
        }

        return [Type::T_STRING, '"', [$value]];
    }

    protected static $libPercentage = ['value'];
    protected function libPercentage($args)
    {
        return new Node\Number($this->coercePercent($args[0]) * 100, '%');
    }

    protected static $libRound = ['value'];
    protected function libRound($args)
    {
        $num = $args[0];

        return new Node\Number(round($num[1]), $num[2]);
    }

    protected static $libFloor = ['value'];
    protected function libFloor($args)
    {
        $num = $args[0];

        return new Node\Number(floor($num[1]), $num[2]);
    }

    protected static $libCeil = ['value'];
    protected function libCeil($args)
    {
        $num = $args[0];

        return new Node\Number(ceil($num[1]), $num[2]);
    }

    protected static $libAbs = ['value'];
    protected function libAbs($args)
    {
        $num = $args[0];

        return new Node\Number(abs($num[1]), $num[2]);
    }

    protected function libMin($args)
    {
        $numbers = $this->getNormalizedNumbers($args);
        $min = null;

        foreach ($numbers as $key => $number) {
            if (null === $min || $number[1] <= $min[1]) {
                $min = [$key, $number[1]];
            }
        }

        return $args[$min[0]];
    }

    protected function libMax($args)
    {
        $numbers = $this->getNormalizedNumbers($args);
        $max = null;

        foreach ($numbers as $key => $number) {
            if (null === $max || $number[1] >= $max[1]) {
                $max = [$key, $number[1]];
            }
        }

        return $args[$max[0]];
    }

    /**
     * Helper to normalize args containing numbers
     *
     * @param array $args
     *
     * @return array
     */
    protected function getNormalizedNumbers($args)
    {
        $unit = null;
        $originalUnit = null;
        $numbers = [];

        foreach ($args as $key => $item) {
            if ($item[0] !== Type::T_NUMBER) {
                $this->throwError('%s is not a number', $item[0]);
                break;
            }

            $number = $item->normalize();

            if (null === $unit) {
                $unit = $number[2];
                $originalUnit = $item->unitStr();
            } elseif ($number[1] && $unit !== $number[2]) {
                $this->throwError('Incompatible units: "%s" and "%s".', $originalUnit, $item->unitStr());
                break;
            }

            $numbers[$key] = $number;
        }

        return $numbers;
    }

    protected static $libLength = ['list'];
    protected function libLength($args)
    {
        $list = $this->coerceList($args[0]);

        return count($list[2]);
    }

    //protected static $libListSeparator = ['list...'];
    protected function libListSeparator($args)
    {
        if (count($args) > 1) {
            return 'comma';
        }

        $list = $this->coerceList($args[0]);

        if (count($list[2]) <= 1) {
            return 'space';
        }

        if ($list[1] === ',') {
            return 'comma';
        }

        return 'space';
    }

    protected static $libNth = ['list', 'n'];
    protected function libNth($args)
    {
        $list = $this->coerceList($args[0]);
        $n = $this->assertNumber($args[1]);

        if ($n > 0) {
            $n--;
        } elseif ($n < 0) {
            $n += count($list[2]);
        }

        return isset($list[2][$n]) ? $list[2][$n] : static::$defaultValue;
    }

    protected static $libSetNth = ['list', 'n', 'value'];
    protected function libSetNth($args)
    {
        $list = $this->coerceList($args[0]);
        $n = $this->assertNumber($args[1]);

        if ($n > 0) {
            $n--;
        } elseif ($n < 0) {
            $n += count($list[2]);
        }

        if (! isset($list[2][$n])) {
            $this->throwError('Invalid argument for "n"');

            return null;
        }

        $list[2][$n] = $args[2];

        return $list;
    }

    protected static $libMapGet = ['map', 'key'];
    protected function libMapGet($args)
    {
        $map = $this->assertMap($args[0]);
        $key = $this->compileStringContent($this->coerceString($args[1]));

        for ($i = count($map[1]) - 1; $i >= 0; $i--) {
            if ($key === $this->compileStringContent($this->coerceString($map[1][$i]))) {
                return $map[2][$i];
            }
        }

        return static::$null;
    }

    protected static $libMapKeys = ['map'];
    protected function libMapKeys($args)
    {
        $map = $this->assertMap($args[0]);
        $keys = $map[1];

        return [Type::T_LIST, ',', $keys];
    }

    protected static $libMapValues = ['map'];
    protected function libMapValues($args)
    {
        $map = $this->assertMap($args[0]);
        $values = $map[2];

        return [Type::T_LIST, ',', $values];
    }

    protected static $libMapRemove = ['map', 'key'];
    protected function libMapRemove($args)
    {
        $map = $this->assertMap($args[0]);
        $key = $this->compileStringContent($this->coerceString($args[1]));

        for ($i = count($map[1]) - 1; $i >= 0; $i--) {
            if ($key === $this->compileStringContent($this->coerceString($map[1][$i]))) {
                array_splice($map[1], $i, 1);
                array_splice($map[2], $i, 1);
            }
        }

        return $map;
    }

    protected static $libMapHasKey = ['map', 'key'];
    protected function libMapHasKey($args)
    {
        $map = $this->assertMap($args[0]);
        $key = $this->compileStringContent($this->coerceString($args[1]));

        for ($i = count($map[1]) - 1; $i >= 0; $i--) {
            if ($key === $this->compileStringContent($this->coerceString($map[1][$i]))) {
                return true;
            }
        }

        return false;
    }

    protected static $libMapMerge = ['map-1', 'map-2'];
    protected function libMapMerge($args)
    {
        $map1 = $this->assertMap($args[0]);
        $map2 = $this->assertMap($args[1]);

        foreach ($map2[1] as $i2 => $key2) {
            $key = $this->compileStringContent($this->coerceString($key2));

            foreach ($map1[1] as $i1 => $key1) {
                if ($key === $this->compileStringContent($this->coerceString($key1))) {
                    $map1[2][$i1] = $map2[2][$i2];
                    continue 2;
                }
            }

            $map1[1][] = $map2[1][$i2];
            $map1[2][] = $map2[2][$i2];
        }

        return $map1;
    }

    protected static $libKeywords = ['args'];
    protected function libKeywords($args)
    {
        $this->assertList($args[0]);

        $keys = [];
        $values = [];

        foreach ($args[0][2] as $name => $arg) {
            $keys[] = [Type::T_KEYWORD, $name];
            $values[] = $arg;
        }

        return [Type::T_MAP, $keys, $values];
    }

    protected function listSeparatorForJoin($list1, $sep)
    {
        if (! isset($sep)) {
            return $list1[1];
        }

        switch ($this->compileValue($sep)) {
            case 'comma':
                return ',';

            case 'space':
                return '';

            default:
                return $list1[1];
        }
    }

    protected static $libJoin = ['list1', 'list2', 'separator'];
    protected function libJoin($args)
    {
        list($list1, $list2, $sep) = $args;

        $list1 = $this->coerceList($list1, ' ');
        $list2 = $this->coerceList($list2, ' ');
        $sep = $this->listSeparatorForJoin($list1, $sep);

        return [Type::T_LIST, $sep, array_merge($list1[2], $list2[2])];
    }

    protected static $libAppend = ['list', 'val', 'separator'];
    protected function libAppend($args)
    {
        list($list1, $value, $sep) = $args;

        $list1 = $this->coerceList($list1, ' ');
        $sep = $this->listSeparatorForJoin($list1, $sep);

        return [Type::T_LIST, $sep, array_merge($list1[2], [$value])];
    }

    protected function libZip($args)
    {
        foreach ($args as $arg) {
            $this->assertList($arg);
        }

        $lists = [];
        $firstList = array_shift($args);

        foreach ($firstList[2] as $key => $item) {
            $list = [Type::T_LIST, '', [$item]];

            foreach ($args as $arg) {
                if (isset($arg[2][$key])) {
                    $list[2][] = $arg[2][$key];
                } else {
                    break 2;
                }
            }

            $lists[] = $list;
        }

        return [Type::T_LIST, ',', $lists];
    }

    protected static $libTypeOf = ['value'];
    protected function libTypeOf($args)
    {
        $value = $args[0];

        switch ($value[0]) {
            case Type::T_KEYWORD:
                if ($value === static::$true || $value === static::$false) {
                    return 'bool';
                }

                if ($this->coerceColor($value)) {
                    return 'color';
                }

                // fall-thru
            case Type::T_FUNCTION:
                return 'string';

            case Type::T_LIST:
                if (isset($value[3]) && $value[3]) {
                    return 'arglist';
                }

                // fall-thru
            default:
                return $value[0];
        }
    }

    protected static $libUnit = ['number'];
    protected function libUnit($args)
    {
        $num = $args[0];

        if ($num[0] === Type::T_NUMBER) {
            return [Type::T_STRING, '"', [$num->unitStr()]];
        }

        return '';
    }

    protected static $libUnitless = ['number'];
    protected function libUnitless($args)
    {
        $value = $args[0];

        return $value[0] === Type::T_NUMBER && $value->unitless();
    }

    protected static $libComparable = ['number-1', 'number-2'];
    protected function libComparable($args)
    {
        list($number1, $number2) = $args;

        if (! isset($number1[0]) || $number1[0] !== Type::T_NUMBER ||
            ! isset($number2[0]) || $number2[0] !== Type::T_NUMBER
        ) {
            $this->throwError('Invalid argument(s) for "comparable"');

            return null;
        }

        $number1 = $number1->normalize();
        $number2 = $number2->normalize();

        return $number1[2] === $number2[2] || $number1->unitless() || $number2->unitless();
    }

    protected static $libStrIndex = ['string', 'substring'];
    protected function libStrIndex($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $substring = $this->coerceString($args[1]);
        $substringContent = $this->compileStringContent($substring);

        $result = strpos($stringContent, $substringContent);

        return $result === false ? static::$null : new Node\Number($result + 1, '');
    }

    protected static $libStrInsert = ['string', 'insert', 'index'];
    protected function libStrInsert($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $insert = $this->coerceString($args[1]);
        $insertContent = $this->compileStringContent($insert);

        list(, $index) = $args[2];

        $string[2] = [substr_replace($stringContent, $insertContent, $index - 1, 0)];

        return $string;
    }

    protected static $libStrLength = ['string'];
    protected function libStrLength($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        return new Node\Number(strlen($stringContent), '');
    }

    protected static $libStrSlice = ['string', 'start-at', 'end-at'];
    protected function libStrSlice($args)
    {
        if (isset($args[2]) && $args[2][1] == 0) {
            return static::$nullString;
        }

        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $start = (int) $args[1][1];

        if ($start > 0) {
            $start--;
        }

        $end    = (int) $args[2][1];
        $length = $end < 0 ? $end + 1 : ($end > 0 ? $end - $start : $end);

        $string[2] = $length
            ? [substr($stringContent, $start, $length)]
            : [substr($stringContent, $start)];

        return $string;
    }

    protected static $libToLowerCase = ['string'];
    protected function libToLowerCase($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $string[2] = [function_exists('mb_strtolower') ? mb_strtolower($stringContent) : strtolower($stringContent)];

        return $string;
    }

    protected static $libToUpperCase = ['string'];
    protected function libToUpperCase($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $string[2] = [function_exists('mb_strtoupper') ? mb_strtoupper($stringContent) : strtoupper($stringContent)];

        return $string;
    }

    protected static $libFeatureExists = ['feature'];
    protected function libFeatureExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->toBool(
            array_key_exists($name, $this->registeredFeatures) ? $this->registeredFeatures[$name] : false
        );
    }

    protected static $libFunctionExists = ['name'];
    protected function libFunctionExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        // user defined functions
        if ($this->has(static::$namespaces['function'] . $name)) {
            return true;
        }

        $name = $this->normalizeName($name);

        if (isset($this->userFunctions[$name])) {
            return true;
        }

        // built-in functions
        $f = $this->getBuiltinFunction($name);

        return $this->toBool(is_callable($f));
    }

    protected static $libGlobalVariableExists = ['name'];
    protected function libGlobalVariableExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->has($name, $this->rootEnv);
    }

    protected static $libMixinExists = ['name'];
    protected function libMixinExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->has(static::$namespaces['mixin'] . $name);
    }

    protected static $libVariableExists = ['name'];
    protected function libVariableExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->has($name);
    }

    /**
     * Workaround IE7's content counter bug.
     *
     * @param array $args
     *
     * @return array
     */
    protected function libCounter($args)
    {
        $list = array_map([$this, 'compileValue'], $args);

        return [Type::T_STRING, '', ['counter(' . implode(',', $list) . ')']];
    }

    protected static $libRandom = ['limit'];
    protected function libRandom($args)
    {
        if (isset($args[0])) {
            $n = $this->assertNumber($args[0]);

            if ($n < 1) {
                $this->throwError("limit must be greater than or equal to 1");

                return null;
            }

            return new Node\Number(mt_rand(1, $n), '');
        }

        return new Node\Number(mt_rand(1, mt_getrandmax()), '');
    }

    protected function libUniqueId()
    {
        static $id;

        if (! isset($id)) {
            $id = mt_rand(0, pow(36, 8));
        }

        $id += mt_rand(0, 10) + 1;

        return [Type::T_STRING, '', ['u' . str_pad(base_convert($id, 10, 36), 8, '0', STR_PAD_LEFT)]];
    }

    protected static $libInspect = ['value'];
    protected function libInspect($args)
    {
        if ($args[0] === static::$null) {
            return [Type::T_KEYWORD, 'null'];
        }

        return $args[0];
    }

    /**
     * Preprocess selector args
     *
     * @param array $arg
     *
     * @return array|boolean
     */
    protected function getSelectorArg($arg)
    {
        static $parser = null;

        if (is_null($parser)) {
            $parser = $this->parserFactory(__METHOD__);
        }

        $arg = $this->libUnquote([$arg]);
        $arg = $this->compileValue($arg);

        $parsedSelector = [];

        if ($parser->parseSelector($arg, $parsedSelector)) {
            $selector = $this->evalSelectors($parsedSelector);
            $gluedSelector = $this->glueFunctionSelectors($selector);

            return $gluedSelector;
        }

        return false;
    }

    /**
     * Postprocess selector to output in right format
     *
     * @param array $selectors
     *
     * @return string
     */
    protected function formatOutputSelector($selectors)
    {
        $selectors = $this->collapseSelectors($selectors, true);

        return $selectors;
    }

    protected static $libIsSuperselector = ['super', 'sub'];
    protected function libIsSuperselector($args)
    {
        list($super, $sub) = $args;

        $super = $this->getSelectorArg($super);
        $sub = $this->getSelectorArg($sub);

        return $this->isSuperSelector($super, $sub);
    }

    /**
     * Test a $super selector again $sub
     *
     * @param array $super
     * @param array $sub
     *
     * @return boolean
     */
    protected function isSuperSelector($super, $sub)
    {
        // one and only one selector for each arg
        if (! $super || count($super) !== 1) {
            $this->throwError("Invalid super selector for isSuperSelector()");
        }

        if (! $sub || count($sub) !== 1) {
            $this->throwError("Invalid sub selector for isSuperSelector()");
        }

        $super = reset($super);
        $sub = reset($sub);

        $i = 0;
        $nextMustMatch = false;

        foreach ($super as $node) {
            $compound = '';

            array_walk_recursive(
                $node,
                function ($value, $key) use (&$compound) {
                    $compound .= $value;
                }
            );

            if ($this->isImmediateRelationshipCombinator($compound)) {
                if ($node !== $sub[$i]) {
                    return false;
                }

                $nextMustMatch = true;
                $i++;
            } else {
                while ($i < count($sub) && ! $this->isSuperPart($node, $sub[$i])) {
                    if ($nextMustMatch) {
                        return false;
                    }

                    $i++;
                }

                if ($i >= count($sub)) {
                    return false;
                }

                $nextMustMatch = false;
                $i++;
            }
        }

        return true;
    }

    /**
     * Test a part of super selector again a part of sub selector
     *
     * @param array $superParts
     * @param array $subParts
     *
     * @return boolean
     */
    protected function isSuperPart($superParts, $subParts)
    {
        $i = 0;

        foreach ($superParts as $superPart) {
            while ($i < count($subParts) && $subParts[$i] !== $superPart) {
                $i++;
            }

            if ($i >= count($subParts)) {
                return false;
            }

            $i++;
        }

        return true;
    }

    //protected static $libSelectorAppend = ['selector...'];
    protected function libSelectorAppend($args)
    {
        if (count($args) < 1) {
            $this->throwError("selector-append() needs at least 1 argument");
        }

        $selectors = array_map([$this, 'getSelectorArg'], $args);

        return $this->formatOutputSelector($this->selectorAppend($selectors));
    }

    /**
     * Append parts of the last selector in the list to the previous, recursively
     *
     * @param array $selectors
     *
     * @return array
     *
     * @throws \Leafo\ScssPhp\Exception\CompilerException
     */
    protected function selectorAppend($selectors)
    {
        $lastSelectors = array_pop($selectors);

        if (! $lastSelectors) {
            $this->throwError("Invalid selector list in selector-append()");
        }

        while (count($selectors)) {
            $previousSelectors = array_pop($selectors);

            if (! $previousSelectors) {
                $this->throwError("Invalid selector list in selector-append()");
            }

            // do the trick, happening $lastSelector to $previousSelector
            $appended = [];

            foreach ($lastSelectors as $lastSelector) {
                $previous = $previousSelectors;

                foreach ($lastSelector as $lastSelectorParts) {
                    foreach ($lastSelectorParts as $lastSelectorPart) {
                        foreach ($previous as $i => $previousSelector) {
                            foreach ($previousSelector as $j => $previousSelectorParts) {
                                $previous[$i][$j][] = $lastSelectorPart;
                            }
                        }
                    }
                }

                foreach ($previous as $ps) {
                    $appended[] = $ps;
                }
            }

            $lastSelectors = $appended;
        }

        return $lastSelectors;
    }

    protected static $libSelectorExtend = ['selectors', 'extendee', 'extender'];
    protected function libSelectorExtend($args)
    {
        list($selectors, $extendee, $extender) = $args;

        $selectors = $this->getSelectorArg($selectors);
        $extendee = $this->getSelectorArg($extendee);
        $extender = $this->getSelectorArg($extender);

        if (! $selectors || ! $extendee || ! $extender) {
            $this->throwError("selector-extend() invalid arguments");
        }

        $extended = $this->extendOrReplaceSelectors($selectors, $extendee, $extender);

        return $this->formatOutputSelector($extended);
    }

    protected static $libSelectorReplace = ['selectors', 'original', 'replacement'];
    protected function libSelectorReplace($args)
    {
        list($selectors, $original, $replacement) = $args;

        $selectors = $this->getSelectorArg($selectors);
        $original = $this->getSelectorArg($original);
        $replacement = $this->getSelectorArg($replacement);

        if (! $selectors || ! $original || ! $replacement) {
            $this->throwError("selector-replace() invalid arguments");
        }

        $replaced = $this->extendOrReplaceSelectors($selectors, $original, $replacement, true);

        return $this->formatOutputSelector($replaced);
    }

    /**
     * Extend/replace in selectors
     * used by selector-extend and selector-replace that use the same logic
     *
     * @param array   $selectors
     * @param array   $extendee
     * @param array   $extender
     * @param boolean $replace
     *
     * @return array
     */
    protected function extendOrReplaceSelectors($selectors, $extendee, $extender, $replace = false)
    {
        $saveExtends = $this->extends;
        $saveExtendsMap = $this->extendsMap;

        $this->extends = [];
        $this->extendsMap = [];

        foreach ($extendee as $es) {
            // only use the first one
            $this->pushExtends(reset($es), $extender, null);
        }

        $extended = [];

        foreach ($selectors as $selector) {
            if (! $replace) {
                $extended[] = $selector;
            }

            $n = count($extended);

            $this->matchExtends($selector, $extended);

            // if didnt match, keep the original selector if we are in a replace operation
            if ($replace and count($extended) === $n) {
                $extended[] = $selector;
            }
        }

        $this->extends = $saveExtends;
        $this->extendsMap = $saveExtendsMap;

        return $extended;
    }

    //protected static $libSelectorNest = ['selector...'];
    protected function libSelectorNest($args)
    {
        if (count($args) < 1) {
            $this->throwError("selector-nest() needs at least 1 argument");
        }

        $selectorsMap = array_map([$this, 'getSelectorArg'], $args);

        $envs = [];
        foreach ($selectorsMap as $selectors) {
            $env = new Environment();
            $env->selectors = $selectors;

            $envs[] = $env;
        }

        $envs = array_reverse($envs);
        $env = $this->extractEnv($envs);
        $outputSelectors = $this->multiplySelectors($env);

        return $this->formatOutputSelector($outputSelectors);
    }

    protected static $libSelectorParse = ['selectors'];
    protected function libSelectorParse($args)
    {
        $selectors = reset($args);
        $selectors = $this->getSelectorArg($selectors);

        return $this->formatOutputSelector($selectors);
    }

    protected static $libSelectorUnify = ['selectors1', 'selectors2'];
    protected function libSelectorUnify($args)
    {
        list($selectors1, $selectors2) = $args;

        $selectors1 = $this->getSelectorArg($selectors1);
        $selectors2 = $this->getSelectorArg($selectors2);

        if (! $selectors1 || ! $selectors2) {
            $this->throwError("selector-unify() invalid arguments");
        }

        // only consider the first compound of each
        $compound1 = reset($selectors1);
        $compound2 = reset($selectors2);

        // unify them and that's it
        $unified = $this->unifyCompoundSelectors($compound1, $compound2);

        return $this->formatOutputSelector($unified);
    }

    /**
     * The selector-unify magic as its best
     * (at least works as expected on test cases)
     *
     * @param array $compound1
     * @param array $compound2
     * @return array|mixed
     */
    protected function unifyCompoundSelectors($compound1, $compound2)
    {
        if (! count($compound1)) {
            return $compound2;
        }

        if (! count($compound2)) {
            return $compound1;
        }

        // check that last part are compatible
        $lastPart1 = array_pop($compound1);
        $lastPart2 = array_pop($compound2);
        $last = $this->mergeParts($lastPart1, $lastPart2);

        if (! $last) {
            return [[]];
        }

        $unifiedCompound = [$last];
        $unifiedSelectors = [$unifiedCompound];

        // do the rest
        while (count($compound1) || count($compound2)) {
            $part1 = end($compound1);
            $part2 = end($compound2);

            if ($part1 && ($match2 = $this->matchPartInCompound($part1, $compound2))) {
                list($compound2, $part2, $after2) = $match2;

                if ($after2) {
                    $unifiedSelectors = $this->prependSelectors($unifiedSelectors, $after2);
                }

                $c = $this->mergeParts($part1, $part2);
                $unifiedSelectors = $this->prependSelectors($unifiedSelectors, [$c]);
                $part1 = $part2 = null;

                array_pop($compound1);
            }

            if ($part2 && ($match1 = $this->matchPartInCompound($part2, $compound1))) {
                list($compound1, $part1, $after1) = $match1;

                if ($after1) {
                    $unifiedSelectors = $this->prependSelectors($unifiedSelectors, $after1);
                }

                $c = $this->mergeParts($part2, $part1);
                $unifiedSelectors = $this->prependSelectors($unifiedSelectors, [$c]);
                $part1 = $part2 = null;

                array_pop($compound2);
            }

            $new = [];

            if ($part1 && $part2) {
                array_pop($compound1);
                array_pop($compound2);

                $s = $this->prependSelectors($unifiedSelectors, [$part2]);
                $new = array_merge($new, $this->prependSelectors($s, [$part1]));
                $s = $this->prependSelectors($unifiedSelectors, [$part1]);
                $new = array_merge($new, $this->prependSelectors($s, [$part2]));
            } elseif ($part1) {
                array_pop($compound1);

                $new = array_merge($new, $this->prependSelectors($unifiedSelectors, [$part1]));
            } elseif ($part2) {
                array_pop($compound2);

                $new = array_merge($new, $this->prependSelectors($unifiedSelectors, [$part2]));
            }

            if ($new) {
                $unifiedSelectors = $new;
            }
        }

        return $unifiedSelectors;
    }

    /**
     * Prepend each selector from $selectors with $parts
     *
     * @param array $selectors
     * @param array $parts
     *
     * @return array
     */
    protected function prependSelectors($selectors, $parts)
    {
        $new = [];

        foreach ($selectors as $compoundSelector) {
            array_unshift($compoundSelector, $parts);

            $new[] = $compoundSelector;
        }

        return $new;
    }

    /**
     * Try to find a matching part in a compound:
     * - with same html tag name
     * - with some class or id or something in common
     *
     * @param array $part
     * @param array $compound
     *
     * @return array|boolean
     */
    protected function matchPartInCompound($part, $compound)
    {
        $partTag = $this->findTagName($part);
        $before = $compound;
        $after = [];

        // try to find a match by tag name first
        while (count($before)) {
            $p = array_pop($before);

            if ($partTag && $partTag !== '*' && $partTag == $this->findTagName($p)) {
                return [$before, $p, $after];
            }

            $after[] = $p;
        }

        // try again matching a non empty intersection and a compatible tagname
        $before = $compound;
        $after = [];

        while (count($before)) {
            $p = array_pop($before);

            if ($this->checkCompatibleTags($partTag, $this->findTagName($p))) {
                if (count(array_intersect($part, $p))) {
                    return [$before, $p, $after];
                }
            }

            $after[] = $p;
        }

        return false;
    }

    /**
     * Merge two part list taking care that
     * - the html tag is coming first - if any
     * - the :something are coming last
     *
     * @param array $parts1
     * @param array $parts2
     *
     * @return array
     */
    protected function mergeParts($parts1, $parts2)
    {
        $tag1 = $this->findTagName($parts1);
        $tag2 = $this->findTagName($parts2);
        $tag = $this->checkCompatibleTags($tag1, $tag2);

        // not compatible tags
        if ($tag === false) {
            return [];
        }

        if ($tag) {
            if ($tag1) {
                $parts1 = array_diff($parts1, [$tag1]);
            }

            if ($tag2) {
                $parts2 = array_diff($parts2, [$tag2]);
            }
        }

        $mergedParts = array_merge($parts1, $parts2);
        $mergedOrderedParts = [];

        foreach ($mergedParts as $part) {
            if (strpos($part, ':') === 0) {
                $mergedOrderedParts[] = $part;
            }
        }

        $mergedParts = array_diff($mergedParts, $mergedOrderedParts);
        $mergedParts = array_merge($mergedParts, $mergedOrderedParts);

        if ($tag) {
            array_unshift($mergedParts, $tag);
        }

        return $mergedParts;
    }

    /**
     * Check the compatibility between two tag names:
     * if both are defined they should be identical or one has to be '*'
     *
     * @param string $tag1
     * @param string $tag2
     *
     * @return array|boolean
     */
    protected function checkCompatibleTags($tag1, $tag2)
    {
        $tags = [$tag1, $tag2];
        $tags = array_unique($tags);
        $tags = array_filter($tags);

        if (count($tags)>1) {
            $tags = array_diff($tags, ['*']);
        }

        // not compatible nodes
        if (count($tags)>1) {
            return false;
        }

        return $tags;
    }

    /**
     * Find the html tag name in a selector parts list
     *
     * @param array $parts
     *
     * @return mixed|string
     */
    protected function findTagName($parts)
    {
        foreach ($parts as $part) {
            if (! preg_match('/^[\[.:#%_-]/', $part)) {
                return $part;
            }
        }

        return '';
    }

    protected static $libSimpleSelectors = ['selector'];
    protected function libSimpleSelectors($args)
    {
        $selector = reset($args);
        $selector = $this->getSelectorArg($selector);

        // remove selectors list layer, keeping the first one
        $selector = reset($selector);

        // remove parts list layer, keeping the first part
        $part = reset($selector);

        $listParts = [];

        foreach ($part as $p) {
            $listParts[] = [Type::T_STRING, '', [$p]];
        }

        return [Type::T_LIST, ',', $listParts];
    }
}
