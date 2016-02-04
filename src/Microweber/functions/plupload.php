<?php


$dangerous = array(
    'php',
    'php5',
    'php4',
    'php3',
    'ptml',
    'hphp',
    'html',
    'xhtml',
    'phtml',
    'shtml',
    'htm',
    'pl',
    'js',
    'cgi',
    'rb',
    'py',
    'asp',
    'htaccess',
    'exe',
    'msi',
    'sh',
    'bat',
    'vbs',
    'vb',
    'lnk',

    // from http://www.file-extensions.org/filetype/extension/name/program-executable-files
    'action ',  //  Automator Action  Mac OS
    'apk',      //     Application  Android
    'app',      //     Executable  Mac OS
    'bat',      //     Batch File  Windows
    'bin',      //     Binary Executable  Windows, Mac OS, Linux
    'cmd',      //     Command Script  Windows
    'com',      //     Command File  Windows
    'command',  //   Terminal Command  Mac OS
    'cpl',      //     Control Panel Extension  Windows
    'csh',      //     C Shell Script  Mac OS, Linux
    'exe',      //     Executable  Windows
    'gadget',   //  Windows Gadget  Windows
    'inf1',     //      Setup Information File  Windows
    'ins',      //     Internet Communication Settings  Windows
    'inx',      //     InstallShield Compiled Script  Windows
    'ipa',      //     Application  iOS
    'isu',      //     InstallShield Uninstaller Script  Windows
    'job',      //     Windows Task Scheduler Job File  Windows
    'jse',      //     JScript Encoded File  Windows
    'ksh',      //     Unix Korn Shell Script  Linux
    'lnk',      //     File Shortcut  Windows
    'msc',      //     Microsoft Common Console Document  Windows
    'msi',      //     Windows Installer Package  Windows
    'msp',      //     Windows Installer Patch  Windows
    'mst',      //     Windows Installer Setup Transform File  Windows
    'osx',      //     Executable  Mac OS
    'out',      //     Executable  Linux
    'paf',      //     Portable Application Installer File  Windows
    'pif',      //     Program Information File  Windows
    'prg',      //     Executable  GEM
    'ps1',      //     Windows PowerShell Cmdlet  Windows
    'reg',      //     Registry Data File  Windows
    'rgs',      //     Registry Script  Windows
    'run',      //     Executable  Linux
    'sct',      //     Windows Scriptlet  Windows
    'shb',      //     Windows Document Shortcut  Windows
    'shs',      //     Shell Scrap Object  Windows
    'u3p',      //     U3 Smart Application  Windows
    'vb',       //    VBScript File  Windows
    'vbe',      //     VBScript Encoded Script  Windows
    'vbs',      //     VBScript File  Windows
    'vbscript', //    Visual Basic Script  Windows
    'workflow', //    Automator Workflow  Mac OS
    'ws',       //    Windows Script  Windows
    'wsf',      //  Windows Script  Windows

    // from http://fileinfo.com/filetypes/executable
    '0xe',        //    F-Secure Renamed Virus File',
    '73k',        //    TI-73 Application',
    '89k',        //    TI-89 Application',
    '8ck',        //    TI-83 and TI-84 Plus Application
    'a6p',        //    Authorware 6 Program',
    'a7r',        //    Authorware 7 Runtime File',
    'ac',        //    Autoconf Script',
    'acc',        //    GEM Accessory File',
    'acr',        //    ACRobot Script',
    'action',        //    Automator Action',
    'actm',        //    AutoCAD Action Macro File',
    'ahk',        //    AutoHotkey Script',
    'air',        //    Adobe AIR Installation Package',
    'apk',        //    Android Package File',
    'app',        //    Mac OS X Application',
    'app',        //    FoxPro Generated Application',
    'app',        //    Symbian OS Application',
    'applescripT',        //    AppleScript File',
    'arscript',        //    ArtRage Script',
    'asb',        //    Alphacam Stone VB Macro File',
    'azw2',        //    Kindle Active Content App File',
    'ba_',        //    Renamed BAT File',
    'bat',        //    DOS Batch File',
    'beam',        //    Compiled Erlang File',
    'bin',        //    Generic Binary Executable File',
    'bin',        //    Unix Executable File',
    'btm',        //    4DOS Batch File',
    'caction',        //    Automator Converter Action',
    'cel',        //    Celestia Script File',
    'celx',        //    Celestia Script',
    'cgi',        //    Common Gateway Interface Script',
    'cmd',        //    Windows Command File',
    'cof',        //    MPLAB COFF File',
    'com',        //    DOS Command File',
    'command',        //    Terminal Command File',
    'csh',        //    C Shell Script',
    'cyw',        //    Rbot.CYW Worm File',
    'dek',        //    Eavesdropper Batch File',
    'dld',        //    EdLog Compiled Program',
    'dmc',        //    Medical Manager Script',
    'ds',        //    TWAIN Data Source',
    'dxl',        //    Rational DOORS Script',
    'e_e',        //    Renamed EXE File',
    'ear',        //    Java Enterprise Archive File',
    'ebm',        //    EXTRA! Basic Macro',
    'ebs',        //    E-Run 1.x Script',
    'ebs2',        //    E-Run 2.0 Script File',
    'ecf',        //    SageCRM Component File',
    'eham',        //    ExtraHAM Executable File',
    'elf',        //    Nintendo Wii Game File',
    'es',        //    SageCRM Script File',
    'esh',        //    Extended Shell Batch File',
    'ex4',        //    MetaTrader Program File',
    'ex_',        //    Compressed Executable File',
    'ex_',        //    Renamed Windows Executable File',
    'exe',        //    Windows Executable File',
    'exe',        //    PortableApps.com Application',
    'exe1',        //    Renamed EXE File',
    'exopc',        //    ExoPC Application',
    'ezs',        //    EZ-R Stats Batch Script',
    'ezt',        //    EZT Malicious Worm File',
    'fas',        //    Compiled Fast-Load AutoLISP File'
    'fas',        //    QuickSilver Fast Save Lisp File',
    'fky',        //    FoxPro Macro',
    'fpi',        //    FPS Creator Intelligence Script',
    'frs',        //    Flash Renamer Script',
    'fxp',        //    FoxPro Compiled Program',
    'gadget',        //    Windows Gadget',
    'gpe',        //    GP2X Video Game',
    'gpu',        //    GP2X Utility Program',
    'gs',        //    Geosoft Script',
    'ham',        //    HAM Executable File',
    'hms',        //    HostMonitor Script File',
    'hpf',        //    HP9100A Program File',
    'hta',        //    HTML Application',
    'icd',        //    SafeDisc Encrypted Program',
    'iim',        //    iMacro Macro File',
    'ipa',        //    iOS Application',
    'ipf',        //    SMS Installer Script',
    'isu',        //    InstallShield Uninstaller Script'
    'ita',        //    VTech InnoTab Application File',
    'jar',        //    Java Archive File',
    'js',        //    JScript Executable Script',
    'jse',        //    JScript Encoded File',
    'jsx',        //    ExtendScript Script File',
    'kix',        //    KiXtart Script File',
    'ksh',        //    Unix Korn Shell Script',
    'lo',        //    Interleaf Compiled Lisp File',
    'ls',        //    LightWave LScript File',
    'm3g',        //    Mobile 3D Graphics Program',
    'mam',        //    Microsoft Access Macro',
    'mcr',        //    3ds Max Macroscript File',
    'mcr',        //    Tecplot Macro',
    'mel',        //    Maya Embedded Language File',
    'mem',        //    Macro Editor Macro',
    'mio',        //    MioEngine Application File',
    'mm',        //    NeXtMidas Macro File',
    'mpx',        //    FoxPro Compiled Menu Program',
    'mrc',        //    mIRC Script File',
    'mrp',        //    Mobile Application File',
    'ms',        //    3ds Max Script File',
    'ms',        //    Maxwell Script',
    'msl',        //    Magick Scripting Language File',
    'mxe',        //    Macro Express Playable Macro',
    'n',        //    Neko Bytecode File',
    'nexe',        //    Chrome Native Client Executable',
    'ore',        //    Ore Executable File',
    'osx',        //    PowerPC Executable File',
    'otm',        //    Outlook Macro File',
    'out',        //    Compiled Executable File',
    'paf',        //    Portable Application Installer Fi
    'paf.exe',        //    PortableApps.com Program File
    'pex',        //    ProBoard Executable File',
    'phar',        //    PHP Archive',
    'pif',        //    Program Information File',
    'plsc',        //    Messenger Plus! Live Script File'
    'plx',        //    Perl Executable File',
    'prc',        //    Palm Resource Code File',
    'prg',        //    Program File',
    'prg',        //    GEM Application',
    'ps1',        //    Windows PowerShell Cmdlet File',
    'pvd',        //    Instalit Script',
    'pwc',        //    PictureTaker File',
    'pyc',        //    Python Compiled File',
    'pyo',        //    Python Optimized Code',
    'qit',        //    QIT Trojan Horse File',
    'qpx',        //    FoxPro Compiled Query Program',
    'rbx',        //    Rembo-C Compiled Script',
    'rfu',        //    Remote Firmware Update',
    'rgs',        //    Registry Script',
    'rox',        //    Actuate Report Object Executable
    'rpj',        //    Real Pac Batch Job File',
    'run',        //    Linux Executable File',
    'rxe',        //    Lego Mindstorms NXT Executable Pr
    's2a',        //    SEAL2 Application',
    'sbs',        //    SPSS Script',
    'sca',        //    Scala Script File',
    'scar',        //    SCAR Script',
    'scb',        //    Scala Published Script',
    'scpt',        //    AppleScript Script File',
    'scptd',        //    AppleScript Script Bundle',
    'scr',        //    Script File',
    'script',        //    Generic Script File',
    'sct',        //    Windows Scriptlet',
    'seed',        //    Linux Preseed File',
    'shb',        //    Windows Document Shortcut',
    'smm',        //    Ami Pro Macro',
    'spr',        //    FoxPro Generated Screen File',
    'tcp',        //    Tally Compiled Program File',
    'thm',        //    Thermwood Macro File',
    'tms',        //    Telemate Script',
    'u3p',        //    U3 Smart Application',
    'udf',        //    Excel User Defined Function',
    'upx',        //    Ultimate Packer for eXecutables F
    'vb',        //    VBScript File',    	//
    'vbe',        //    VBScript Encoded Script File',
    'vbs',        //    VBScript File',
    'vbscript',        //    Visual Basic Script',
    'vdo',        //    Heathen Virus File',
    'vexe',        //    Virus Executable File',
    'vlx',        //    Compiled AutoLISP File',
    'vpm',        //    Vox Proxy Macro File',
    'vxp',        //    Mobile Application File',
    'wcm',        //    WordPerfect Macro',
    'widget',        //    Microsoft Windows Mobile Widget',
    'widget',        //    Yahoo! Widget',
    'wiz',        //    Microsoft Wizard File',
    'workflow',        //    Automator Workflow',
    'wpk',        //    WordPerfect Macro',
    'wpm',        //    WordPerfect Macro File',
    'ws',        //    Windows Script',
    'wsf',        //    Windows Script File',
    'wsh',        //    Windows Script Host Settings',
    'x86',        //    Linux Executable File',
    'xap',        //    Silverlight Application Package',
    'xbap',        //    XAML Browser Application File',
    'xlm',        //    Excel Macro',
    'xqt',        //    SuperCalc Macro File',
    'xys',        //    XYplorer Script File',
    'zl9',        //    ZoneAlarm Quarantined EXE File


);

