<?php
/*
 * ZlibDecompress.php
 * (c) 2007 Emanuele Iannone <emanuele@fondani.it>
 *
 * This class was written from original C++ source code by Mark Adler (http://zlib.net/)
 * The original source is puff.c, included in the contrib/puff directory of Zlib source code.
 *
 * ORIGINAL COMMENTS:
 *
 * Copyright (C) 2002-2004 Mark Adler
 * version 1.8, 9 Jan 2004
 *
 * puff.c is a simple inflate written to be an unambiguous way to specify the
 * deflate format.  It is not written for speed but rather simplicity.  As a
 * side benefit, this code might actually be useful when small code is more
 * important than speed, such as bootstrap applications.  For typical deflate
 * data, zlib's inflate() is about four times as fast as puff().  zlib's
 * inflate compiles to around 20K on my machine, whereas puff.c compiles to
 * around 4K on my machine (a PowerPC using GNU cc).  If the faster decode()
 * function here is used, then puff() is only twice as slow as zlib's
 * inflate().
 *
 * In the comments below are "Format notes" that describe the inflate process
 * and document some of the less obvious aspects of the format.  This source
 * code is meant to supplement RFC 1951, which formally describes the deflate
 * format:
 *
 *    http://www.zlib.org/rfc-deflate.html
 */

require_once("HuffmanTable.php");

/*
 * Maximums for allocations and loops.  It is not useful to change these --
 * they are fixed by the deflate format.
 */
define('MAXLCODES', 286);                 /* maximum number of literal/length codes */
define('MAXDCODES', 30);                  /* maximum number of distance codes */
define('MAXCODES', (MAXLCODES+MAXDCODES));/* maximum codes lengths to read */
define('FIXLCODES', 288);                 /* number of fixed literal/length codes */

/* input and output state */
class ZlibDecompress {
	/* output state */
	var $out; //unsigned char *out;         /* output buffer */
	//var $outlen; //unsigned long outlen;       /* available space at out */
	var $outcnt; //unsigned long outcnt;       /* bytes written to out so far */
	
	/* input state */
	var $in; //unsigned char *in;          /* input buffer */
	var $inlen; //unsigned long inlen;        /* available input at in */
	var $incnt; //unsigned long incnt;        /* bytes read so far */
	var $bitbuf; //int bitbuf;                 /* bit buffer */
	var $bitcnt; //int bitcnt;                 /* number of bits in bit buffer */
	
	var $fixed_lencode = null;
	var $fixed_distcode = null;

  var $lens = array( /* Size base for length codes 257..285 */
        3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 15, 17, 19, 23, 27, 31,
        35, 43, 51, 59, 67, 83, 99, 115, 131, 163, 195, 227, 258);
  var $lext = array( /* Extra bits for length codes 257..285 */
        0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2,
        3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 0);
  var $dists = array( /* Offset base for distance codes 0..29 */
        1, 2, 3, 4, 5, 7, 9, 13, 17, 25, 33, 49, 65, 97, 129, 193,
        257, 385, 513, 769, 1025, 1537, 2049, 3073, 4097, 6145,
        8193, 12289, 16385, 24577);
  var $dext = array( /* Extra bits for distance codes 0..29 */
        0, 0, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6,
        7, 7, 8, 8, 9, 9, 10, 10, 11, 11,
        12, 12, 13, 13);
        
  var $order = array(      /* permutation of code length codes */
      16, 17, 18, 0, 8, 7, 9, 6, 10, 5, 11, 4, 12, 3, 13, 2, 14, 1, 15);

