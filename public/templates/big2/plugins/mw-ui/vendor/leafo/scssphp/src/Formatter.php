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

use Leafo\ScssPhp\Formatter\OutputBlock;
use Leafo\ScssPhp\SourceMap\SourceMapGenerator;

/**
 * Base formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
abstract class Formatter
{
    /**
     * @var integer
     */
    public $indentLevel;

    /**
     * @var string
     */
    public $indentChar;

    /**
     * @var string
     */
    public $break;

    /**
     * @var string
     */
    public $open;

    /**
     * @var string
     */
    public $close;

    /**
     * @var string
     */
    public $tagSeparator;

    /**
     * @var string
     */
    public $assignSeparator;

    /**
     * @var boolean
     */
    public $keepSemicolons;

    /**
     * @var \Leafo\ScssPhp\Formatter\OutputBlock
     */
    protected $currentBlock;

    /**
     * @var integer
     */
    protected $currentLine;

    /**
     * @var integer
     */
    protected $currentColumn;

    /**
     * @var \Leafo\ScssPhp\SourceMap\SourceMapGenerator
     */
    protected $sourceMapGenerator;

    /**
     * Initialize formatter
     *
     * @api
     */
    abstract public function __construct();

    /**
     * Return indentation (whitespace)
     *
     * @return string
     */
    protected function indentStr()
    {
        return '';
    }

    /**
     * Return property assignment
     *
     * @api
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return string
     */
    public function property($name, $value)
    {
        return rtrim($name) . $this->assignSeparator . $value . ';';
    }

    /**
     * Strip semi-colon appended by property(); it's a separator, not a terminator
     *
     * @api
     *
     * @param array $lines
     */
    public function stripSemicolon(&$lines)
    {
        if ($this->keepSemicolons) {
            return;
        }

        if (($count = count($lines))
            && substr($lines[$count - 1], -1) === ';'
        ) {
            $lines[$count - 1] = substr($lines[$count - 1], 0, -1);
        }
    }

    /**
     * Output lines inside a block
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $block
     */
    protected function blockLines(OutputBlock $block)
    {
        $inner = $this->indentStr();

        $glue = $this->break . $inner;

        $this->write($inner . implode($glue, $block->lines));

        if (! empty($block->children)) {
            $this->write($this->break);
        }
    }

    /**
     * Output block selectors
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $block
     */
    protected function blockSelectors(OutputBlock $block)
    {
        $inner = $this->indentStr();

        $this->write($inner
            . implode($this->tagSeparator, $block->selectors)
            . $this->open . $this->break);
    }

    /**
     * Output block children
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $block
     */
    protected function blockChildren(OutputBlock $block)
    {
        foreach ($block->children as $child) {
            $this->block($child);
        }
    }

    /**
     * Output non-empty block
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock $block
     */
    protected function block(OutputBlock $block)
    {
        if (empty($block->lines) && empty($block->children)) {
            return;
        }

        $this->currentBlock = $block;

        $pre = $this->indentStr();

        if (! empty($block->selectors)) {
            $this->blockSelectors($block);

            $this->indentLevel++;
        }

        if (! empty($block->lines)) {
            $this->blockLines($block);
        }

        if (! empty($block->children)) {
            $this->blockChildren($block);
        }

        if (! empty($block->selectors)) {
            $this->indentLevel--;

            if (empty($block->children)) {
                $this->write($this->break);
            }

            $this->write($pre . $this->close . $this->break);
        }
    }

    /**
     * Entry point to formatting a block
     *
     * @api
     *
     * @param \Leafo\ScssPhp\Formatter\OutputBlock             $block              An abstract syntax tree
     * @param \Leafo\ScssPhp\SourceMap\SourceMapGenerator|null $sourceMapGenerator Optional source map generator
     *
     * @return string
     */
    public function format(OutputBlock $block, SourceMapGenerator $sourceMapGenerator = null)
    {
        $this->sourceMapGenerator = null;

        if ($sourceMapGenerator) {
            $this->currentLine = 1;
            $this->currentColumn = 0;
            $this->sourceMapGenerator = $sourceMapGenerator;
        }

        ob_start();

        $this->block($block);

        $out = ob_get_clean();

        return $out;
    }

    /**
     * @param string $str
     */
    protected function write($str)
    {
        if ($this->sourceMapGenerator) {
            $this->sourceMapGenerator->addMapping(
                $this->currentLine,
                $this->currentColumn,
                $this->currentBlock->sourceLine,
                //columns from parser are off by one
                $this->currentBlock->sourceColumn > 0 ? $this->currentBlock->sourceColumn - 1 : 0,
                $this->currentBlock->sourceName
            );

            $lines = explode("\n", $str);
            $lineCount = count($lines);
            $this->currentLine += $lineCount-1;

            $lastLine = array_pop($lines);

            $this->currentColumn = ($lineCount === 1 ? $this->currentColumn : 0) + strlen($lastLine);
        }

        echo $str;
    }
}