if (!mw()->user_manager->session_id() or (mw()->user_manager->session_all()==false)){
    // //session_start();
}
$validate_token = false;
if (!isset($_SERVER['HTTP_REFERER'])){
    die('{"jsonrpc" : "2.0", "error" : {"code":97, "message": "You are not allowed to upload"}}');
} elseif (!stristr($_SERVER['HTTP_REFERER'], site_url())) {
//    if (!is_logged()){
//        die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": "You cannot upload from remote domains"}}');
//    }
}


if (!is_admin()){
    $validate_token = mw()->user_manager->csrf_validate($_GET);
    if ($validate_token==false){
        die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": "You are not allowed to upload"}}');
    }


    $is_ajax = mw()->url_manager->is_ajax();
    if ($is_ajax!=false){
        die('{"jsonrpc" : "2.0", "error" : {"code":99, "message": "You are not allowed to upload"}}');
    }


}

$host = (parse_url(site_url()));

$host_dir = false;
if (isset($host['host'])){
    $host_dir = $host['host'];
    $host_dir = str_ireplace('www.', '', $host_dir);
    $host_dir = str_ireplace('.', '-', $host_dir);
}


$fileName_ext = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';


$is_ext = get_file_extension($fileName_ext);
$is_ext = strtolower($is_ext);

