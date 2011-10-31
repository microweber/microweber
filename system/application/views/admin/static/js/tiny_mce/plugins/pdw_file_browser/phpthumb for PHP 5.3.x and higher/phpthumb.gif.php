<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
// GIF Util - (C) 2003 Yamasoft (S/C)
// http://www.yamasoft.com
// All Rights Reserved
// This file can be freely copied, distributed, modified, updated by anyone under the only
// condition to leave the original address (Yamasoft, http://www.yamasoft.com) and this header.
///////////////////////////////////////////////////////////////////////////////////////////////////
// <gif>  = gif_loadFile(filename, [index])
// <bool> = gif_getSize(<gif> or filename, &width, &height)
// <bool> = gif_outputAsPng(<gif>, filename, [bgColor])
// <bool> = gif_outputAsBmp(<gif>, filename, [bgcolor])
// <bool> = gif_outputAsJpeg(<gif>, filename, [bgcolor]) - use cjpeg if available otherwise uses GD
///////////////////////////////////////////////////////////////////////////////////////////////////
// Original code by Fabien Ezber
// Modified by James Heinrich <info@silisoftware.com> for use in phpThumb() - December 10, 2003
// * Added function gif_loadFileToGDimageResource() - this returns a GD image resource
// * Modified gif_outputAsJpeg() to check if it's running under Windows, or if cjpeg is not
//   available, in which case it will attempt to output JPEG using GD functions
// * added @ error-suppression to two lines where it checks: if ($this->m_img->m_bTrans)
//   otherwise warnings are generated if error_reporting == E_ALL
///////////////////////////////////////////////////////////////////////////////////////////////////

function gif_loadFile($lpszFileName, $iIndex = 0)
{
	$gif = new CGIF();
	if ($gif->loadFile($lpszFileName, $iIndex)) {
		return $gif;
	}
	return false;
}

///////////////////////////////////////////////////////////////////////////////////////////////////

// Added by James Heinrich <info@silisoftware.com> - December 10, 2003
function gif_loadFileToGDimageResource($gifFilename, $bgColor = -1)
{
	if ($gif = gif_loadFile($gifFilename)) {

		if (!phpthumb_functions::FunctionIsDisabled('set_time_limit')) {
			// shouldn't take nearly this long
			set_time_limit(120);
		}
		// general strategy: convert raw data to PNG then convert PNG data to GD image resource
		$PNGdata = $gif->getPng($bgColor);
		if ($img = @ImageCreateFromString($PNGdata)) {

			// excellent - PNG image data successfully converted to GD image
			return $img;

		} elseif ($img = $gif->getGD_PixelPlotterVersion()) {

			// problem: ImageCreateFromString() didn't like the PNG image data.
			//   This has been known to happen in PHP v4.0.6
			// solution: take the raw image data and create a new GD image and plot
			//   pixel-by-pixel on the GD image. This is extremely slow, but it does
			//   work and a slow solution is better than no solution, right? :)
			return $img;

		}
	}
	return false;
}

///////////////////////////////////////////////////////////////////////////////////////////////////

function gif_outputAsBmp($gif, $lpszFileName, $bgColor = -1)
{
	if (!isSet($gif) || (@get_class($gif) <> 'cgif') || !$gif->loaded() || ($lpszFileName == '')) {
		return false;
	}

	$fd = $gif->getBmp($bgColor);
	if (strlen($fd) <= 0) {
		return false;
	}

	if (!($fh = @fopen($lpszFileName, 'wb'))) {
		return false;
	}
	@fwrite($fh, $fd, strlen($fd));
	@fflush($fh);
	@fclose($fh);
	return true;
}

///////////////////////////////////////////////////////////////////////////////////////////////////

function gif_outputAsPng($gif, $lpszFileName, $bgColor = -1)
{
	if (!isSet($gif) || (@get_class($gif) <> 'cgif') || !$gif->loaded() || ($lpszFileName == '')) {
		return false;
	}

	$fd = $gif->getPng($bgColor);
	if (strlen($fd) <= 0) {
		return false;
	}

	if (!($fh = @fopen($lpszFileName, 'wb'))) {
		return false;
	}
	@fwrite($fh, $fd, strlen($fd));
	@fflush($fh);
	@fclose($fh);
	return true;
}

///////////////////////////////////////////////////////////////////////////////////////////////////

