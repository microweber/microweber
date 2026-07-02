<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Upload Disk
    |--------------------------------------------------------------------------
    |
    | The filesystem disk to use for storing uploaded files.
    |
    */
    'disk' => env('FILE_UPLOADER_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Default Upload Path
    |--------------------------------------------------------------------------
    |
    | The default path within the disk where files are uploaded.
    |
    */
    'upload_path' => env('FILE_UPLOADER_PATH', 'uploads'),

    /*
    |--------------------------------------------------------------------------
    | Maximum File Sizes (in KB)
    |--------------------------------------------------------------------------
    |
    | Define maximum file sizes per category.
    |
    */
    'size_limits' => [
        'images'    => 10240,    // 10 MB
        'videos'    => 102400,   // 100 MB
        'audios'    => 51200,    // 50 MB
        'documents' => 20480,    // 20 MB
        'archives'  => 102400,   // 100 MB
        'files'     => 10240,    // 10 MB
        'default'   => 10240,    // 10 MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatic Image Resize on Upload
    |--------------------------------------------------------------------------
    |
    | Whether to automatically resize large images on upload.
    |
    */
    'auto_resize_images' => env('FILE_UPLOADER_AUTO_RESIZE', false),

    /*
    |--------------------------------------------------------------------------
    | Auto Resize Threshold (bytes)
    |--------------------------------------------------------------------------
    |
    | Images larger than this will trigger auto-resize prompt or action.
    |
    */
    'auto_resize_threshold' => 2000000, // 2 MiB

    /*
    |--------------------------------------------------------------------------
    | Auto Resize Max Dimension (pixels)
    |--------------------------------------------------------------------------
    |
    | Maximum width/height for auto-resized images.
    |
    */
    'auto_resize_max_dimension' => 1980,

    /*
    |--------------------------------------------------------------------------
    | Temp File Max Age (seconds)
    |--------------------------------------------------------------------------
    |
    | Maximum age for temporary .part files before cleanup.
    |
    */
    'temp_file_max_age' => 18000, // 5 hours

    /*
    |--------------------------------------------------------------------------
    | Dangerous File Extensions
    |--------------------------------------------------------------------------
    |
    | Extensions that are NEVER allowed to be uploaded for security reasons.
    | This list overrides any allowed types.
    |
    */
    'dangerous_extensions' => [
        '0xe', '73k', '89k', '8ck', 'a4p', 'a5w', 'a6p', 'a7r', 'ac', 'acc',
        'acr', 'action', 'actm', 'adr', 'aex', 'ahk', 'air', 'alfa', 'alx', 'an',
        'ap', 'apk', 'app', 'appcache', 'applescript', 'aro', 'arscript', 'asa', 'asax', 'asb',
        'ascx', 'ashx', 'asmx', 'asp', 'aspx', 'asr', 'atom', 'att', 'awm', 'axd',
        'azw2', 'ba_', 'bat', 'beam', 'bin', 'bml', 'bok', 'br', 'browser', 'btapp',
        'btm', 'bwp', 'caction', 'ccbjs', 'cdf', 'cel', 'celx', 'cer', 'cfm', 'cfml',
        'cgi', 'cha', 'chat', 'chm', 'cmd', 'cms', 'codasite', 'cof', 'com', 'command',
        'compressed', 'con', 'cpg', 'cphd', 'cpl', 'crdownload', 'crl', 'crt', 'csh', 'cshtml',
        'csp', 'csr', 'cyw', 'dap', 'dbm', 'dcr', 'dek', 'der', 'dhtml', 'disco',
        'discomap', 'dld', 'dll', 'dmc', 'dml', 'do', 'dochtml', 'docmhtml', 'dosexec', 'dothtml',
        'download', 'ds', 'dwt', 'dxl', 'e_e', 'ear', 'ebm', 'ebs', 'ebs2', 'ece',
        'ecf', 'edge', 'eham', 'elf', 'epibrw', 'es', 'esh', 'esproj', 'ewp', 'ex4',
        'ex_', 'exe', 'exe1', 'exopc', 'ezs', 'ezt', 'faces', 'fas', 'fcgi', 'fky',
        'fmp', 'fpi', 'freeway', 'frs', 'fwp', 'fwtb', 'fwtemplate', 'fwtemplateb', 'fxp', 'gadget',
        'gne', 'gpe', 'gpu', 'gs', 'gsp', 'h5p', 'ham', 'har', 'hdm', 'hdml',
        'hms', 'hpf', 'hphp', 'hta', 'htaccess', 'htc', 'htm', 'html', 'htx', 'hxs',
        'hype', 'hyperesources', 'hypesymbol', 'hypetemplate', 'icd', 'idc', 'iim', 'inf1', 'ins', 'inx',
        'ipa', 'ipf', 'iqy', 'isu', 'ita', 'itms', 'itpc', 'iwdgt', 'jar', 'jcz',
        'jhtml', 'jnlp', 'job', 'js', 'jse', 'jsonl', 'jsp', 'jspa', 'jspx', 'jss',
        'jst', 'jsx', 'jvs', 'jws', 'kcmsf', 'key', 'kit', 'kix', 'ksh', 'lasso',
        'lbc', 'less', 'lnk', 'lo', 'ls', 'm3g', 'maff', 'mam', 'map', 'mapx',
        'master', 'mcr', 'mel', 'mem', 'mht', 'mhtml', 'mio', 'mjs', 'mm', 'mml',
        'moz', 'mpx', 'mrc', 'mrp', 'ms', 'msc', 'msi', 'msl', 'msp', 'mspx',
        'mst', 'muse', 'mvc', 'mvr', 'mxe', 'n', 'ndjson', 'nexe', 'nod', 'node',
        'nxg', 'nzb', 'oam', 'obml', 'obml15', 'obml16', 'ognc', 'olp', 'opml', 'ore',
        'osx', 'oth', 'otm', 'out', 'p12', 'p7', 'p7b', 'p7c', 'pac', 'paf',
        'paf.exe', 'page', 'pem', 'pex', 'phar', 'php', 'php10', 'php11', 'php12', 'php2',
        'php3', 'php4', 'php5', 'php56', 'php6', 'php7', 'php72', 'php73', 'php74', 'php8',
        'php81', 'php82', 'php9', 'phps', 'phpt', 'phtm', 'phtml', 'pif', 'pl', 'plsc',
        'plx', 'ppthtml', 'pptmhtml', 'prc', 'prf', 'prg', 'private', 'pro', 'ps1', 'psd1',
        'psm1', 'psp', 'ptml', 'ptw', 'pub', 'public', 'pvd', 'pwc', 'py', 'pyc',
        'pyo', 'qbo', 'qbx', 'qf', 'qit', 'qpx', 'qrm', 'razor', 'rb', 'rbx',
        'reg', 'rflw', 'rfu', 'rgs', 'rhtml', 'rjs', 'rox', 'rpj', 'rss', 'rt',
        'run', 'rw3', 'rwp', 'rwsw', 'rwtheme', 'rxe', 's2a', 'sass', 'saveddeck', 'sbs',
        'sca', 'scar', 'scb', 'scpt', 'scptd', 'scr', 'script', 'sct', 'sdb', 'seam',
        'seed', 'sh', 'shb', 'shs', 'sht', 'shtm', 'shtml', 'site', 'sitemap', 'sites',
        'sites2', 'smm', 'sparkle', 'spc', 'spr', 'srf', 'srl', 'ssp', 'stc', 'stl',
        'stm', 'stml', 'stp', 'strm', 'suck', 'svc', 'svr', 'swf', 'swz', 'tcp',
        'thm', 'tms', 'tpl', 'tvpi', 'tvvi', 'u3p', 'ucf', 'udf', 'uhtml', 'upx',
        'url', 'vb', 'vbd', 'vbe', 'vbhtml', 'vbs', 'vbscript', 'vdo', 'vdw', 'vexe',
        'vlp', 'vlx', 'vpm', 'vrml', 'vrt', 'vsdisco', 'vxp', 'wbs', 'wbxml', 'wcm',
        'wdgt', 'web', 'webarchive', 'webarchivexml', 'webbookmark', 'webhistory', 'webloc', 'webmanifest', 'website', 'wgp',
        'wgt', 'whtt', 'widget', 'wiz', 'wml', 'wn', 'woa', 'workflow', 'wpk', 'wpm',
        'wpp', 'wpx', 'ws', 'wsc', 'wsdl', 'wsf', 'wsh', 'x86', 'xap', 'xbap',
        'xbel', 'xbl', 'xd', 'xfdl', 'xht', 'xhtm', 'xhtml', 'xlm', 'xml', 'xpd',
        'xqt', 'xss', 'xul', 'xws', 'xys', 'zfo', 'zhtml', 'zl9', 'zul', 'zvz',
    ],

    /*
    |--------------------------------------------------------------------------
    | MIME Type Mappings
    |--------------------------------------------------------------------------
    |
    | Maps MIME types to file categories and their allowed extensions.
    |
    */
    'mime_type_mappings' => [
        'images' => [
            'image/jpeg'      => ['jpg', 'jpeg', 'jpe'],
            'image/png'       => ['png'],
            'image/gif'       => ['gif'],
            'image/webp'      => ['webp'],
            'image/svg+xml'   => ['svg'],
            'image/tiff'      => ['tiff', 'tif'],
            'image/bmp'       => ['bmp'],
            'image/x-icon'    => ['ico'],
        ],
        'videos' => [
            'video/mp4'       => ['mp4', 'm4v'],
            'video/avi'       => ['avi'],
            'video/x-msvideo' => ['avi'],
            'video/mpeg'      => ['mpg', 'mpeg'],
            'video/webm'      => ['webm'],
            'video/ogg'       => ['ogv', 'ogg'],
            'video/quicktime' => ['mov'],
            'video/x-ms-wmv'  => ['wmv'],
            'video/3gpp'      => ['3gp'],
            'video/3gpp2'     => ['3g2'],
        ],
        'audios' => [
            'audio/mpeg' => ['mp3'],
            'audio/ogg'  => ['ogg'],
            'audio/wav'  => ['wav'],
            'audio/flac' => ['flac'],
            'audio/mp4'  => ['m4a'],
            'audio/aac'  => ['aac'],
        ],
        'documents' => [
            'application/pdf'       => ['pdf'],
            'application/msword'    => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'application/vnd.ms-excel' => ['xls'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
            'application/vnd.ms-powerpoint' => ['ppt'],
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['pptx'],
            'application/rtf'       => ['rtf'],
            'text/plain'            => ['txt'],
            'text/xml'              => ['xml'],
            'application/vnd.oasis.opendocument.text' => ['odt'],
        ],
        'archives' => [
            'application/zip'              => ['zip'],
            'application/x-zip-compressed' => ['zip'],
            'application/x-rar-compressed' => ['rar'],
            'application/x-7z-compressed'  => ['7z'],
            'application/gzip'             => ['gz', 'gzip'],
            'application/x-tar'            => ['tar'],
            'application/x-compressed-tar' => ['tar.gz', 'tgz'],
        ],
        'files' => [
            'text/css'                   => ['css'],
            'application/json'           => ['json'],
            'application/font-woff'      => ['woff'],
            'application/font-woff2'     => ['woff2'],
            'font/ttf'                   => ['ttf'],
            'font/otf'                   => ['otf'],
            'image/vnd.microsoft.icon'   => ['ico'],
            'text/csv'                   => ['csv'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Extensions by Category
    |--------------------------------------------------------------------------
    |
    | Extensions grouped by file category. Used for extension-based validation.
    |
    */
    'allowed_extensions' => [
        'images'    => ['png', 'gif', 'jpg', 'jpeg', 'jpe', 'tiff', 'bmp', 'webp', 'ico', 'svg'],
        'videos'    => ['mp4', 'm4v', 'avi', 'asf', 'mpg', 'mpeg', 'flv', 'mkv', 'webm', 'ogg', 'ogv', '3gp', '3g2', 'wma', 'mov', 'wmv'],
        'audios'    => ['mp3', 'mp4', 'ogg', 'wav', 'flac', 'm4a', 'aac'],
        'documents' => ['doc', 'docx', 'pdf', 'odt', 'rtf', 'txt', 'pps', 'ppt', 'pptx', 'xls', 'xlsx', 'xml'],
        'archives'  => ['zip', 'zipx', 'gzip', 'rar', 'gz', '7z', 'cbr', 'tar.gz', 'tgz'],
        'files'     => ['css', 'json', 'zip', 'gzip', 'psd', 'csv', '7z', 'rar', 'gz', 'woff', 'woff2', 'ttf', 'oet', 'otf', 'cur', 'ico'],
    ],
];