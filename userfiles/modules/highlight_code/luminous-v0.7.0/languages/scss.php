<?php
/**
 * The SCSS scanner is quite complex, having to deal with nested rules
 * and so forth and some disambiguation is non-trivial, so we are employing
 * a two-pass approach here - we first tokenize the source as normal with a 
 * scanner, then we parse the token stream with a parser to figure out
 * what various things really are.
 */
class LuminousSCSSScanner extends LuminousScanner {
    private $regexen = array();
    
    public $rule_tag_map = array(
        'PROPERTY' => 'TYPE',
        'COMMENT_SL' => 'COMMENT',
        'COMMENT_ML' => 'COMMENT',
        'ELEMENT_SELECTOR' => 'KEYWORD',
        'STRING_S' => 'STRING',
        'STRING_D' => 'STRING',
        'CLASS_SELECTOR' => 'VARIABLE',
        'ID_SELECTOR' => 'VARIABLE',
        'PSEUDO_SELECTOR' => 'OPERATOR',
        'ATTR_SELECTOR' => 'OPERATOR',
        'WHITESPACE' => null,
        'COLON' => 'OPERATOR',
        'SEMICOLON' => 'OPERATOR',
        'COMMA' => 'OPERATOR',
        'R_BRACE' => 'OPERATOR', 
        'R_BRACKET' => 'OPERATOR',
        'R_SQ_BRACKET' => 'OPERATOR',
        'L_BRACE' => 'OPERATOR', 
        'L_BRACKET' => 'OPERATOR',
        'L_SQ_BRACKET' => 'OPERATOR',
        'OTHER_OPERATOR' => 'OPERATOR',
        'GENERIC_IDENTIFIER' => null,
        'AT_IDENTIFIER' => 'KEYWORD',
        'IMPORTANT' => 'KEYWORD',
    );
    
    public function init() {
        $this->regexen = array(
            // For the first pass we just feed in a bunch of tokens.
            // Some of these are generic and will require disambiguation later
            'COMMENT_SL' => LuminousTokenPresets::$C_COMMENT_SL,
            'COMMENT_ML' =>  LuminousTokenPresets::$C_COMMENT_ML,
            'STRING_S' => LuminousTokenPresets::$SINGLE_STR,
            'STRING_D' => LuminousTokenPresets::$DOUBLE_STR,
            // TODO check var naming, is $1 a legal variable?
            'VARIABLE' => '%\$[\-a-z_0-9]+ | \#\{\$[\-a-z_0-9]+\} %x',
            'AT_IDENTIFIER' => '%@[a-zA-Z0-9]+%',
            
            // This is generic - it may be a selector fragment, a rule, or
            // even a hex colour.
            'GENERIC_IDENTIFIER' => '@
                \\#[a-fA-F0-9]{3}(?:[a-fA-F0-9]{3})?
                |
                [0-9]+(\.[0-9]+)?(\w+|%|in|cm|mm|em|ex|pt|pc|px|s)?
                |
                -?[a-zA-Z_\-0-9]+[a-zA-Z_\-0-9]*
                |&
            @x',
            'IMPORTANT' => '/!important/',
            'L_BRACE' => '/\{/',
            'R_BRACE' => '/\}/',
            'L_SQ_BRACKET' => '/\[/',
            'R_SQ_BRACKET' => '/\]/',
            'L_BRACKET' => '/\(/',
            'R_BRACKET' => '/\)/',
            
            'DOUBLE_COLON' => '/::/',
            'COLON' => '/:/',
            'SEMICOLON' => '/;/',
            
            'DOT' => '/\./',
            'HASH' => '/#/',
            
            'COMMA' => '/,/',
            
            'OTHER_OPERATOR' => '@[+\-*/%&>=!]@',

            'WHITESPACE' => '/\s+/'
        );
    }
    
    
    public function main() {
        while (!$this->eos()) {
            $m = null;
            foreach($this->regexen as $token=>$pattern) {
                if ( ($m = $this->scan($pattern)) !== null) {
                    $this->record($m, $token);
                    break;
                }
            }
            if ($m === null) {
                $this->record($this->get(), null);
            }
        }
        $parser = new LuminousSASSParser();
        $parser->tokens = $this->tokens;
        $parser->parse();
        $this->tokens = $parser->tokens;
    }
}
/**
 * The parsing class 
 */
class LuminousSASSParser {
    
    public $tokens;
    public $index;
    public $stack;
    static $delete_token = 'delete';
    
    /**
     * Returns true if the next token is the given token name
     * optionally skipping whitespace
     */
    function next_is($token_name, $ignore_whitespace = false) {
        $i = $this->index+1;
        $len = count($this->tokens);
        while($i<$len) {
            $tok = $this->tokens[$i][0];
            if ($ignore_whitespace && $tok === 'WHITESPACE') {
                $i++;
            }
            else {
                return $tok === $token_name;
            }
        }
        return false;
    }
    /**
     * Returns the index of the next match of the sequence of tokens
     * given, optionally ignoring ertain tokens
     */
    function next_sequence($sequence, $ignore=array()) {
        $i = $this->index+1;
        $len = count($this->tokens);
        $seq_len = count($sequence);
        $seq = 0;
        $seq_start = 0;
        while ($i<$len) {
            $tok = $this->tokens[$i][0];
            if ($tok === $sequence[$seq]) {
                if ($seq === 0) $seq_start = $i;
                $seq++;
                $i++;
                if ($seq === $seq_len) {
                    return $seq_start;
                }
            } else {
                if (in_array($tok, $ignore)) {}
                else {
                    $seq = 0;
                }
                $i++;
            }
        }
        return $len;
    }