function gif_outputAsJpeg($gif, $lpszFileName, $bgColor = -1)
{
	// JPEG output that does not require cjpeg added by James Heinrich <info@silisoftware.com> - December 10, 2003
	if ((strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') && (file_exists('/usr/local/bin/cjpeg') || `which cjpeg`)) {

		if (gif_outputAsBmp($gif, $lpszFileName.'.bmp', $bgColor)) {
			exec('cjpeg '.$lpszFileName.'.bmp >'.$lpszFileName.' 2>/dev/null');
			@unLink($lpszFileName.'.bmp');

			if (@file_exists($lpszFileName)) {
				if (@fileSize($lpszFileName) > 0) {
					return true;
				}

				@unLink($lpszFileName);
			}
		}

	} else {

		// either Windows, or cjpeg not found in path
		if ($img = @ImageCreateFromString($gif->getPng($bgColor))) {
			if (@ImageJPEG($img, $lpszFileName)) {
				return true;
			}
		}

	}

	return false;
}

///////////////////////////////////////////////////////////////////////////////////////////////////

function gif_getSize($gif, &$width, &$height)
{
	if (isSet($gif) && (@get_class($gif) == 'cgif') && $gif->loaded()) {
		$width  = $gif->width();
		$height = $gif->height();
	} elseif (@file_exists($gif)) {
		$myGIF = new CGIF();
		if (!$myGIF->getSize($gif, $width, $height)) {
			return false;
		}
	} else {
		return false;
	}

	return true;
}

///////////////////////////////////////////////////////////////////////////////////////////////////

class CGIFLZW
{
	public $MAX_LZW_BITS;
	public $Fresh, $CodeSize, $SetCodeSize, $MaxCode, $MaxCodeSize, $FirstCode, $OldCode;
	public $ClearCode, $EndCode, $Next, $Vals, $Stack, $sp, $Buf, $CurBit, $LastBit, $Done, $LastByte;

	///////////////////////////////////////////////////////////////////////////

	// CONSTRUCTOR
	function CGIFLZW()
	{
		$this->MAX_LZW_BITS = 12;
		unSet($this->Next);
		unSet($this->Vals);
		unSet($this->Stack);
		unSet($this->Buf);

		$this->Next  = range(0, (1 << $this->MAX_LZW_BITS)       - 1);
		$this->Vals  = range(0, (1 << $this->MAX_LZW_BITS)       - 1);
		$this->Stack = range(0, (1 << ($this->MAX_LZW_BITS + 1)) - 1);
		$this->Buf   = range(0, 279);
	}

	///////////////////////////////////////////////////////////////////////////

	function deCompress($data, &$datLen)
	{
		$stLen  = strlen($data);
		$datLen = 0;
		$ret    = '';

		// INITIALIZATION
		$this->LZWCommand($data, true);

		while (($iIndex = $this->LZWCommand($data, false)) >= 0) {
			$ret .= chr($iIndex);
		}

		$datLen = $stLen - strlen($data);

		if ($iIndex != -2) {
			return false;
		}

		return $ret;
	}

	///////////////////////////////////////////////////////////////////////////

	function LZWCommand(&$data, $bInit)
	{
		if ($bInit) {
			$this->SetCodeSize = ord($data{0});
			$data = substr($data, 1);

			$this->CodeSize    = $this->SetCodeSize + 1;
			$this->ClearCode   = 1 << $this->SetCodeSize;
			$this->EndCode     = $this->ClearCode + 1;
			$this->MaxCode     = $this->ClearCode + 2;
			$this->MaxCodeSize = $this->ClearCode << 1;

			$this->GetCode($data, $bInit);

			$this->Fresh = 1;
			for ($i = 0; $i < $this->ClearCode; $i++) {
				$this->Next[$i] = 0;
				$this->Vals[$i] = $i;
			}

			for (; $i < (1 << $this->MAX_LZW_BITS); $i++) {
				$this->Next[$i] = 0;
				$this->Vals[$i] = 0;
			}

			$this->sp = 0;
			return 1;
		}

		if ($this->Fresh) {
			$this->Fresh = 0;
			do {
				$this->FirstCode = $this->GetCode($data, $bInit);
				$this->OldCode   = $this->FirstCode;
			}
			while ($this->FirstCode == $this->ClearCode);

			return $this->FirstCode;
		}

		if ($this->sp > 0) {
			$this->sp--;
			return $this->Stack[$this->sp];
		}

		while (($Code = $this->GetCode($data, $bInit)) >= 0) {
			if ($Code == $this->ClearCode) {
				for ($i = 0; $i < $this->ClearCode; $i++) {
					$this->Next[$i] = 0;
					$this->Vals[$i] = $i;
				}

				for (; $i < (1 << $this->MAX_LZW_BITS); $i++) {
					$this->Next[$i] = 0;
					$this->Vals[$i] = 0;
				}

				$this->CodeSize    = $this->SetCodeSize + 1;
				$this->MaxCodeSize = $this->ClearCode << 1;
				$this->MaxCode     = $this->ClearCode + 2;
				$this->sp          = 0;
				$this->FirstCode   = $this->GetCode($data, $bInit);
				$this->OldCode     = $this->FirstCode;

				return $this->FirstCode;
			}

			if ($Code == $this->EndCode) {
				return -2;
			}

			$InCode = $Code;
			if ($Code >= $this->MaxCode) {
				$this->Stack[$this->sp] = $this->FirstCode;
				$this->sp++;
				$Code = $this->OldCode;
			}

			while ($Code >= $this->ClearCode) {
				$this->Stack[$this->sp] = $this->Vals[$Code];
				$this->sp++;

				if ($Code == $this->Next[$Code]) // Circular table entry, big GIF Error!
					return -1;

				$Code = $this->Next[$Code];
			}

			$this->FirstCode = $this->Vals[$Code];
			$this->Stack[$this->sp] = $this->FirstCode;
			$this->sp++;

			if (($Code = $this->MaxCode) < (1 << $this->MAX_LZW_BITS)) {
				$this->Next[$Code] = $this->OldCode;
				$this->Vals[$Code] = $this->FirstCode;
				$this->MaxCode++;

				if (($this->MaxCode >= $this->MaxCodeSize) && ($this->MaxCodeSize < (1 << $this->MAX_LZW_BITS))) {
					$this->MaxCodeSize *= 2;
					$this->CodeSize++;
				}
			}

			$this->OldCode = $InCode;
			if ($this->sp > 0) {
				$this->sp--;
				return $this->Stack[$this->sp];
			}
		}

		return $Code;
	}

	///////////////////////////////////////////////////////////////////////////

	function GetCode(&$data, $bInit)
	{
		if ($bInit) {
			$this->CurBit   = 0;
			$this->LastBit  = 0;
			$this->Done     = 0;
			$this->LastByte = 2;
			return 1;
		}

		if (($this->CurBit + $this->CodeSize) >= $this->LastBit) {
			if ($this->Done) {
				if ($this->CurBit >= $this->LastBit) {
					// Ran off the end of my bits
					return 0;
				}
				return -1;
			}

			$this->Buf[0] = $this->Buf[$this->LastByte - 2];
			$this->Buf[1] = $this->Buf[$this->LastByte - 1];

			$Count = ord($data{0});
			$data  = substr($data, 1);

			if ($Count) {
				for ($i = 0; $i < $Count; $i++) {
					$this->Buf[2 + $i] = ord($data{$i});
				}
				$data = substr($data, $Count);
			} else {
				$this->Done = 1;
			}

			$this->LastByte = 2 + $Count;
			$this->CurBit   = ($this->CurBit - $this->LastBit) + 16;
			$this->LastBit  = (2 + $Count) << 3;
		}

		$iRet = 0;
		for ($i = $this->CurBit, $j = 0; $j < $this->CodeSize; $i++, $j++) {
			$iRet |= (($this->Buf[intval($i / 8)] & (1 << ($i % 8))) != 0) << $j;
		}

		$this->CurBit += $this->CodeSize;
		return $iRet;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////

class CGIFCOLORTABLE
{
	public $m_nColors;
	public $m_arColors;

	///////////////////////////////////////////////////////////////////////////

	// CONSTRUCTOR
	function CGIFCOLORTABLE()
	{
		unSet($this->m_nColors);
		unSet($this->m_arColors);
	}

	///////////////////////////////////////////////////////////////////////////

	function load($lpData, $num)
	{
		$this->m_nColors  = 0;
		$this->m_arColors = array();

		for ($i = 0; $i < $num; $i++) {
			$rgb = substr($lpData, $i * 3, 3);
			if (strlen($rgb) < 3) {
				return false;
			}

			$this->m_arColors[] = (ord($rgb{2}) << 16) + (ord($rgb{1}) << 8) + ord($rgb{0});
			$this->m_nColors++;
		}

		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	function toString()
	{
		$ret = '';

		for ($i = 0; $i < $this->m_nColors; $i++) {
			$ret .=
				chr(($this->m_arColors[$i] & 0x000000FF))       . // R
				chr(($this->m_arColors[$i] & 0x0000FF00) >>  8) . // G
				chr(($this->m_arColors[$i] & 0x00FF0000) >> 16);  // B
		}

		return $ret;
	}

	///////////////////////////////////////////////////////////////////////////

	function toRGBQuad()
	{
		$ret = '';

		for ($i = 0; $i < $this->m_nColors; $i++) {
			$ret .=
				chr(($this->m_arColors[$i] & 0x00FF0000) >> 16) . // B
				chr(($this->m_arColors[$i] & 0x0000FF00) >>  8) . // G
				chr(($this->m_arColors[$i] & 0x000000FF))       . // R
				"\x00";
		}

		return $ret;
	}

	///////////////////////////////////////////////////////////////////////////

	function colorIndex($rgb)
	{
		$rgb  = intval($rgb) & 0xFFFFFF;
		$r1   = ($rgb & 0x0000FF);
		$g1   = ($rgb & 0x00FF00) >>  8;
		$b1   = ($rgb & 0xFF0000) >> 16;
		$idx  = -1;

		for ($i = 0; $i < $this->m_nColors; $i++) {
			$r2 = ($this->m_arColors[$i] & 0x000000FF);
			$g2 = ($this->m_arColors[$i] & 0x0000FF00) >>  8;
			$b2 = ($this->m_arColors[$i] & 0x00FF0000) >> 16;
			$d  = abs($r2 - $r1) + abs($g2 - $g1) + abs($b2 - $b1);

			if (($idx == -1) || ($d < $dif)) {
				$idx = $i;
				$dif = $d;
			}
		}

		return $idx;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////

class CGIFFILEHEADER
{
	public $m_lpVer;
	public $m_nWidth;
	public $m_nHeight;
	public $m_bGlobalClr;
	public $m_nColorRes;
	public $m_bSorted;
	public $m_nTableSize;
	public $m_nBgColor;
	public $m_nPixelRatio;
	public $m_colorTable;

	///////////////////////////////////////////////////////////////////////////

	// CONSTRUCTOR
	function CGIFFILEHEADER()
	{
		unSet($this->m_lpVer);
		unSet($this->m_nWidth);
		unSet($this->m_nHeight);
		unSet($this->m_bGlobalClr);
		unSet($this->m_nColorRes);
		unSet($this->m_bSorted);
		unSet($this->m_nTableSize);
		unSet($this->m_nBgColor);
		unSet($this->m_nPixelRatio);
		unSet($this->m_colorTable);
	}

	///////////////////////////////////////////////////////////////////////////

	function load($lpData, &$hdrLen)
	{
		$hdrLen = 0;

		$this->m_lpVer = substr($lpData, 0, 6);
		if (($this->m_lpVer <> 'GIF87a') && ($this->m_lpVer <> 'GIF89a')) {
			return false;
		}

		$this->m_nWidth  = $this->w2i(substr($lpData, 6, 2));
		$this->m_nHeight = $this->w2i(substr($lpData, 8, 2));
		if (!$this->m_nWidth || !$this->m_nHeight) {
			return false;
		}

		$b = ord(substr($lpData, 10, 1));
		$this->m_bGlobalClr  = ($b & 0x80) ? true : false;
		$this->m_nColorRes   = ($b & 0x70) >> 4;
		$this->m_bSorted     = ($b & 0x08) ? true : false;
		$this->m_nTableSize  = 2 << ($b & 0x07);
		$this->m_nBgColor    = ord(substr($lpData, 11, 1));
		$this->m_nPixelRatio = ord(substr($lpData, 12, 1));
		$hdrLen = 13;

		if ($this->m_bGlobalClr) {
			$this->m_colorTable = new CGIFCOLORTABLE();
			if (!$this->m_colorTable->load(substr($lpData, $hdrLen), $this->m_nTableSize)) {
				return false;
			}
			$hdrLen += 3 * $this->m_nTableSize;
		}

		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////

class CGIFIMAGEHEADER
{
	public $m_nLeft;
	public $m_nTop;
	public $m_nWidth;
	public $m_nHeight;
	public $m_bLocalClr;
	public $m_bInterlace;
	public $m_bSorted;
	public $m_nTableSize;
	public $m_colorTable;

	///////////////////////////////////////////////////////////////////////////

	// CONSTRUCTOR
	function CGIFIMAGEHEADER()
	{
		unSet($this->m_nLeft);
		unSet($this->m_nTop);
		unSet($this->m_nWidth);
		unSet($this->m_nHeight);
		unSet($this->m_bLocalClr);
		unSet($this->m_bInterlace);
		unSet($this->m_bSorted);
		unSet($this->m_nTableSize);
		unSet($this->m_colorTable);
	}

	///////////////////////////////////////////////////////////////////////////

	function load($lpData, &$hdrLen)
	{
		$hdrLen = 0;

		$this->m_nLeft   = $this->w2i(substr($lpData, 0, 2));
		$this->m_nTop    = $this->w2i(substr($lpData, 2, 2));
		$this->m_nWidth  = $this->w2i(substr($lpData, 4, 2));
		$this->m_nHeight = $this->w2i(substr($lpData, 6, 2));

		if (!$this->m_nWidth || !$this->m_nHeight) {
			return false;
		}

		$b = ord($lpData{8});
		$this->m_bLocalClr  = ($b & 0x80) ? true : false;
		$this->m_bInterlace = ($b & 0x40) ? true : false;
		$this->m_bSorted    = ($b & 0x20) ? true : false;
		$this->m_nTableSize = 2 << ($b & 0x07);
		$hdrLen = 9;

		if ($this->m_bLocalClr) {
			$this->m_colorTable = new CGIFCOLORTABLE();
			if (!$this->m_colorTable->load(substr($lpData, $hdrLen), $this->m_nTableSize)) {
				return false;
			}
			$hdrLen += 3 * $this->m_nTableSize;
		}

		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////

class CGIFIMAGE
{
	public $m_disp;
	public $m_bUser;
	public $m_bTrans;
	public $m_nDelay;
	public $m_nTrans;
	public $m_lpComm;
	public $m_gih;
	public $m_data;
	public $m_lzw;

	///////////////////////////////////////////////////////////////////////////

	function CGIFIMAGE()
	{
		unSet($this->m_disp);
		unSet($this->m_bUser);
		unSet($this->m_bTrans);
		unSet($this->m_nDelay);
		unSet($this->m_nTrans);
		unSet($this->m_lpComm);
		unSet($this->m_data);
		$this->m_gih = new CGIFIMAGEHEADER();
		$this->m_lzw = new CGIFLZW();
	}

	///////////////////////////////////////////////////////////////////////////

	function load($data, &$datLen)
	{
		$datLen = 0;

		while (true) {
			$b = ord($data{0});
			$data = substr($data, 1);
			$datLen++;

			switch($b) {
			case 0x21: // Extension
				if (!$this->skipExt($data, $len = 0)) {
					return false;
				}
				$datLen += $len;
				break;

			case 0x2C: // Image
				// LOAD HEADER & COLOR TABLE
				if (!$this->m_gih->load($data, $len = 0)) {
					return false;
				}
				$data = substr($data, $len);
				$datLen += $len;

				// ALLOC BUFFER
				if (!($this->m_data = $this->m_lzw->deCompress($data, $len = 0))) {
					return false;
				}
				$data = substr($data, $len);
				$datLen += $len;

				if ($this->m_gih->m_bInterlace) {
					$this->deInterlace();
				}
				return true;

			case 0x3B: // EOF
			default:
				return false;
			}
		}
		return false;
	}

	///////////////////////////////////////////////////////////////////////////

	function skipExt(&$data, &$extLen)
	{
		$extLen = 0;

		$b = ord($data{0});
		$data = substr($data, 1);
		$extLen++;

		switch($b) {
		case 0xF9: // Graphic Control
			$b = ord($data{1});
			$this->m_disp   = ($b & 0x1C) >> 2;
			$this->m_bUser  = ($b & 0x02) ? true : false;
			$this->m_bTrans = ($b & 0x01) ? true : false;
			$this->m_nDelay = $this->w2i(substr($data, 2, 2));
			$this->m_nTrans = ord($data{4});
			break;

		case 0xFE: // Comment
			$this->m_lpComm = substr($data, 1, ord($data{0}));
			break;

		case 0x01: // Plain text
			break;

		case 0xFF: // Application
			break;
		}

		// SKIP DEFAULT AS DEFS MAY CHANGE
		$b = ord($data{0});
		$data = substr($data, 1);
		$extLen++;
		while ($b > 0) {
			$data = substr($data, $b);
			$extLen += $b;
			$b    = ord($data{0});
			$data = substr($data, 1);
			$extLen++;
		}
		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}

	///////////////////////////////////////////////////////////////////////////

	function deInterlace()
	{
		$data = $this->m_data;

		for ($i = 0; $i < 4; $i++) {
			switch($i) {
			case 0:
				$s = 8;
				$y = 0;
				break;

			case 1:
				$s = 8;
				$y = 4;
				break;

			case 2:
				$s = 4;
				$y = 2;
				break;

			case 3:
				$s = 2;
				$y = 1;
				break;
			}

			for (; $y < $this->m_gih->m_nHeight; $y += $s) {
				$lne = substr($this->m_data, 0, $this->m_gih->m_nWidth);
				$this->m_data = substr($this->m_data, $this->m_gih->m_nWidth);

				$data =
					substr($data, 0, $y * $this->m_gih->m_nWidth) .
					$lne .
					substr($data, ($y + 1) * $this->m_gih->m_nWidth);
			}
		}

		$this->m_data = $data;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////

class CGIF
{
	public $m_gfh;
	public $m_lpData;
	public $m_img;
	public $m_bLoaded;

	///////////////////////////////////////////////////////////////////////////

	// CONSTRUCTOR
	function CGIF()
	{
		$this->m_gfh     = new CGIFFILEHEADER();
		$this->m_img     = new CGIFIMAGE();
		$this->m_lpData  = '';
		$this->m_bLoaded = false;
	}

	///////////////////////////////////////////////////////////////////////////

	function loadFile($lpszFileName, $iIndex)
	{
		if ($iIndex < 0) {
			return false;
		}

		// READ FILE
		if (!($fh = @fopen($lpszFileName, 'rb'))) {
			return false;
		}
		$this->m_lpData = @fRead($fh, @fileSize($lpszFileName));
		fclose($fh);

		// GET FILE HEADER
		if (!$this->m_gfh->load($this->m_lpData, $len = 0)) {
			return false;
		}
		$this->m_lpData = substr($this->m_lpData, $len);

		do {
			if (!$this->m_img->load($this->m_lpData, $imgLen = 0)) {
				return false;
			}
			$this->m_lpData = substr($this->m_lpData, $imgLen);
		}
		while ($iIndex-- > 0);

		$this->m_bLoaded = true;
		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	function getSize($lpszFileName, &$width, &$height)
	{
		if (!($fh = @fopen($lpszFileName, 'rb'))) {
			return false;
		}
		$data = @fRead($fh, @fileSize($lpszFileName));
		@fclose($fh);

		$gfh = new CGIFFILEHEADER();
		if (!$gfh->load($data, $len = 0)) {
			return false;
		}

		$width  = $gfh->m_nWidth;
		$height = $gfh->m_nHeight;
		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	function getBmp($bgColor)
	{
		$out = '';

		if (!$this->m_bLoaded) {
			return false;
		}

		// PREPARE COLOR TABLE (RGBQUADs)
		if ($this->m_img->m_gih->m_bLocalClr) {
			$nColors = $this->m_img->m_gih->m_nTableSize;
			$rgbq    = $this->m_img->m_gih->m_colorTable->toRGBQuad();
			if ($bgColor != -1) {
				$bgColor = $this->m_img->m_gih->m_colorTable->colorIndex($bgColor);
			}
		} elseif ($this->m_gfh->m_bGlobalClr) {
			$nColors = $this->m_gfh->m_nTableSize;
			$rgbq    = $this->m_gfh->m_colorTable->toRGBQuad();
			if ($bgColor != -1) {
				$bgColor = $this->m_gfh->m_colorTable->colorIndex($bgColor);
			}
		} else {
			$nColors =  0;
			$bgColor = -1;
		}

		// PREPARE BITMAP BITS
		$data = $this->m_img->m_data;
		$nPxl = ($this->m_gfh->m_nHeight - 1) * $this->m_gfh->m_nWidth;
		$bmp  = '';
		$nPad = ($this->m_gfh->m_nWidth % 4) ? 4 - ($this->m_gfh->m_nWidth % 4) : 0;
		for ($y = 0; $y < $this->m_gfh->m_nHeight; $y++) {
			for ($x = 0; $x < $this->m_gfh->m_nWidth; $x++, $nPxl++) {
				if (
					($x >= $this->m_img->m_gih->m_nLeft) &&
					($y >= $this->m_img->m_gih->m_nTop) &&
					($x <  ($this->m_img->m_gih->m_nLeft + $this->m_img->m_gih->m_nWidth)) &&
					($y <  ($this->m_img->m_gih->m_nTop  + $this->m_img->m_gih->m_nHeight))) {
					// PART OF IMAGE
					if (@$this->m_img->m_bTrans && (ord($data{$nPxl}) == $this->m_img->m_nTrans)) {
						// TRANSPARENT -> BACKGROUND
						if ($bgColor == -1) {
							$bmp .= chr($this->m_gfh->m_nBgColor);
						} else {
							$bmp .= chr($bgColor);
						}
					} else {
						$bmp .= $data{$nPxl};
					}
				} else {
					// BACKGROUND
					if ($bgColor == -1) {
						$bmp .= chr($this->m_gfh->m_nBgColor);
					} else {
						$bmp .= chr($bgColor);
					}
				}
			}
			$nPxl -= $this->m_gfh->m_nWidth << 1;

			// ADD PADDING
			for ($x = 0; $x < $nPad; $x++) {
				$bmp .= "\x00";
			}
		}

		// BITMAPFILEHEADER
		$out .= 'BM';
		$out .= $this->dword(14 + 40 + ($nColors << 2) + strlen($bmp));
		$out .= "\x00\x00";
		$out .= "\x00\x00";
		$out .= $this->dword(14 + 40 + ($nColors << 2));

		// BITMAPINFOHEADER
		$out .= $this->dword(40);
		$out .= $this->dword($this->m_gfh->m_nWidth);
		$out .= $this->dword($this->m_gfh->m_nHeight);
		$out .= "\x01\x00";
		$out .= "\x08\x00";
		$out .= "\x00\x00\x00\x00";
		$out .= "\x00\x00\x00\x00";
		$out .= "\x12\x0B\x00\x00";
		$out .= "\x12\x0B\x00\x00";
		$out .= $this->dword($nColors % 256);
		$out .= "\x00\x00\x00\x00";

		// COLOR TABLE
		if ($nColors > 0) {
			$out .= $rgbq;
		}

		// DATA
		$out .= $bmp;

		return $out;
	}

	///////////////////////////////////////////////////////////////////////////

	function getPng($bgColor)
	{
		$out = '';

		if (!$this->m_bLoaded) {
			return false;
		}

		// PREPARE COLOR TABLE (RGBQUADs)
		if ($this->m_img->m_gih->m_bLocalClr) {
			$nColors = $this->m_img->m_gih->m_nTableSize;
			$pal     = $this->m_img->m_gih->m_colorTable->toString();
			if ($bgColor != -1) {
				$bgColor = $this->m_img->m_gih->m_colorTable->colorIndex($bgColor);
			}
		} elseif ($this->m_gfh->m_bGlobalClr) {
			$nColors = $this->m_gfh->m_nTableSize;
			$pal     = $this->m_gfh->m_colorTable->toString();
			if ($bgColor != -1) {
				$bgColor = $this->m_gfh->m_colorTable->colorIndex($bgColor);
			}
		} else {
			$nColors =  0;
			$bgColor = -1;
		}

		// PREPARE BITMAP BITS
		$data = $this->m_img->m_data;
		$nPxl = 0;
		$bmp  = '';
		for ($y = 0; $y < $this->m_gfh->m_nHeight; $y++) {
			$bmp .= "\x00";
			for ($x = 0; $x < $this->m_gfh->m_nWidth; $x++, $nPxl++) {
				if (
					($x >= $this->m_img->m_gih->m_nLeft) &&
					($y >= $this->m_img->m_gih->m_nTop) &&
					($x <  ($this->m_img->m_gih->m_nLeft + $this->m_img->m_gih->m_nWidth)) &&
					($y <  ($this->m_img->m_gih->m_nTop  + $this->m_img->m_gih->m_nHeight))) {
					// PART OF IMAGE
					$bmp .= $data{$nPxl};
				} else {
					// BACKGROUND
					if ($bgColor == -1) {
						$bmp .= chr($this->m_gfh->m_nBgColor);
					} else {
						$bmp .= chr($bgColor);
					}
				}
			}
		}
		$bmp = gzcompress($bmp, 9);

		///////////////////////////////////////////////////////////////////////
		// SIGNATURE
		$out .= "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";
		///////////////////////////////////////////////////////////////////////
		// HEADER
		$out .= "\x00\x00\x00\x0D";
		$tmp  = 'IHDR';
		$tmp .= $this->ndword($this->m_gfh->m_nWidth);
		$tmp .= $this->ndword($this->m_gfh->m_nHeight);
		$tmp .= "\x08\x03\x00\x00\x00";
		$out .= $tmp;
		$out .= $this->ndword(crc32($tmp));
		///////////////////////////////////////////////////////////////////////
		// PALETTE
		if ($nColors > 0) {
			$out .= $this->ndword($nColors * 3);
			$tmp  = 'PLTE';
			$tmp .= $pal;
			$out .= $tmp;
			$out .= $this->ndword(crc32($tmp));
		}
		///////////////////////////////////////////////////////////////////////
		// TRANSPARENCY
		if (@$this->m_img->m_bTrans && ($nColors > 0)) {
			$out .= $this->ndword($nColors);
			$tmp  = 'tRNS';
			for ($i = 0; $i < $nColors; $i++) {
				$tmp .= ($i == $this->m_img->m_nTrans) ? "\x00" : "\xFF";
			}
			$out .= $tmp;
			$out .= $this->ndword(crc32($tmp));
		}
		///////////////////////////////////////////////////////////////////////
		// DATA BITS
		$out .= $this->ndword(strlen($bmp));
		$tmp  = 'IDAT';
		$tmp .= $bmp;
		$out .= $tmp;
		$out .= $this->ndword(crc32($tmp));
		///////////////////////////////////////////////////////////////////////
		// END OF FILE
		$out .= "\x00\x00\x00\x00IEND\xAE\x42\x60\x82";

		return $out;
	}

	///////////////////////////////////////////////////////////////////////////

	// Added by James Heinrich <info@silisoftware.com> - January 5, 2003

	// Takes raw image data and plots it pixel-by-pixel on a new GD image and returns that
	// It's extremely slow, but the only solution when ImageCreateFromString() fails
	function getGD_PixelPlotterVersion()
	{
		if (!$this->m_bLoaded) {
			return false;
		}

		// PREPARE COLOR TABLE (RGBQUADs)
		if ($this->m_img->m_gih->m_bLocalClr) {
			$pal = $this->m_img->m_gih->m_colorTable->toString();
		} elseif ($this->m_gfh->m_bGlobalClr) {
			$pal = $this->m_gfh->m_colorTable->toString();
		} else {
			die('No color table available in getGD_PixelPlotterVersion()');
		}

		$PlottingIMG = ImageCreate($this->m_gfh->m_nWidth, $this->m_gfh->m_nHeight);
		$NumColorsInPal = floor(strlen($pal) / 3);
		for ($i = 0; $i < $NumColorsInPal; $i++) {
			$ThisImageColor[$i] = ImageColorAllocate(
									$PlottingIMG,
									ord($pal{(($i * 3) + 0)}),
									ord($pal{(($i * 3) + 1)}),
									ord($pal{(($i * 3) + 2)}));
		}

		// PREPARE BITMAP BITS
		$data = $this->m_img->m_data;
		$nPxl = ($this->m_gfh->m_nHeight - 1) * $this->m_gfh->m_nWidth;
		for ($y = 0; $y < $this->m_gfh->m_nHeight; $y++) {
			if (!phpthumb_functions::FunctionIsDisabled('set_time_limit')) {
				set_time_limit(30);
			}
			for ($x = 0; $x < $this->m_gfh->m_nWidth; $x++, $nPxl++) {
				if (
					($x >= $this->m_img->m_gih->m_nLeft) &&
					($y >= $this->m_img->m_gih->m_nTop) &&
					($x <  ($this->m_img->m_gih->m_nLeft + $this->m_img->m_gih->m_nWidth)) &&
					($y <  ($this->m_img->m_gih->m_nTop  + $this->m_img->m_gih->m_nHeight))) {
					// PART OF IMAGE
					if (@$this->m_img->m_bTrans && (ord($data{$nPxl}) == $this->m_img->m_nTrans)) {
						ImageSetPixel($PlottingIMG, $x, $this->m_gfh->m_nHeight - $y - 1, $ThisImageColor[$this->m_gfh->m_nBgColor]);
					} else {
						ImageSetPixel($PlottingIMG, $x, $this->m_gfh->m_nHeight - $y - 1, $ThisImageColor[ord($data{$nPxl})]);
					}
				} else {
					// BACKGROUND
					ImageSetPixel($PlottingIMG, $x, $this->m_gfh->m_nHeight - $y - 1, $ThisImageColor[$this->m_gfh->m_nBgColor]);
				}
			}
			$nPxl -= $this->m_gfh->m_nWidth << 1;

		}

		return $PlottingIMG;
	}

	///////////////////////////////////////////////////////////////////////////

	function dword($val)
	{
		$val = intval($val);
		return chr($val & 0xFF).chr(($val & 0xFF00) >> 8).chr(($val & 0xFF0000) >> 16).chr(($val & 0xFF000000) >> 24);
	}

	///////////////////////////////////////////////////////////////////////////

	function ndword($val)
	{
		$val = intval($val);
		return chr(($val & 0xFF000000) >> 24).chr(($val & 0xFF0000) >> 16).chr(($val & 0xFF00) >> 8).chr($val & 0xFF);
	}

	///////////////////////////////////////////////////////////////////////////

	function width()
	{
		return $this->m_gfh->m_nWidth;
	}

	///////////////////////////////////////////////////////////////////////////

	function height()
	{
		return $this->m_gfh->m_nHeight;
	}

	///////////////////////////////////////////////////////////////////////////

	function comment()
	{
		return $this->m_img->m_lpComm;
	}

	///////////////////////////////////////////////////////////////////////////

	function loaded()
	{
		return $this->m_bLoaded;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////

?>