	/**
	 * Inflate source to dest.  On return, destlen and sourcelen are updated to the
	 * size of the uncompressed data and the size of the deflate data respectively.
	 * On success, the return value of puff() is zero.  If there is an error in the
	 * source data, i.e. it is not in the deflate format, then a negative value is
	 * returned.  If there is not enough input available or there is not enough
	 * output space, then a positive error is returned.  In that case, destlen and
	 * sourcelen are not updated to facilitate retrying from the beginning with the
	 * provision of more input data or more output space.  In the case of invalid
	 * inflate data (a negative error), the dest and source pointers are updated to
	 * facilitate the debugging of deflators.
	 *
	 * puff() also has a mode to determine the size of the uncompressed output with
	 * no output written.  For this dest must be (unsigned char *)0.  In this case,
	 * the input value of *destlen is ignored, and on return *destlen is set to the
	 * size of the uncompressed output.
	 *
	 * The return codes are:
	 *
	 *   2:  available inflate data did not terminate
	 *   1:  output space exhausted before completing inflate
	 *   0:  successful inflate
	 *  -1:  invalid block type (type == 3)
	 *  -2:  stored block length did not match one's complement
	 *  -3:  dynamic block code description: too many length or distance codes
	 *  -4:  dynamic block code description: code lengths codes incomplete
	 *  -5:  dynamic block code description: repeat lengths with no first length
	 *  -6:  dynamic block code description: repeat more than specified lengths
	 *  -7:  dynamic block code description: invalid literal/length code lengths
	 *  -8:  dynamic block code description: invalid distance code lengths
	 *  -9:  invalid literal/length or distance code in fixed or dynamic block
	 * -10:  distance is too far back in fixed or dynamic block
	 *
	 * Format notes:
	 *
	 * - Three bits are read for each block to determine the kind of block and
	 *   whether or not it is the last block.  Then the block is decoded and the
	 *   process repeated if it was not the last block.
	 *
	 * - The leftover bits in the last byte of the deflate data after the last
	 *   block (if it was a fixed or dynamic block) are undefined and have no
	 *   expected values to check.
	 *
	 */      
	function inflate($compressed_data) {
    /* initialize output state */
    $this->out = "";
    $this->outcnt = 0;

    /* initialize input state */
    $this->in = $compressed_data;
    $this->inlen = strlen($compressed_data);
    $this->incnt = 0;
    $this->bitbuf = 0;
    $this->bitcnt = 0;
 
	  /* process blocks until last block or error */
	  do {
			$last = $this->bits(1);         /* one if last block */
			$type = $this->bits(2);         /* block type 0..3 */
			$err = $type == 0 ? $this->stored() :
						($type == 1 ? $this->fixed() :
			      ($type == 2 ? $this->dynamic() :
						-1));               /* type == 3, invalid */
			if ($err != 0) break;        /* return with error */
	  } 
	  while (!$last);
	
	  /* update the lengths and return */
	  if ($err <= 0) {
			return $this->out;
	  }
	  else {
	  	return false;
	  }
  }
      
	/**
	 * Return $need bits from the input stream.  This always leaves less than
	 * eight bits in the buffer.  bits() works properly for need == 0.
	 *
	 * Format notes:
	 *
	 * - Bits are stored in bytes from the least significant bit to the most
	 *   significant bit.  Therefore bits are dropped from the bottom of the bit
	 *   buffer, using shift right, and new bytes are appended to the top of the
	 *   bit buffer, using shift left.
	 *
	 * @param $need int
	 * @return int
	 */
	function bits($need) {
		/* load at least $need bits into val */
		$val = $this->bitbuf; /* bit accumulator (can use up to 20 bits) */
		while ($this->bitcnt < $need) {
	    if ($this->incnt == $this->inlen) {
	    	trigger_error("Out of input", E_USER_ERROR);
	    }
	    $val |= ord($this->in[$this->incnt++]) << $this->bitcnt;  /* load eight bits */
	    $this->bitcnt += 8;
		}
		
		/* drop need bits and update buffer, always zero to seven bits left */
		$this->bitbuf = $val >> $need;
		$this->bitcnt -= $need;
		
		/* return need bits, zeroing the bits above that */
		return (int)($val & ((1 << $need) - 1));
	}
	
	/**
	 * Process a stored block.
	 *
	 * Format notes:
	 *
	 * - After the two-bit stored block type (00), the stored block length and
	 *   stored bytes are byte-aligned for fast copying.  Therefore any leftover
	 *   bits in the byte that has the last bit of the type, as many as seven, are
	 *   discarded.  The value of the discarded bits are not defined and should not
	 *   be checked against any expectation.
	 *
	 * - The second inverted copy of the stored block length does not have to be
	 *   checked, but it's probably a good idea to do so anyway.
	 *
	 * - A stored block can have zero length.  This is sometimes used to byte-align
	 *   subsets of the compressed data for random access or partial recovery.
	 */
	function stored() {  
		/* discard leftover bits from current byte (assumes s->bitcnt < 8) */
		$this->bitbuf = 0;
		$this->bitcnt = 0;
		
		/* get length and check against its one's complement */
		if ($this->incnt + 4 > $this->inlen) return 2;  /* not enough input */
		
		$len = $this->in[$this->incnt++]; /* length of stored block */
		$len |= $this->in[$this->incnt++] << 8;
		if ($this->in[$this->incnt++] != (~$len & 0xff) ||
				$this->in[$this->incnt++] != ((~$len >> 8) & 0xff))
				return -2;                              /* didn't match complement! */
		
		/* copy len bytes from in to out */
		if ($this->incnt + $len > $this->inlen) return 2;    /* not enough input */
		while ($len--) $this->out[$this->outcnt++] = $this->in[$this->incnt++];
		
		/* done with a valid stored block */
		return 0;
	}