if (in_array($is_ext, $dangerous)){


    die('{"jsonrpc" : "2.0", "error" : {"code":100, "message": "You cannot upload scripts or executable files"}}');
}


$allowed_to_upload = false;

if (is_admin()!=false){
    $allowed_to_upload = true;

} else {
    $uid = user_id();
    if ($uid!=0){
        $user = mw()->user_manager->get_by_id($uid);
        if (!empty($user) and isset($user["is_active"]) and $user["is_active"]==1){
            $are_allowed = 'img';
            $_REQUEST["path"] = 'media/' . $host_dir . DS . 'user_uploads/user/' . DS . $user["id"] . DS;
            $allowed_to_upload = true;
        }
    } else {
        $_REQUEST["path"] = 'media/' . $host_dir . DS . 'user_uploads/anonymous/';
        $allowed_to_upload = true;
    }

}


if ($allowed_to_upload==false){

    if (isset($_REQUEST["rel_type"]) and isset($_REQUEST["custom_field_id"]) and trim($_REQUEST["rel_type"])!='' and trim($_REQUEST["rel_type"])!='false'){

        $cfid = mw()->fields_manager->get_by_id(intval($_REQUEST["custom_field_id"]));
        if ($cfid==false){
            die('{"jsonrpc" : "2.0", "error" : {"code": 90, "message": "Custom field is not found"}}');

        } else {

            $rel_error = false;
            if (!isset($_REQUEST["rel_id"])){
                $rel_error = true;
            }
            if (!isset($cfid["rel_id"])){
                $rel_error = true;
            }

            if (($_REQUEST["rel_id"])!=$cfid["rel_id"]){
                $rel_error = true;
            }

            if ($rel_error){
                die('{"jsonrpc" : "2.0", "error" : {"code": 91, "message": "You are not allowed to upload"}}');
            }
        }


        if ($cfid!=false and isset($cfid['custom_field_type'])){
            if ($cfid['custom_field_type']!='upload'){
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Custom field is not file upload type"}}');

            }
            if ($cfid!=false and (!isset($cfid['options']) or !isset($cfid['options']['file_types']))){
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "File types is not set."}}');

            }
            if ($cfid!=false and isset($cfid['file_types']) and empty($cfid['file_types'])){
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "File types cannot by empty."}}');
            }

            if ($cfid!=false and isset($cfid['options']) and isset($cfid['options']['file_types'])){

                $alloled_ft = array_values(($cfid['options']['file_types']));
                if (empty($alloled_ft)){
                    die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "File types cannot by empty."}}');
                } else {
                    $are_allowed = '';
                    $fileName_ext = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
                    foreach ($alloled_ft as $allowed_file_type_item) {
                        if (trim($allowed_file_type_item)!='' and $fileName_ext!=''){
                            $is_ext = get_file_extension($fileName_ext);
                            $is_ext = strtolower($is_ext);

                            switch ($is_ext) {
                                case 'php':
                                case 'php5':
                                case 'php4':
                                case 'php3':
                                case 'ptml':
                                case 'html':
                                case 'xhtml':
                                case 'phtml':
                                case 'shtml':
                                case 'htm':
                                case 'pl':
                                case 'cgi':
                                case 'rb':
                                case 'py':
                                case 'asp':
                                case 'htaccess':
                                case 'exe':
                                case 'msi':
                                case 'sh':
                                case 'bat':
                                case 'vbs':
                                    $are_allowed = false;
                                    die('{"jsonrpc" : "2.0", "error" : {"code":105, "message": "You cannot upload scripts or executables"}}');

                                    break;
                            }


                            switch ($allowed_file_type_item) {


                                case 'img':
                                case 'image':
                                case 'images':
                                    $are_allowed .= ',png,gif,jpg,jpeg,tiff,bmp,svg';
                                    break;
                                case 'video':
                                case 'videos':
                                    $are_allowed .= ',avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov,wmv';
                                    break;
                                case 'file':
                                case 'files':
                                    $are_allowed .= ',doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv,7z';
                                    break;
                                case 'documents':
                                case 'doc':
                                    $are_allowed .= ',doc,docx,log,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
                                    break;
                                case 'archives':
                                case 'arc':
                                case 'arch':
                                    $are_allowed .= ',zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
                                    break;
                                case 'all':
                                    $are_allowed .= ',*';
                                    break;
                                case '*':
                                    $are_allowed .= ',*';
                                    break;
                                default:


                                    $are_allowed .= ',' . $allowed_file_type_item;


                            }
                            $pass_type_check = false;
                            if ($are_allowed!=false){
                                $are_allowed_a = explode(',', $are_allowed);
                                if (!empty($are_allowed_a)){
                                    foreach ($are_allowed_a as $are_allowed_a_item) {
                                        $are_allowed_a_item = strtolower(trim($are_allowed_a_item));
                                        $is_ext = strtolower(trim($is_ext));


                                        if ($are_allowed_a_item=='*'){
                                            $pass_type_check = 1;
                                        }

                                        if ($are_allowed_a_item!='' and $are_allowed_a_item==$is_ext){
                                            $pass_type_check = 1;
                                        }
                                    }

                                }
                            }
                            if ($pass_type_check==false){
                                die('{"jsonrpc" : "2.0", "error" : {"code":106, "message": "You can only upload ' . $are_allowed . ' files."}}');

                            } else {
                                if (!isset($_REQUEST['captcha'])){
                                    if (!$validate_token){
                                        die('{"jsonrpc" : "2.0", "error" : {"code":107, "message": "Please enter the captcha answer!"}}');
                                    }
                                } else {
                                    $cap = mw()->user_manager->session_get('captcha');
                                    if ($cap==false){
                                        die('{"jsonrpc" : "2.0", "error" : {"code":108, "message": "You must load a captcha first!"}}');

                                    }
                                    $validate_captcha = $this->app->captcha->validate($_REQUEST['captcha']);
                                    if (!$validate_captcha){
                                        die('{"jsonrpc" : "2.0", "error" : {"code":109, "message": "Invalid captcha answer! "}}');
                                    } else {
                                        if (!isset($_REQUEST["path"])){
                                            $_REQUEST["path"] = 'media/' . $host_dir . '/user_uploads' . DS . $_REQUEST["rel_type"] . DS;
                                        }
                                    }
                                }


                                //die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": PECATA - Not finished yet."}}');

                            }
                        }
                    }
                }
            }
        }
    } else {
        die('{"jsonrpc" : "2.0", "error" : {"code": 110, "message": "Only admin can upload."}, "id" : "id"}');

    }
}

