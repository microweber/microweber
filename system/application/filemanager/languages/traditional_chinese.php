<?php
// $Id: traditional_chinese.php 149 2010-06-16 23:13:22Z which $
// Traditional Chinese Language Module for v2.3 (translated by www.which.tw)
// Additional translation by sloarch, mic, Paulino Michelazzo

global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
  	"error"			=> "錯誤",
		"message"			=> "訊息",
  	"back"			=> "回上頁",
	
	// root
	"home"			=> "主目錄並不存在, 請檢查設定.",
	"abovehome"		=> "目前的目錄可能沒有在主目錄上.",
	"targetabovehome"	=> "目標的目錄可能沒有在主目錄上.",
	
	// exist
	"direxist"		=> "此目錄不存在.",
	//"filedoesexist"	=> "此目錄已存在.",
	"fileexist"		=> "此檔案不存在.",
	"itemdoesexist"		=> "此項目已存在.",
	"itemexist"		=> "此項目不存在.",
	"targetexist"		=> "此目標目錄不存在.",
	"targetdoesexist"	=> "此目標項目已存在.",
	
	// open
	"opendir"		=> "無法打開目錄.",
	"readdir"		=> "無法讀取目錄.",
	
	// access
	"accessdir"		=> "不允許您存取這個目錄.",
	"accessfile"		=> "不允許您存取這個檔案.",
	"accessitem"		=> "不允許您存取這個項目.",
	"accessfunc"		=> "不允許您使用這個功能.",
	"accesstarget"		=> "不允許您存取這個目標目錄.",
	
	// actions
	"permread"		=> "取得權限失敗.",
	"permchange"		=> "權限更改失敗 (這通常是因為檔案所有權的問題 - 例如. 如果HTTP使用者('wwwrun' 或 'nobody')跟FTP使用者不一致時)",
	"openfile"		=> "打開檔案失敗.",
	"savefile"		=> "檔案儲存失敗.",
	"createfile"		=> "新增檔案失敗.",
	"createdir"		=> "新增目錄失敗.",
	"uploadfile"		=> "檔案上傳失敗.",
	"copyitem"		=> "複製失敗.",
	"moveitem"		=> "移動失敗.",
	"delitem"		=> "刪除失敗.",
	"chpass"		=> "更改密碼失敗.",
	"deluser"		=> "移除使用者失敗.",
	"adduser"		=> "加入使用者失敗.",
	"saveuser"		=> "儲存使用者失敗.",
	"searchnothing"		=> "您必須輸入些什麼來搜尋.",
	
	// misc
	"miscnofunc"		=> "功能無效.",
	"miscfilesize"		=> "檔案大小已達最大.",
	"miscfilepart"		=> "檔案只有部分上傳.",
	"miscnoname"		=> "您必須輸入名稱.",
	"miscselitems"		=> "您還未選擇任何項目.",
	"miscdelitems"		=> "您確定要刪除這些 {0} 項目?",
	"miscdeluser"		=> "您確定要刪除使用者 '{0}'?",
	"miscnopassdiff"	=> "新密碼跟舊密碼相同.",
	"miscnopassmatch"	=> "密碼不符.",
	"miscfieldmissed"	=> "您遺漏一個重要欄位.",
	"miscnouserpass"	=> "使用者名稱或密碼錯誤.",
	"miscselfremove"	=> "您無法移除您自己.",
	"miscuserexist"		=> "使用者已存在.",
	"miscnofinduser"	=> "無法找到使用者.",
	"extract_noarchive" => "此檔案不是一個可以解壓縮的檔案.",
	"extract_unknowntype" => "未知的壓縮類型",
	
	'chmod_none_not_allowed' => '更改權限至 <none> 是不允許的',
	'archive_dir_notexists' => '您指定要儲存的目錄不存在.',
	'archive_dir_unwritable' => '請指定一個可寫入的目錄來儲存壓縮檔.',
	'archive_creation_failed' => '儲存壓縮檔失敗'

);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "更改權限",
	"editlink"		=> "編輯",
	"downlink"		=> "下載",
	"uplink"		=> "上一層",
	"homelink"		=> "主頁",
	"reloadlink"		=> "重新載入",
	"copylink"		=> "複製",
	"movelink"		=> "移動",
	"dellink"		=> "刪除",
	"comprlink"		=> "壓縮",
	"adminlink"		=> "管理員",
	"logoutlink"		=> "登出",
	"uploadlink"		=> "上傳",
	"searchlink"		=> "搜尋",
	'difflink'      => 'Diff',
	"extractlink"	=> "解開壓縮檔",
	'chmodlink'		=> '更改 (chmod) 權限 (資料夾/檔案)', // new mic
	'mossysinfolink'	=> 'eXtplorer 系統資訊 (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> '前往 eXtplorer 網站 (另開視窗)', // new mic
	
	// list
	"nameheader"		=> "名稱",
	"sizeheader"		=> "大小",
	"typeheader"		=> "類型",
	"modifheader"		=> "最後更新",
	"permheader"		=> "權限",
	"actionheader"		=> "動作",
	"pathheader"		=> "路徑",
	
	// buttons
	"btncancel"		=> "取消",
	"btnsave"		=> "儲存",
	"btnchange"		=> "更改",
	"btnreset"		=> "重設",
	"btnclose"		=> "關閉",
	"btnreopen"		=> "重開",
	"btncreate"		=> "新增",
	"btnsearch"		=> "搜尋",
	"btnupload"		=> "上傳",
	"btncopy"		=> "複製",
	"btnmove"		=> "移動",
	"btnlogin"		=> "登入",
	"btnlogout"		=> "登出",
	"btnadd"		=> "新增",
	"btnedit"		=> "編輯",
	"btnremove"		=> "移除",
  "btndiff"       => "Diff",
		
		// user messages, new in joomlaXplorer 1.3.0
	"renamelink"	=> "重新命名",
	"confirm_delete_file" => "您確定要刪除這個檔案? \\n%s",
	"success_delete_file" => "物件成功刪除.",
	"success_rename_file" => "此目錄/檔案 %s 已成功重新命名為 %s.",
	
