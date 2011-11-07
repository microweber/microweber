<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// phpthumb.ico.php - .ICO output format functions          //
//                                                         ///
//////////////////////////////////////////////////////////////


class phpthumb_ico {

	function phpthumb_ico() {
		return true;
	}


	function GD2ICOstring(&$gd_image_array) {
		foreach ($gd_image_array as $key => $gd_image) {

			$ImageWidths[$key]  = ImageSX($gd_image);
			$ImageHeights[$key] = ImageSY($gd_image);
	    	$bpp[$key]          = ImageIsTrueColor($gd_image) ? 32 : 24;
	    	$totalcolors[$key]  = ImageColorsTotal($gd_image);

			$icXOR[$key] = '';
			for ($y = $ImageHeights[$key] - 1; $y >= 0; $y--) {
				for ($x = 0; $x < $ImageWidths[$key]; $x++) {
					$argb = phpthumb_functions::GetPixelColor($gd_image, $x, $y);
					$a = round(255 * ((127 - $argb['alpha']) / 127));
					$r = $argb['red'];
					$g = $argb['green'];
					$b = $argb['blue'];

					if ($bpp[$key] == 32) {
						$icXOR[$key] .= chr($b).chr($g).chr($r).chr($a);
					} elseif ($bpp[$key] == 24) {
						$icXOR[$key] .= chr($b).chr($g).chr($r);
					}

					if ($a < 128) {
						@$icANDmask[$key][$y] .= '1';
					} else {
						@$icANDmask[$key][$y] .= '0';
					}
				}
				// mask bits are 32-bit aligned per scanline
				while (strlen($icANDmask[$key][$y]) % 32) {
					$icANDmask[$key][$y] .= '0';
				}
			}
			$icAND[$key] = '';
			foreach ($icANDmask[$key] as $y => $scanlinemaskbits) {
				for ($i = 0; $i < strlen($scanlinemaskbits); $i += 8) {
					$icAND[$key] .= chr(bindec(str_pad(substr($scanlinemaskbits, $i, 8), 8, '0', STR_PAD_LEFT)));
				}
			}

		}

	    foreach ($gd_image_array as $key => $gd_image) {
			$biSizeImage = $ImageWidths[$key] * $ImageHeights[$key] * ($bpp[$key] / 8);

	    	// BITMAPINFOHEADER - 40 bytes
			$BitmapInfoHeader[$key]  = '';
			$BitmapInfoHeader[$key] .= "\x28\x00\x00\x00";                              // DWORD  biSize;
			$BitmapInfoHeader[$key] .= phpthumb_functions::LittleEndian2String($ImageWidths[$key], 4);      // LONG   biWidth;
			// The biHeight member specifies the combined
			// height of the XOR and AND masks.
			$BitmapInfoHeader[$key] .= phpthumb_functions::LittleEndian2String($ImageHeights[$key] * 2, 4); // LONG   biHeight;
	    	$BitmapInfoHeader[$key] .= "\x01\x00";                                      // WORD   biPlanes;
	   		$BitmapInfoHeader[$key] .= chr($bpp[$key])."\x00";                          // wBitCount;
			$BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";                              // DWORD  biCompression;
			$BitmapInfoHeader[$key] .= phpthumb_functions::LittleEndian2String($biSizeImage, 4);            // DWORD  biSizeImage;
	    	$BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";                              // LONG   biXPelsPerMeter;
	    	$BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";                              // LONG   biYPelsPerMeter;
	    	$BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";                              // DWORD  biClrUsed;
	    	$BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";                              // DWORD  biClrImportant;
		}


		$icondata  = "\x00\x00";                                      // idReserved;   // Reserved (must be 0)
		$icondata .= "\x01\x00";                                      // idType;       // Resource Type (1 for icons)
		$icondata .= phpthumb_functions::LittleEndian2String(count($gd_image_array), 2);  // idCount;      // How many images?

		$dwImageOffset = 6 + (count($gd_image_array) * 16);
		foreach ($gd_image_array as $key => $gd_image) {
	    	// ICONDIRENTRY   idEntries[1]; // An entry for each image (idCount of 'em)

	    	$icondata .= chr($ImageWidths[$key]);                     // bWidth;          // Width, in pixels, of the image
	    	$icondata .= chr($ImageHeights[$key]);                    // bHeight;         // Height, in pixels, of the image
	    	$icondata .= chr($totalcolors[$key]);                     // bColorCount;     // Number of colors in image (0 if >=8bpp)
	    	$icondata .= "\x00";                                      // bReserved;       // Reserved ( must be 0)

	    	$icondata .= "\x01\x00";                                  // wPlanes;         // Color Planes
			$icondata .= chr($bpp[$key])."\x00";                      // wBitCount;       // Bits per pixel

			$dwBytesInRes = 40 + strlen($icXOR[$key]) + strlen($icAND[$key]);
			$icondata .= phpthumb_functions::LittleEndian2String($dwBytesInRes, 4);       // dwBytesInRes;    // How many bytes in this resource?

		    $icondata .= phpthumb_functions::LittleEndian2String($dwImageOffset, 4);      // dwImageOffset;   // Where in the file is this image?
			$dwImageOffset += strlen($BitmapInfoHeader[$key]);
			$dwImageOffset += strlen($icXOR[$key]);
			$dwImageOffset += strlen($icAND[$key]);
	    }

	    foreach ($gd_image_array as $key => $gd_image) {
			$icondata .= $BitmapInfoHeader[$key];
			$icondata .= $icXOR[$key];
			$icondata .= $icAND[$key];
	    }

	    return $icondata;
	}

}

?>