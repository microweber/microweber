<?
//////////////////////////////////////////////////////////////////////////////////////////////
$thumb = new THUMB;
if($_GET["size"]) $thumb->set('thumbSize', $_GET["size"]);
elseif ($_GET["original"]) $thumb->set('originalSize', TRUE);
$thumb->makeThumb($file);
//////////////////////////////////////////////////////////////////////////////////////////////

############################################
# THUMBNAIL GENERATOR - v-2.0 - 29/09/2003 #
#              André Cupini                #
############################################
class THUMB
{
    function THUMB() {
        # Thumb size default
        $this->thumbSize    = 50;

        # String and color borders
        $this->R            = 0;    // Red
        $this->G            = 0;    // Green
        $this->B            = 0;    // Blue

        # Error border
        $this->drawBorder   = TRUE;

        # Image border
        $this->drawImgBorder= TRUE;

        # Supported files
        $this->setSupported(IMAGETYPE_GIF,  imageCreateFromGif);
        $this->setSupported(IMAGETYPE_JPEG, imageCreateFromJpeg);
        $this->setSupported(IMAGETYPE_PNG,  imageCreateFromPng);

        # Initial position of string
        $this->set(initialPixelCol, 3);
        $this->set(initialPixelRow, 2);

        # Keep Original size
        $this->originalSize = FALSE;
    }

    // Set values 
    function set($key, $value) {
        $this->$key = $value;
    }

    // Check if image type is supported
    function isSupported($file) {
        # Integer que indica o tipo da imagem
        $imageType  =  exif_imageType($file);
        $ext        =& $this->supportedExt; 
        $count      =& $ext["count"];
        for($i = 0; $i < $count; $i++) {
            if($ext["type"][$i] == $imageType){
                $supported = TRUE;
                break;
            } else $supported = FALSE;
        }
        return $supported;
    }

    // Return wich function will be used
    function retrieveFunction($file) {
        # image type
        $imageType  =  exif_imageType($file);
        $ext        =& $this->supportedExt;
        $count      =& $ext["count"];
        for($i = 0; $i < $count; $i++) {
            if($ext["type"][$i] == $imageType) return $ext["function"][$i];
        }
    }

    // supported images types
    function setSupported($value, $function) {
        $this->supportedExt["type"][]       = $value;
        $this->supportedExt["function"][]   = $function;
        $this->supportedExt["count"]        = count($this->supportedExt["type"]);
    }

    // Print string in line
    function writeLine($string, $width = FALSE) {
        if($width === FALSE) {
            $strLen = strlen($string);
            $width = ($strLen * 10) - ($strLen * 2.8);
        }
        $img		    = ImageCreate ($width+1, 16);
        $background     = ImageColorAllocate ($img, 255, 255, 255);
        $defaultColor   = ImageColorAllocate ($img, $this->R, $this->G, $this->B);
        if($this->drawBorder) ImageRectangle($img, 0, 0, $width, 15, $defaultColor);
        ImageString ($img, 3, $this->initialPixelCol, $this->initialPixelRow,  $string, $defaultColor);
        header("Content-type: image/png");
        ImagePNG($img);
    }

    // Generate thumbnail
    function makeThumb($file) {
        if(file_exists($file)) {
            # Dimensões originais da imagem
            list ($width, $height) = GetImageSize ($file);
            # If not size
            if($this->originalSize) $size = $width;     # Original size
            else $size = $this->thumbSize;              # Default size

            # Not supported image
            if(!$this->isSupported($file)) $this->writeLine("Tipo de imagem não suportado!");
            else {
                if ($width > $height) {
                    # Width greater than height
                    $newWidth  = $size;
                    $newHeight = ($newWidth * $height) / $width;
                } else {
                    # Height greater than width 
                    $newHeight = $size * .8;
                    $newWidth  = ($newHeight * $width) / $height;
                }
                $func   = $this->retrieveFunction($file);
                $src    = $func($file);
                $dst    = ImageCreateTrueColor ($newWidth -1, $newHeight -1);
                ImageCopyResized ($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                if($this->drawImgBorder) ImageRectangle($dst, 0, 0, $newWidth -2, $newHeight -2, $defaultColor);
                header("Content-type: image/png");
                ImagePNG($dst);
            }
        } else $this->writeLine("Imagem não encontrada!");
    }
}
?>