	/**
	 * Process a fixed codes block.
	 *
	 * Format notes:
	 *
	 * - This block type can be useful for compressing small amounts of data for
	 *   which the size of the code descriptions in a dynamic block exceeds the
	 *   benefit of custom codes for that block.  For fixed codes, no bits are
	 *   spent on code descriptions.  Instead the code lengths for literal/length
	 *   codes and distance codes are fixed.  The specific lengths for each symbol
	 *   can be seen in the "for" loops below.
	 *
	 * - The literal/length code is complete, but has two symbols that are invalid
	 *   and should result in an error if received.  This cannot be implemented
	 *   simply as an incomplete code since those two symbols are in the "middle"
	 *   of the code.  They are eight bits long and the longest literal/length\
	 *   code is nine bits.  Therefore the code must be constructed with those
	 *   symbols, and the invalid symbols must be detected after decoding.
	 *
	 * - The fixed distance codes also have two invalid symbols that should result
	 *   in an error if received.  Since all of the distance codes are the same
	 *   length, this can be implemented as an incomplete code.  Then the invalid
	 *   codes are detected while decoding.
	 */
	function fixed() {
    /* build fixed huffman tables if first call (may not be thread safe) */
    if ($this->fixed_lencode == null) {
      $lengths = array(); // short lengths[FIXLCODES];
      
      /* literal/length table */
      for ($symbol = 0; $symbol < 144; $symbol++)
          $lengths[$symbol] = 8;
      for (; $symbol < 256; $symbol++)
          $lengths[$symbol] = 9;
      for (; $symbol < 280; $symbol++)
          $lengths[$symbol] = 7;
      for (; $symbol < FIXLCODES; $symbol++)
          $lengths[$symbol] = 8;
          
      $this->fixed_lencode = new HuffmanTable($lengths, FIXLCODES);

      /* distance table */
      for ($symbol = 0; $symbol < MAXDCODES; $symbol++) 
      	$lengths[$symbol] = 5;
      
      $this->fixed_distcode = new HuffmanTable($lengths, MAXDCODES);
    }

    /* decode data until end-of-block code */
    return $this->codes($this->fixed_lencode, $this->fixed_distcode);
	}
	