/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */
// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Settings
$target_path = media_base_path() . DS;
$target_path = media_base_path() . DS . $host_dir . DS . 'uploaded' . DS;
$target_path = normalize_path($target_path, 0);


$path_restirct = userfiles_path(); // the path the script should access
if (isset($_REQUEST["path"]) and trim($_REQUEST["path"])!='' and trim($_REQUEST["path"])!='false'){
    $path = urldecode($_REQUEST["path"]);

    $path = html_entity_decode($path);
    $path = htmlspecialchars_decode($path, ENT_NOQUOTES);


    //$path = urldecode($path);
    $path = str_replace('%2F', '/', $path);
    //$path = str_replace('%25252F','/',$path);


    $path = normalize_path($path, 0);

    $path = str_replace('..', '', $path);
    $path = str_replace($path_restirct, '', $path);


    $target_path = userfiles_path() . DS . $path;
    $target_path = normalize_path($target_path, 1);
}


$targetDir = $target_path;
if (!is_dir($targetDir)){
    mkdir_recursive($targetDir);
}
//$targetDir = 'uploads';

$cleanupTargetDir = true;
// Remove old files
$maxFileAge = 5 * 3600;
// Temp file age in seconds
// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);
// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
$fileName = str_replace('..', '.', $fileName);
$fileName = strtolower($fileName);
// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)){
    $ext = strrpos($fileName, '.');
    $fileName_a = substr($fileName, 0, $ext);
    $fileName_b = substr($fileName, $ext);

    $count = 1;
    while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b)) {
        $count ++;
    }

    $fileName = $fileName_a . '_' . $count . $fileName_b;
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Create target dir
if (!is_dir($targetDir)){
    @mkdir_recursive($targetDir);
}

