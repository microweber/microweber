<?php

namespace Microweber\Utils;


///*
// * Huffman code decoding tables.  count[1..MAXBITS] is the number of symbols of
// * each length, which for a canonical code are stepped through in order.
// * symbol[] are the symbol values in canonical order, where the number of
// * entries is the sum of the counts in count[].  The decoding process can be
// * seen in the function decode() below.
// */
//struct huffman {
//    short *count;       /* number of symbols of each length */
//    short *symbol;      /* canonically ordered symbols */
//};	  

define('MAXBITS', 15);              /* maximum bits in a code */
		
class HuffmanTable {
	var $count;
	var $symbol;
	var $error = null;

	/**
	 * Given the list of code lengths length[0..n-1] representing a canonical
	 * Huffman code for n symbols, construct the tables required to decode those
	 * codes.  Those tables are the number of codes of each length, and the symbols
	 * sorted by length, retaining their original order within each length.  The
	 * return value is zero for a complete code set, negative for an over-
	 * subscribed code set, and positive for an incomplete code set.  The tables
	 * can be used if the return value is zero or positive, but they cannot be used
	 * if the return value is negative.  If the return value is zero, it is not
	 * possible for decode() using that table to return an error--any stream of
	 * enough bits will resolve to a symbol.  If the return value is positive, then
	 * it is possible for decode() using that table to return an error for received
	 * codes past the end of the incomplete lengths.
	 *
	 * Not used by decode(), but used for error checking, h->count[0] is the number
	 * of the n symbols not in the code.  So n - h->count[0] is the number of
	 * codes.  This is useful for checking for incomplete codes that have more than
	 * one symbol, which is an error in a dynamic block.
	 *
	 * Assumption: for all i in 0..n-1, 0 <= length[i] <= MAXBITS
	 * This is assured by the construction of the length arrays in dynamic() and
	 * fixed() and is not verified by construct().
	 *
	 * Format notes:
	 *
	 * - Permitted and expected examples of incomplete codes are one of the fixed
	 *   codes and any code with a single symbol which in deflate is coded as one
	 *   bit instead of zero bits.  See the format notes for fixed() and dynamic().
	 *
	 * - Within a given code length, the symbols are kept in ascending order for
	 *   the code bits definition.
	 */
	function HuffmanTable($length, $n) {
	  //int symbol;         /* current symbol when stepping through length[] */
	  //int len;            /* current length when stepping through h->count[] */
	  //int left;           /* number of possible codes left of current length */
	  //short offs[MAXBITS+1];      /* offsets in symbol table for each length */
		$this->count = array();
		$this->symbol = array();
		
	  /* count number of codes of each length */
	  for ($len = 0; $len <= MAXBITS; $len++)
	      $this->count[$len] = 0;
	  for ($symbol = 0; $symbol < $n; $symbol++)
	      $this->count[$length[$symbol]]++;   /* assumes lengths are within bounds */
	  if ($this->count[0] == $n) {              /* no codes! */
			$this->error = 0;                       /* complete, but decode() will fail */
	  }
	  else {
		  /* check for an over-subscribed or incomplete set of lengths */
		  $left = 1;                           /* one possible code of zero length */
		  for ($len = 1; $len <= MAXBITS; $len++) {
	      $left <<= 1;                     /* one more bit, double codes left */
	      $left -= $this->count[$len];          /* deduct count from possible codes */
	      if ($left < 0) {
	      	$this->error = $left;      /* over-subscribed--return negative */
	      	break;
	      }
		  }                                   /* left > 0 means incomplete */
		
		  if ($left >= 0) {
			  /* generate offsets into symbol table for each length for sorting */
			  $offs[1] = 0;
			  for ($len = 1; $len < MAXBITS; $len++)
					$offs[$len + 1] = $offs[$len] + $this->count[$len];
			
			  /*
			   * put symbols in table sorted by length, by symbol order within each
			   * length
			   */
			  for ($symbol = 0; $symbol < $n; $symbol++)
					if ($length[$symbol] != 0) $this->symbol[$offs[$length[$symbol]]++] = $symbol;
			
			  /* return zero for complete set, positive for incomplete set */
			  $this->error = $left;
		  }
	  }
	}
}
?>