	/*
	 * Process a dynamic codes block.
	 *
	 * Format notes:
	 *
	 * - A dynamic block starts with a description of the literal/length and
	 *   distance codes for that block.  New dynamic blocks allow the compressor to
	 *   rapidly adapt to changing data with new codes optimized for that data.
	 *
	 * - The codes used by the deflate format are "canonical", which means that
	 *   the actual bits of the codes are generated in an unambiguous way simply
	 *   from the number of bits in each code.  Therefore the code descriptions
	 *   are simply a list of code lengths for each symbol.
	 *
	 * - The code lengths are stored in order for the symbols, so lengths are
	 *   provided for each of the literal/length symbols, and for each of the
	 *   distance symbols.
	 *
	 * - If a symbol is not used in the block, this is represented by a zero as
	 *   as the code length.  This does not mean a zero-length code, but rather
	 *   that no code should be created for this symbol.  There is no way in the
	 *   deflate format to represent a zero-length code.
	 *
	 * - The maximum number of bits in a code is 15, so the possible lengths for
	 *   any code are 1..15.
	 *
	 * - The fact that a length of zero is not permitted for a code has an
	 *   interesting consequence.  Normally if only one symbol is used for a given
	 *   code, then in fact that code could be represented with zero bits.  However
	 *   in deflate, that code has to be at least one bit.  So for example, if
	 *   only a single distance base symbol appears in a block, then it will be
	 *   represented by a single code of length one, in particular one 0 bit.  This
	 *   is an incomplete code, since if a 1 bit is received, it has no meaning,
	 *   and should result in an error.  So incomplete distance codes of one symbol
	 *   should be permitted, and the receipt of invalid codes should be handled.
	 *
	 * - It is also possible to have a single literal/length code, but that code
	 *   must be the end-of-block code, since every dynamic block has one.  This
	 *   is not the most efficient way to create an empty block (an empty fixed
	 *   block is fewer bits), but it is allowed by the format.  So incomplete
	 *   literal/length codes of one symbol should also be permitted.
	 *
	 * - If there are only literal codes and no lengths, then there are no distance
	 *   codes.  This is represented by one distance code with zero bits.
	 *
	 * - The list of up to 286 length/literal lengths and up to 30 distance lengths
	 *   are themselves compressed using Huffman codes and run-length encoding.  In
	 *   the list of code lengths, a 0 symbol means no code, a 1..15 symbol means
	 *   that length, and the symbols 16, 17, and 18 are run-length instructions.
	 *   Each of 16, 17, and 18 are follwed by extra bits to define the length of
	 *   the run.  16 copies the last length 3 to 6 times.  17 represents 3 to 10
	 *   zero lengths, and 18 represents 11 to 138 zero lengths.  Unused symbols
	 *   are common, hence the special coding for zero lengths.
	 *
	 * - The symbols for 0..18 are Huffman coded, and so that code must be
	 *   described first.  This is simply a sequence of up to 19 three-bit values
	 *   representing no code (0) or the code length for that symbol (1..7).
	 *
	 * - A dynamic block starts with three fixed-size counts from which is computed
	 *   the number of literal/length code lengths, the number of distance code
	 *   lengths, and the number of code length code lengths (ok, you come up with
	 *   a better name!) in the code descriptions.  For the literal/length and
	 *   distance codes, lengths after those provided are considered zero, i.e. no
	 *   code.  The code length code lengths are received in a permuted order (see
	 *   the order[] array below) to make a short code length code length list more
	 *   likely.  As it turns out, very short and very long codes are less likely
	 *   to be seen in a dynamic code description, hence what may appear initially
	 *   to be a peculiar ordering.
	 *
	 * - Given the number of literal/length code lengths (nlen) and distance code
	 *   lengths (ndist), then they are treated as one long list of nlen + ndist
	 *   code lengths.  Therefore run-length coding can and often does cross the
	 *   boundary between the two sets of lengths.
	 *
	 * - So to summarize, the code description at the start of a dynamic block is
	 *   three counts for the number of code lengths for the literal/length codes,
	 *   the distance codes, and the code length codes.  This is followed by the
	 *   code length code lengths, three bits each.  This is used to construct the
	 *   code length code which is used to read the remainder of the lengths.  Then
	 *   the literal/length code lengths and distance lengths are read as a single
	 *   set of lengths using the code length codes.  Codes are constructed from
	 *   the resulting two sets of lengths, and then finally you can start
	 *   decoding actual compressed data in the block.
	 *
	 * - For reference, a "typical" size for the code description in a dynamic
	 *   block is around 80 bytes.
	 */
	function dynamic() {
    /* get number of lengths in each table, check lengths */
    $nlen = $this->bits(5) + 257;
    $ndist = $this->bits(5) + 1;
    $ncode = $this->bits(4) + 4;
    if ($nlen > MAXLCODES || $ndist > MAXDCODES)
        return -3;                      /* bad counts */

    /* read code length code lengths (really), missing lengths are zero */
    $lengths = array();
    for ($index = 0; $index < $ncode; $index++)
        $lengths[$this->order[$index]] = $this->bits(3);
    for (; $index < 19; $index++)
        $lengths[$this->order[$index]] = 0;

    /* build huffman table for code lengths codes (use lencode temporarily) */
    $lencode = new HuffmanTable($lengths, 19);
    $err = $lencode->error;
    if ($err != 0) return -4;            /* require complete code set here */

    /* read length/literal and distance code length tables */
    $index = 0;
    while ($index < $nlen + $ndist) {
      $symbol = $this->decode($lencode);
      if ($symbol < 16)                /* length in 0..15 */
				$lengths[$index++] = $symbol;
      else {                          /* repeat instruction */
				$len = 0;                    /* assume repeating zeros */
				if ($symbol == 16) {         /* repeat last length 3..6 times */
				    if ($index == 0) return -5;      /* no last length! */
				    $len = $lengths[$index - 1];       /* last length */
				    $symbol = 3 + $this->bits(2);
				}
				else if ($symbol == 17)      /* repeat zero 3..10 times */
				    $symbol = 3 + $this->bits(3);
				else                        /* == 18, repeat zero 11..138 times */
				    $symbol = 11 + $this->bits(7);
				if ($index + $symbol > $nlen + $ndist)
				    return -6;              /* too many lengths! */
				while ($symbol--)            /* repeat last or zero symbol times */
				    $lengths[$index++] = $len;
      }
    }

    /* build huffman table for literal/length codes */
    $lencode = new HuffmanTable($lengths, $nlen);
    $err = $lencode->error;
    if ($err < 0 || ($err > 0 && $nlen - $lencode->count[0] != 1))
        return -7;      /* only allow incomplete codes if just one code */

    /* build huffman table for distance codes */
    $distcode = new HuffmanTable(array_slice($lengths, $nlen), $ndist);
    $err = $distcode->error;
    if ($err < 0 || ($err > 0 && $ndist - $distcode->count[0] != 1))
        return -8;      /* only allow incomplete codes if just one code */

    /* decode data until end-of-block code */
    return $this->codes($lencode, $distcode);
	}
	