// Remove old temp files
if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))){
    while (($file = readdir($dir))!==false) {
        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath!="{$filePath}.part")){

            @unlink($tmpfilePath);
        }
    }

    closedir($dir);
} else {
    die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
}

if (isset($_SERVER["CONTENT_LENGTH"]) and isset($_FILES['file'])){
    $filename_log = mw()->url_manager->slug($fileName);
    $check = mw()->log_manager->get("one=true&no_cache=true&is_system=y&created_at=[mt]30 min ago&field=upload_size&rel=uploader&rel_id=" . $filename_log . "&user_ip=" . MW_USER_IP);
    $upl_size_log = $_SERVER["CONTENT_LENGTH"];
    if (is_array($check) and isset($check['id'])){
        $upl_size_log = intval($upl_size_log) + intval($check['value']);
        mw()->log_manager->save("no_cache=true&is_system=y&field=upload_size&rel=uploader&rel_id=" . $filename_log . "&value=" . $upl_size_log . "&user_ip=" . MW_USER_IP . "&id=" . $check['id']);
    } else {
        mw()->log_manager->save("no_cache=true&is_system=y&field=upload_size&rel=uploader&rel_id=" . $filename_log . "&value=" . $upl_size_log . "&user_ip=" . MW_USER_IP);
    }
}

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"])){
    $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
}