// actions
	"actdir"		=> "目錄",
	"actperms"		=> "更改權限",
	"actedit"		=> "編輯檔案",
	"actsearchresults"	=> "搜尋結果",
	"actcopyitems"		=> "複製項目",
	"actcopyfrom"		=> "從 /%s 複製到 /%s ",
	"actmoveitems"		=> "移動項目",
	"actmovefrom"		=> "從 /%s 移動到 /%s ",
	"actlogin"		=> "登入",
	"actloginheader"	=> "登入以使用 QuiXplorer",
	"actadmin"		=> "管理選單",
	"actchpwd"		=> "更改密碼",
	"actusers"		=> "使用者",
	"actarchive"		=> "壓縮項目",
	"actupload"		=> "上傳檔案",
	
	// misc
	"miscitems"		=> "項目",
	"miscfree"		=> "Free",
	"miscusername"		=> "使用者名稱",
	"miscpassword"		=> "密碼",
	"miscoldpass"		=> "舊密碼",
	"miscnewpass"		=> "新密碼",
	"miscconfpass"		=> "確認密碼",
	"miscconfnewpass"	=> "確認新密碼",
	"miscchpass"		=> "更改密碼",
	"mischomedir"		=> "主頁目錄",
	"mischomeurl"		=> "主頁 URL",
	"miscshowhidden"	=> "顯示隱藏項目",
	"mischidepattern"	=> "隱藏樣式",
	"miscperms"		=> "權限",
	"miscuseritems"		=> "(名稱, 主頁目錄, 顯示隱藏項目, 權限, 啟用)",
	"miscadduser"		=> "增加使用者",
	"miscedituser"		=> "編輯使用者 '%s'",
	"miscactive"		=> "啟用",
	"misclang"		=> "語言",
	"miscnoresult"		=> "無結果可用.",
	"miscsubdirs"		=> "搜尋子目錄",
	"miscpermnames"		=> array("只能瀏覽","修改","更改密碼","修改及更改密碼","管理員"),
	"miscyesno"		=> array("是","否","Y","N"),
	"miscchmod"		=> array("擁有者", "群組", "公開"),
	'misccontent'    => "檔案內容",
	
	// from here all new by mic
	'miscowner'			=> '擁有者',
	'miscownerdesc'		=> '<strong>描述:</strong><br />使用者 (UID) /<br />群組 (GID)<br />目前權限:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "eXtplorer 系統資訊",
	'sisysteminfo'		=> '系統資訊',
	'sibuilton'			=> '運行系統',
	'sidbversion'		=> '資料庫版本 (MySQL)',
	'siphpversion'		=> 'PHP 版本',
	'siphpupdate'		=> '資訊: <span style="color: red;">您所使用的 PHP 版本還是 <strong>太低</strong>!</span><br />為了保證Mambo跟外掛所有的功能,<br />您至少需使用 <strong>PHP.版本 4.3</strong>!',
	'siwebserver'		=> '網頁伺服器',
	'siwebsphpif'		=> '網頁伺服器 - PHP 介面',
	'simamboversion'	=> 'eXtplorer 版本',
	'siuseragent'		=> '瀏覽器版本',
	'sirelevantsettings' => '重要的 PHP 設定',
	'sisafemode'		=> '安全模式',
	'sibasedir'			=> '打開主目錄',
	'sidisplayerrors'	=> 'PHP 錯誤',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> '檔案上傳',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML 已啟動',
	'sizlibenabled'		=> 'ZLIB 已啟動',
	'sidisabledfuncs'	=> '停用的功能',
	'sieditor'			=> 'WYSIWYG 編輯器',
	'siconfigfile'		=> 'Config file',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP 資訊',
	'sipermissions'		=> '權限',
	'sidirperms'		=> '目錄權限',
	'sidirpermsmess'	=> '為了確保 eXtplorer 所有函數以及功能運作正常, 以下的資料夾權限必須為可寫入 [chmod 0777]',
	'sionoff'			=> array( '打開', '關閉' ),
	
	'extract_warning' => "您確定要在此處解壓縮檔案?\\n如果不小心使用這將會覆蓋已經存在的檔案!",
	'extract_success' => "解壓縮成功",
	'extract_failure' => "解壓縮失敗",
	
	'overwrite_files' => '複蓋已存在的檔案?',
	"viewlink"		=> "檢視",
	"actview"		=> "顯示檔案來源",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> '遞迴至子目錄?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> '檢查最新版本',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'重新命名一個目錄或檔案...',
	'newname'		=>	'新名稱',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'儲存之後回到目錄?',
	'line'		=> 	'行',
	'column'	=>	'列',
	'wordwrap'	=>	'換行: (只限IE)',
	'copyfile'	=>	'複製檔案為此檔案名',
	
	// Bookmarks
	'quick_jump' => '快速跳轉至',
	'already_bookmarked' => '此目錄已加入書籤',
	'bookmark_was_added' => '此目錄已加入至書籤清單.',
	'not_a_bookmark' => '此目錄不是書籤.',
	'bookmark_was_removed' => '此目錄已從書籤清單移除.',
	'bookmarkfile_not_writable' => " %s 書籤失敗.\n 書籤檔案 '%s' \n無法寫入.",
	
	'lbl_add_bookmark' => '新增此目錄為書籤',
	'lbl_remove_bookmark' => '從書籤清單移除此目錄',
	
	'enter_alias_name' => '請輸入此書籤的別名',
	
	'normal_compression' => '正常壓縮',
	'good_compression' => '好的壓縮',
	'best_compression' => '最佳壓縮',
	'no_compression' => '無壓縮',
	
	'creating_archive' => '建立壓縮檔...',
	'processed_x_files' => '已處理 %s 的全部 %s 檔案',
	
	'ftp_header' => '本地 FTP 驗證',
	'ftp_login_lbl' => '請輸入FTP伺服器的登入憑證',
	'ftp_login_name' => 'FTP 使用者名稱',
	'ftp_login_pass' => 'FTP 密碼',
	'ftp_hostname_port' => 'FTP伺服器主電腦名稱及連接埠 <br />(連接埠是可選的)',
	'ftp_login_check' => 'FTP 連線檢查中...',
	'ftp_connection_failed' => "無法連線至 FTP伺服器. \n請檢查您的FTP伺服器是否在您的伺服器運作中.",
	'ftp_login_failed' => "FTP 登入失敗. 請檢查使用者名稱跟密碼並再試一次.",
		
	'switch_file_mode' => '目前模式: <strong>%s</strong>. 您可以切換至 %s 模式.',
	'symlink_target' => '符號連結目標',
	
	"permchange"		=> "CHMOD 成功:",
	"savefile"		=> "檔案已儲存.",
	"moveitem"		=> "移動已成功.",
	"copyitem"		=> "複製已成功.",
	'archive_name' 	=> '壓縮檔案名稱',
	'archive_saveToDir' 	=> '在此目錄儲存壓縮檔案',
	
	'editor_simple'	=> '簡易編輯器模式',
	'editor_syntaxhighlight'	=> '語法-標明顯示 模式',

	'newlink'	=> '新 檔案/目錄',
	'show_directories' => '顯示目錄',
	'actlogin_success' => '登入成功!',
	'actlogin_failure' => '登入失敗, 再試一次.',
	'directory_tree' => '目錄樹',
	'browsing_directory' => '瀏覽目錄',
	'filter_grid' => '篩選條件',
	'paging_page' => '頁',
	'paging_of_X' => '的 {0}',
	'paging_firstpage' => '最前頁',
	'paging_lastpage' => '最後頁',
	'paging_nextpage' => '下一頁',
	'paging_prevpage' => '上一頁',
	
	'paging_info' => '顯示項目 {0} - {1} 的 {2}',
	'paging_noitems' => '沒有項目可以顯示',
	'aboutlink' => '關於...',
	'password_warning_title' => '重要 - 更換您的密碼!',
	'password_warning_text' => '您所登入的帳號 (管理員跟密碼) 為預設的eXtplorer管理帳號. 您的eXtplorer安裝容易被侵入您需要馬上修復此安全漏洞!',
	'change_password_success' => '您的密碼已更換!',
	'success' => '成功',
	'failure' => '失敗',
	'dialog_title' => '網站對話方塊',
	'upload_processing' => '上傳進行中, 請稍候...',
	'upload_completed' => '上傳成功!',
	'acttransfer' => '從另一個伺服器轉移',
	'transfer_processing' => '伺服器-至-伺服器轉移進行中, 請稍候...',
	'transfer_completed' => '轉移完成!',
	'max_file_size' => '最大檔案大小',
	'max_post_size' => '最大上傳限制',
	'done' => '完成.',
	'permissions_processing' => '權限套用中, 請稍候...',
	'archive_created' => '壓縮檔已建立!',
	'save_processing' => '儲存檔案中...',
'current_user' => '腳本目前以以下使用者的權限運行:',
	'your_version' => '您的版本',
	'search_processing' => '搜尋中, 請稍候...',
	'url_to_file' => '檔案的網址',
	'file' => '檔案'
	
);
?>