    /**
     * Returns the first token which occurs out of the set of given tokens
     */
    function next_of($token_names) {
        $i = $this->index+1;
        $len = count($this->tokens);
        while ($i<$len) {
            $tok = $this->tokens[$i][0];
            if (in_array($tok, $token_names)) {
                return $tok;
            }
            $i++;
        }
        return null;
        
    }
    /**
     * Returns the index of the next token with the given token name
     */
    function next_of_type($token_name) {
        $i = $this->index+1;
        $len = count($this->tokens);
        while($i<$len) {
            $tok = $this->tokens[$i][0];
            if ($tok === $token_name) {
                return $i;
            }
            $i++;
        }
        return $len;
    }
    
    private function _parse_identifier($token) {
        $val = $token[1];
        $c = isset($val[0])? $val[0] : '';
        if (ctype_digit($c) || $c === '#') {
            $token[0] = 'NUMERIC';
        }
    }
    
    /**
    * Parses a selector rule 
    */
    private function _parse_rule() {  
        $new_token = $this->tokens[$this->index];
        $set = false;
        if ($this->index > 0) {
            $prev_token = &$this->tokens[$this->index-1];
            $prev_token_type = &$prev_token[0];
            $prev_token_text = &$prev_token[1];
            $concat = false;
            
            $map = array(
                'DOT' => 'CLASS_SELECTOR',
                'HASH' => 'ID_SELECTOR',
                'COLON' => 'PSEUDO_SELECTOR',
                'DOUBLE_COLON' => 'PSEUDO_SELECTOR'
            );
            if (isset($map[$prev_token_type])) {
                // mark the prev token for deletion and concat into one.
                $new_token[0] = $map[$prev_token_type];
                $prev_token_type = self::$delete_token;
                $new_token[1] = $prev_token_text . $new_token[1];
                $set = true;
            }
        }
        if (!$set) {
            // must be an element
            $new_token[0] = 'ELEMENT_SELECTOR';
        }
        $this->tokens[$this->index] = $new_token;
    }
    
    /**
     * Cleans up the token stream by deleting any tokens marked for 
     * deletion, and makes sure the array is continuous afterwards.
     */
    private function _cleanup() {
        foreach($this->tokens as $i=>$t) {
            if ($t[0] === self::$delete_token) {
                unset($this->tokens[$i]);
            }
        }
        $this->tokens = array_values($this->tokens);
    }
    /**
     * Main parsing function
     */
    public function parse() {
        $new_tokens = array();
        $len = count($this->tokens);
        $this->stack = array();
        $prop_value = 'PROPERTY';
        $pushes = array(
            'L_BRACKET' => 'bracket',
            'L_BRACE' => 'brace',
            'AT_IDENTIFIER' => 'at',
            'L_SQ_BRACKET' => 'square'
        );
        $pops = array(
            'R_BRACKET' => 'bracket',
            'R_BRACE' => 'brace',
            'R_SQ_BRACKET' => 'square'
        );
        $this->index = 0;
        while($this->index < $len) {
            $token = &$this->tokens[$this->index];
            $stack_size = count($this->stack);
            $state = !$stack_size? null : $this->stack[$stack_size-1];
            $tok_name = &$token[0];
            $in_brace = in_array('brace', $this->stack);
            $in_bracket = in_array('bracket', $this->stack);
            $in_sq = in_array('square', $this->stack);
            $in_at = in_array('at', $this->stack);
            if ($tok_name === self::$delete_token) continue;
            
            if ($tok_name === 'L_BRACE') {
                if ($state === 'at') {
                    array_pop($this->stack);
                }
                $this->stack[] = $pushes[$tok_name];
                $prop_value = 'PROPERTY';
            }
            elseif (isset($pushes[$tok_name])) {
                $this->stack[] = $pushes[$tok_name];
            } else if (isset($pops[$tok_name]) && $state === $pops[$tok_name]) {
                array_pop($this->stack);
            }
            elseif (!$in_bracket && $tok_name === 'COLON') {
                $prop_value = 'VALUE';
            }
            elseif ($tok_name === 'SEMICOLON') {
                $prop_value = 'PROPERTY';
                if ($state === 'at') array_pop($this->stack);
            }
            elseif ($tok_name === 'GENERIC_IDENTIFIER') {
                // this is where the fun starts.
                // we have to figure out exactly what this is
                // if we can look ahead and find a '{' before we find a 
                // ';', then this is part of a selector.
                // Otherwise it's part of a property/value pair.
                // the exception is when we have something like:
                // font : { family : sans-serif; } 
                // then we need to check for ':{'
                if ($in_sq) {
                    $token[0] = 'ATTR_SELECTOR';
                }
                else if ($in_bracket) {
                    $this->_parse_identifier($token);
                }
                elseif(!$in_at) {
                    $semi = $this->next_of_type('SEMICOLON');
                    $colon_brace = $this->next_sequence(array('COLON', 'L_BRACE'),
                        array('WHITESPACE'));
                    $brace = $this->next_of_type('L_BRACE');
                    
                    $rule_terminator = min($semi, $colon_brace);
                    if ($brace < $rule_terminator) {
                        $this->_parse_rule();
                        $prop_value = 'PROPERTY';
                    } else {
                        $this->tokens[$this->index][0] = $prop_value;
                        if ($prop_value === 'VALUE') {
                            $this->_parse_identifier($token);
                        }
                    }
                }
                
            }
            $this->index++;
        }
        $this->_cleanup();
    }
}