	/**
	 * Decode literal/length and distance codes until an end-of-block code.
	 *
	 * Format notes:
	 *
	 * - Compressed data that is after the block type if fixed or after the code
	 *   description if dynamic is a combination of literals and length/distance
	 *   pairs terminated by and end-of-block code.  Literals are simply Huffman
	 *   coded bytes.  A length/distance pair is a coded length followed by a
	 *   coded distance to represent a string that occurs earlier in the
	 *   uncompressed data that occurs again at the current location.
	 *
	 * - Literals, lengths, and the end-of-block code are combined into a single
	 *   code of up to 286 symbols.  They are 256 literals (0..255), 29 length
	 *   symbols (257..285), and the end-of-block symbol (256).
	 *
	 * - There are 256 possible lengths (3..258), and so 29 symbols are not enough
	 *   to represent all of those.  Lengths 3..10 and 258 are in fact represented
	 *   by just a length symbol.  Lengths 11..257 are represented as a symbol and
	 *   some number of extra bits that are added as an integer to the base length
	 *   of the length symbol.  The number of extra bits is determined by the base
	 *   length symbol.  These are in the static arrays below, lens[] for the base
	 *   lengths and lext[] for the corresponding number of extra bits.
	 *
	 * - The reason that 258 gets its own symbol is that the longest length is used
	 *   often in highly redundant files.  Note that 258 can also be coded as the
	 *   base value 227 plus the maximum extra value of 31.  While a good deflate
	 *   should never do this, it is not an error, and should be decoded properly.
	 *
	 * - If a length is decoded, including its extra bits if any, then it is
	 *   followed a distance code.  There are up to 30 distance symbols.  Again
	 *   there are many more possible distances (1..32768), so extra bits are added
	 *   to a base value represented by the symbol.  The distances 1..4 get their
	 *   own symbol, but the rest require extra bits.  The base distances and
	 *   corresponding number of extra bits are below in the static arrays dist[]
	 *   and dext[].
	 *
	 * - Literal bytes are simply written to the output.  A length/distance pair is
	 *   an instruction to copy previously uncompressed bytes to the output.  The
	 *   copy is from distance bytes back in the output stream, copying for length
	 *   bytes.
	 *
	 * - Distances pointing before the beginning of the output data are not
	 *   permitted.
	 *
	 * - Overlapped copies, where the length is greater than the distance, are
	 *   allowed and common.  For example, a distance of one and a length of 258
	 *   simply copies the last byte 258 times.  A distance of four and a length of
	 *   twelve copies the last four bytes three times.  A simple forward copy
	 *   ignoring whether the length is greater than the distance or not implements
	 *   this correctly.  You should not use memcpy() since its behavior is not
	 *   defined for overlapped arrays.  You should not use memmove() or bcopy()
	 *   since though their behavior -is- defined for overlapping arrays, it is
	 *   defined to do the wrong thing in this case.
	 */
	function codes($lencode, $distcode) {
    /* decode literals and length/distance pairs */
    do {
	    $symbol = $this->decode($lencode);
	    if ($symbol < 0) return $symbol;  /* invalid symbol */
	    if ($symbol < 256) {             /* literal: symbol is the byte */
        /* write out the literal */
        $this->out .= chr($symbol);
        $this->outcnt++;
	    }
	    else if ($symbol > 256) {        /* length */
        /* get and compute length */
        $symbol -= 257;
        if ($symbol >= 29) return -9;        /* invalid fixed code */
        $len = $this->lens[$symbol] + $this->bits($this->lext[$symbol]);

        /* get and check distance */
        $symbol = $this->decode($distcode);
        if ($symbol < 0) return $symbol;      /* invalid symbol */
        $dist = $this->dists[$symbol] + $this->bits($this->dext[$symbol]);

        /* copy length bytes from distance bytes back */
        while ($len--) {
          $this->out .= $this->out[$this->outcnt - $dist];
          $this->outcnt++;
        }
	    }
    } 
    while ($symbol != 256);            /* end of block symbol */

    /* done with a valid fixed or dynamic block */
    return 0;
	}

