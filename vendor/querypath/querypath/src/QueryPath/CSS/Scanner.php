<?php
/** @file
 * The scanner.
 */
namespace QueryPath\CSS;
/**
 * Scanner for CSS selector parsing.
 *
 * This provides a simple scanner for traversing an input stream.
 *
 * @ingroup querypath_css
 */
final class Scanner {
  var $is = NULL;
  public $value = NULL;
  public $token = NULL;

  var $recurse = FALSE;
  var $it = 0;

  /**
   * Given a new input stream, tokenize the CSS selector string.
   * @see InputStream
   * @param InputStream $in
   *  An input stream to be scanned.
   */
  public function __construct(InputStream $in) {
    $this->is = $in;
  }

  /**
   * Return the position of the reader in the string.
   */
  public function position() {
    return $this->is->position;
  }

  /**
   * See the next char without removing it from the stack.
   *
   * @return char
   *  Returns the next character on the stack.
   */
  public function peek() {
    return $this->is->peek();
  }

  /**
   * Get the next token in the input stream.
   *
   * This sets the current token to the value of the next token in
   * the stream.
   *
   * @return int
   *  Returns an int value corresponding to one of the Token constants,
   *  or FALSE if the end of the string is reached. (Remember to use
   *  strong equality checking on FALSE, since 0 is a valid token id.)
   */
  public function nextToken() {
    $tok = -1;
    ++$this->it;
    if ($this->is->isEmpty()) {
      if ($this->recurse) {
        throw new \QueryPath\Exception("Recursion error detected at iteration " . $this->it . '.');
        exit();
      }
      //print "{$this->it}: All done\n";
      $this->recurse = TRUE;
      $this->token = FALSE;
      return FALSE;
    }
    $ch = $this->is->consume();
    //print __FUNCTION__ . " Testing $ch.\n";
    if (ctype_space($ch)) {
      $this->value = ' '; // Collapse all WS to a space.
      $this->token = $tok = Token::white;
      //$ch = $this->is->consume();
      return $tok;
    }

    if (ctype_alnum($ch) || $ch == '-' || $ch == '_') {
      // It's a character
      $this->value = $ch; //strtolower($ch);
      $this->token = $tok = Token::char;
      return $tok;
    }

    $this->value = $ch;

    switch($ch) {
      case '*':
        $tok = Token::star;
        break;
      case chr(ord('>')):
        $tok = Token::rangle;
        break;
      case '.':
        $tok = Token::dot;
        break;
      case '#':
        $tok = Token::octo;
        break;
      case '[':
        $tok = Token::lsquare;
        break;
      case ']':
        $tok = Token::rsquare;
        break;
      case ':':
        $tok = Token::colon;
        break;
      case '(':
        $tok = Token::lparen;
        break;
      case ')':
        $tok = Token::rparen;
        break;
      case '+':
        $tok = Token::plus;
        break;
      case '~':
        $tok = Token::tilde;
        break;
      case '=':
        $tok = Token::eq;
        break;
      case '|':
        $tok = Token::pipe;
        break;
      case ',':
        $tok = Token::comma;
        break;
      case chr(34):
        $tok = Token::quote;
        break;
      case "'":
        $tok = Token::squote;
        break;
      case '\\':
        $tok = Token::bslash;
        break;
      case '^':
        $tok = Token::carat;
        break;
      case '$':
        $tok = Token::dollar;
        break;
      case '@':
        $tok = Token::at;
        break;
    }


    // Catch all characters that are legal within strings.
    if ($tok == -1) {
      // TODO: This should be UTF-8 compatible, but PHP doesn't
      // have a native UTF-8 string. Should we use external
      // mbstring library?

      $ord = ord($ch);
      // Characters in this pool are legal for use inside of
      // certain strings. Extended ASCII is used here, though I
      // Don't know if these are really legal.
      if (($ord >= 32 && $ord <= 126) || ($ord >= 128 && $ord <= 255)) {
        $tok = Token::stringLegal;
      }
      else {
        throw new ParseException('Illegal character found in stream: ' . $ord);
      }
    }

    $this->token = $tok;
    return $tok;
  }

  /**
   * Get a name string from the input stream.
   * A name string must be composed of
   * only characters defined in Token:char: -_a-zA-Z0-9
   */
  public function getNameString() {
    $buf = '';
    while ($this->token === Token::char) {
      $buf .= $this->value;
      $this->nextToken();
      //print '_';
    }
    return $buf;
  }

  /**
   * This gets a string with any legal 'string' characters.
   * See CSS Selectors specification, section 11, for the
   * definition of string.
   *
   * This will check for string1, string2, and the case where a
   * string is unquoted (Oddly absent from the "official" grammar,
   * though such strings are present as examples in the spec.)
   *
   * Note:
   * Though the grammar supplied by CSS 3 Selectors section 11 does not
   * address the contents of a pseudo-class value, the spec itself indicates
   * that a pseudo-class value is a "value between parenthesis" [6.6]. The
   * examples given use URLs among other things, making them closer to the
   * definition of 'string' than to 'name'. So we handle them here as strings.
   */
  public function getQuotedString() {
    if ($this->token == Token::quote || $this->token == Token::squote || $this->token == Token::lparen) {
      $end = ($this->token == Token::lparen) ? Token::rparen : $this->token;
      $buf = '';
      $escape = FALSE;

      $this->nextToken(); // Skip the opening quote/paren

      // The second conjunct is probably not necessary.
      while ($this->token !== FALSE && $this->token > -1) {
        //print "Char: $this->value \n";
        if ($this->token == Token::bslash && !$escape) {
          // XXX: The backslash (\) is removed here.
          // Turn on escaping.
          //$buf .= $this->value;
          $escape = TRUE;
        }
        elseif ($escape) {
          // Turn off escaping
          $buf .= $this->value;
          $escape = FALSE;
        }
        elseif ($this->token === $end) {
          // At end of string; skip token and break.
          $this->nextToken();
          break;
        }
        else {
          // Append char.
          $buf .= $this->value;
        }
        $this->nextToken();
      }
      return $buf;
    }
  }

  /**
   * Get a string from the input stream.
   * This is a convenience function for getting a string of
   * characters that are either alphanumber or whitespace. See
   * the Token::white and Token::char definitions.
   *
   * @deprecated This is not used anywhere in QueryPath.
   *//*
  public function getStringPlusWhitespace() {
    $buf = '';
    if($this->token === FALSE) {return '';}
    while ($this->token === Token::char || $this->token == Token::white) {
      $buf .= $this->value;
      $this->nextToken();
    }
    return $buf;
  }*/

}
