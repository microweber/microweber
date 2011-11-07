NAME:

    Securimage - A PHP class for creating and managing form CAPTCHA images

VERSION: 2.0 BETA

AUTHOR:

    Drew Phillips <drew@drew-phillips.com>

DOWNLOAD:

    The latest version can always be
    found at http://www.phpcaptcha.org

DOCUMENTATION:

    Online documentation of the class, methods, and variables can
    be found at http://www.phpcaptcha.org/Securimage_Docs/

REQUIREMENTS:
    PHP 4.3.0
    GD  2.0
    FreeType (recommended, required for TTF support)

SYNOPSIS:

    require_once 'securimage.php';

    $image = new Securimage();

    $image->show();

    // Code Validation

    $image = new Securimage();
    if ($image->check($_POST['code']) == true) {
      echo "Correct!";
    } else {
      echo "Sorry, wrong code.";
    }

DESCRIPTION:

    What is Securimage?

    Securimage is a PHP class that is used to generate and validate CAPTCHA images.
    The classes uses an existing PHP session or creates its own if none is found to store the
    CAPTCHA code.  Variables within the class are used to control the style and display of the image.
    The class supports TTF fonts and effects for strengthening the security of the image.
    If TTF support is not available, GD fonts can be used as well, but certain options such as
    transparent text and angled letters cannot be used.


COPYRIGHT:
    Copyright (c) 2009 Drew Phillips. All rights reserved.
    This software is released under the GNU Lesser General Public License.

    -----------------------------------------------------------------------------
    Flash code created for Securimage by Douglas Walsh (www.douglaswalsh.net)
    Many thanks for releasing this to the project!

    ------------------------------------------------------------------------------
    Portions of Securimage contain code from Han-Kwang Nienhuys' PHP captcha
        
    Han-Kwang Nienhuys' PHP captcha
    Copyright June 2007
    
    This copyright message and attribution must be preserved upon
    modification. Redistribution under other licenses is expressly allowed.
    Other licenses include GPL 2 or higher, BSD, and non-free licenses.
    The original, unrestricted version can be obtained from
    http://www.lagom.nl/linux/hkcaptcha/
    
    -------------------------------------------------------------------------------
    AHGBold.ttf (AlteHaasGroteskBold.ttf) font was created by Yann Le Coroller and is distributed as freeware
    
    Alte Haas Grotesk is a typeface that look like an helvetica printed in an old Muller-Brockmann Book.
    
    These fonts are freeware and can be distributed as long as they are
    together with this text file. 
    
    I would appreciate very much to see what you have done with it anyway.
    
    yann le coroller 
    www.yannlecoroller.com
    yann@lecoroller.com