	/**
	 * Decode a code from the stream s using huffman table h.  Return the symbol or
	 * a negative value if there is an error.  If all of the lengths are zero, i.e.
	 * an empty code, or if the code is incomplete and an invalid code is received,
	 * then -9 is returned after reading MAXBITS bits.
	 *
	 * Format notes:
	 *
	 * - The codes as stored in the compressed data are bit-reversed relative to
	 *   a simple integer ordering of codes of the same lengths.  Hence below the
	 *   bits are pulled from the compressed data one at a time and used to
	 *   build the code value reversed from what is in the stream in order to
	 *   permit simple integer comparisons for decoding.  A table-based decoding
	 *   scheme (as used in zlib) does not need to do this reversal.
	 *
	 * - The first code for the shortest length is all zeros.  Subsequent codes of
	 *   the same length are simply integer increments of the previous code.  When
	 *   moving up a length, a zero bit is appended to the code.  For a complete
	 *   code, the last code of the longest length will be all ones.
	 *
	 * - Incomplete codes are handled by this decoder, since they are permitted
	 *   in the deflate format.  See the format notes for fixed() and dynamic().
	 */	
//	function decode($huffman) {
//    $code = $first = $index = 0;
//    for ($len = 1; $len <= MAXBITS; $len++) {
//        $code |= $this->bits(1);             /* get next bit */
//        $count = $huffman->count[$len];
//        if ($code < $first + $count)       /* if length len, return symbol */
//            return $huffman->symbol[$index + ($code - $first)];
//        $index += $count;                 /* else update for next length */
//        $first += $count;
//        $first <<= 1;
//        $code <<= 1;
//    }
//    return -9;                          /* ran out of codes */
//	}
	
	/*
	 * A faster version of decode() for real applications of this code.   It's not
	 * as readable, but it makes puff() twice as fast.  And it only makes the code
	 * a few percent larger.
	 */
	function decode($huffman) {
    $bitbuf = $this->bitbuf;
    $left = $this->bitcnt;
    $code = $first = $index = 0;
    $len = 1;
    reset($huffman->count);
    while (1) {
			while ($left--) {
			  $code |= $bitbuf & 1;
			  $bitbuf >>= 1;
			  $count = next($huffman->count);
			  if ($code < $first + $count) { /* if length len, return symbol */
			      $this->bitbuf = $bitbuf;
			      $this->bitcnt = ($this->bitcnt - $len) & 7;
			      return $huffman->symbol[$index + ($code - $first)];
			  }
			  $index += $count;             /* else update for next length */
			  $first += $count;
			  $first <<= 1;
			  $code <<= 1;
			  $len++;
			}
			$left = (MAXBITS + 1) - $len;
			if ($left == 0) break;
			if ($this->incnt == $this->inlen) {
	    	trigger_error("Out of input", E_USER_ERROR);
	    }
			$bitbuf = ord($this->in[$this->incnt++]);
			if ($left > 8) $left = 8;
    }
    return -9;                          /* ran out of codes */
	}
}
?>