if (isset($_SERVER["CONTENT_TYPE"])){
    $contentType = $_SERVER["CONTENT_TYPE"];
}

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5

if (isset($contentType)){
    if (strpos($contentType, "multipart")!==false){


        if ($_FILES['file']['error']===UPLOAD_ERR_OK){
//uploading successfully done 
        } else {
            throw new UploadException($_FILES['file']['error']);
        }

    }


    if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])){
        // Open temp file
        $out = fopen("{$filePath}.part", $chunk==0 ? "wb" : "ab");
        if ($out){
            // Read binary input stream and append it to temp file
            $in = fopen($_FILES['file']['tmp_name'], "rb");

            if ($in){
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
            } else {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
            fclose($in);
            fclose($out);
            @unlink($_FILES['file']['tmp_name']);
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
    } else {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');


    }
} else {
    // Open temp file
    $out = fopen("{$filePath}.part", $chunk==0 ? "wb" : "ab");
    if ($out){
        // Read binary input stream and append it to temp file
        $in = fopen("php://input", "rb");

        if ($in){
            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
        }

        fclose($in);
        fclose($out);
    } else {
        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    }
}

// Check if file has been uploaded
if (!$chunks || $chunk==$chunks - 1){
    // Strip the temp .part suffix off
    rename("{$filePath}.part", $filePath);
    mw()->log_manager->delete("is_system=y&rel=uploader&created_at=[lt]30 min ago");
    mw()->log_manager->delete("is_system=y&rel=uploader&session_id=" . mw()->user_manager->session_id());

}
$f_name = explode(DS, $filePath);

$rerturn = array();
$rerturn['src'] = mw()->url_manager->link_to_file($filePath);
$rerturn['name'] = end($f_name);

if (isset($upl_size_log) and $upl_size_log > 0){
    $rerturn['bytes_uploaded'] = $upl_size_log;
}
//$rerturn['ORIG_REQUEST'] = $_GET;


print json_encode($rerturn);
if (mw()->user_manager->session_id() and !(mw()->user_manager->session_all()==false)){
    // @//session_write_close();

}


class UploadException extends Exception {
    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }

        return $message;
    }
}


exit;

// Return JSON-RPC response
//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
