<?php
// Simplified chinese Language Module for joomlaXplorer version 1.5 

// translated by Baijianpeng http://www.tcmbook.net
global $_VERSION;

$GLOBALS["charset"] = "utf-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "错误",
	"back"			=> "回上页",
	
	// root
	"home"			=> "未找到Joomla根目录，请检查您的设置.",
	"abovehome"		=> "当前目录可能并非Joomla根目录的上级目录.",
	"targetabovehome"	=> "目标目录可能并非Joomla根目录的上级目录.",
	
	// exist
	"direxist"		=> "该目录不存在.",
	//"filedoesexist"	=> "这目录已存在.",
	"fileexist"		=> "该文件不存在.",
	"itemdoesexist"		=> "该项目已存在.",
	"itemexist"		=> "该项目不存在.",
	"targetexist"		=> "该目标目录不存在.",
	"targetdoesexist"	=> "该目标项目已存在.",
	
	// open
	"opendir"		=> "无法打开目录.",
	"readdir"		=> "无法读取目录.",
	
	// access
	"accessdir"		=> "您不允许存取这个目录.",
	"accessfile"		=> "您不允许存取这个文件.",
	"accessitem"		=> "您不允许存取这个项目.",
	"accessfunc"		=> "您不允许使用这个功能.",
	"accesstarget"		=> "您不允许存取这个目标目录.",
	
	// actions
	"permread"		=> "取得权限失败.",
	"permchange"		=> "权限更改失败.",
	"openfile"		=> "打开文件失败.",
	"savefile"		=> "文件储存失败.",
	"createfile"		=> "新增文件失败.",
	"createdir"		=> "新增目录失败.",
	"uploadfile"		=> "文件上传失败.",
	"copyitem"		=> "复制失败.",
	"moveitem"		=> "移动失败.",
	"delitem"		=> "删除失败.",
	"chpass"		=> "更改密码失败.",
	"deluser"		=> "移除使用者失败.",
	"adduser"		=> "加入使用者失败.",
	"saveuser"		=> "储存使用者失败.",
	"searchnothing"		=> "您必须输入些什麽来搜寻.",
	
	// misc
	"miscnofunc"		=> "功能无效.",
	"miscfilesize"		=> "文件大小已达到最大.",
	"miscfilepart"		=> "文件只有部分已上传.",
	"miscnoname"		=> "您必须输入名称.",
	"miscselitems"		=> "您还未选择任何项目.",
	"miscdelitems"		=> "您确定要删除这些 {0} 项目?",
	"miscdeluser"		=> "您确定要删除使用者 '{0}'?",
	"miscnopassdiff"	=> "新密码跟旧密码相同.",
	"miscnopassmatch"	=> "密码不符.",
	"miscfieldmissed"	=> "您遗漏一个重要栏位.",
	"miscnouserpass"	=> "使用者名称或密码错误.",
	"miscselfremove"	=> "您无法移除您自己.",
	"miscuserexist"		=> "使用者已存在.",
	"miscnofinduser"	=> "无法找到使用者.",
	"extract_noarchive" => "该文件不可解压缩.",
	"extract_unknowntype" => "未知文件类型",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "更改权限",
	"editlink"		=> "编辑",
	"downlink"		=> "下载",
	"uplink"		=> "上一层",
	"homelink"		=> "网站根目录",
	"reloadlink"		=> "刷新",
	"copylink"		=> "复制",
	"movelink"		=> "移动",
	"dellink"		=> "删除",
	"comprlink"		=> "压缩",
	"adminlink"		=> "管理员",
	"logoutlink"		=> "登出",
	"uploadlink"		=> "上传",
	"searchlink"		=> "搜索",
	"extractlink"	=> "解压缩",
	'chmodlink'		=> '改变 (chmod) 权限 (目录/文件)', // new mic
	'mossysinfolink'	=> 'eXtplorer 系统信息 (eXtplorer, 服务器, PHP, mySQL数据库)', // new mic
	'logolink'		=> '访问 joomlaXplorer 官方网站 (在新窗口打开)', // new mic
	
	// list
	"nameheader"		=> "名称",
	"sizeheader"		=> "大小",
	"typeheader"		=> "类型",
	"modifheader"		=> "最后更新",
	"permheader"		=> "权限",
	"actionheader"		=> "动作",
	"pathheader"		=> "路径",
	
	// buttons
	"btncancel"		=> "取消",
	"btnsave"		=> "储存",
	"btnchange"		=> "更改",
	"btnreset"		=> "重设",
	"btnclose"		=> "关闭",
	"btncreate"		=> "新增",
	"btnsearch"		=> "搜寻",
	"btnupload"		=> "上传",
	"btncopy"		=> "复制",
	"btnmove"		=> "移动",
	"btnlogin"		=> "登入",
	"btnlogout"		=> "登出",
	"btnadd"		=> "增加",
	"btnedit"		=> "编辑",
	"btnremove"		=> "移除",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> '重命名',
	'confirm_delete_file' => '你确定要删除这些文件吗？ \\n%s',
	'success_delete_file' => '项目删除成功！',
	'success_rename_file' => '文件夹/文件 %s 已被成功重新命名为 %s.',
	
	
	// actions
	"actdir"		=> "目录",
	"actperms"		=> "更改权限",
	"actedit"		=> "编辑文件",
	"actsearchresults"	=> "搜寻结果",
	"actcopyitems"		=> "复制项目",
	"actcopyfrom"		=> "从 /%s 复制到 /%s ",
	"actmoveitems"		=> "移动项目",
	"actmovefrom"		=> "从 /%s 移动到 /%s ",
	"actlogin"		=> "登入",
	"actloginheader"	=> "登入以使用 QuiXplorer",
	"actadmin"		=> "管理选单",
	"actchpwd"		=> "更改密码",
	"actusers"		=> "使用者",
	"actarchive"		=> "压缩项目",
	"actupload"		=> "上传文件",
	
	// misc
	"miscitems"		=> "个项目",
	"miscfree"		=> "服务器可用磁盘空间",
	"miscusername"		=> "使用者名称",
	"miscpassword"		=> "密码",
	"miscoldpass"		=> "旧密码",
	"miscnewpass"		=> "新密码",
	"miscconfpass"		=> "确认密码",
	"miscconfnewpass"	=> "确认新密码",
	"miscchpass"		=> "更改密码",
	"mischomedir"		=> "主页目录",
	"mischomeurl"		=> "主页 URL",
	"miscshowhidden"	=> "显示隐藏项目",
	"mischidepattern"	=> "隐藏样式",
	"miscperms"		=> "权限",
	"miscuseritems"		=> "(名称, 主页目录, 显示隐藏项目, 权限, 启用)",
	"miscadduser"		=> "增加使用者",
	"miscedituser"		=> "编辑使用者 '%s'",
	"miscactive"		=> "启用",
	"misclang"		=> "语言",
	"miscnoresult"		=> "无结果可用.",
	"miscsubdirs"		=> "搜寻子目录",
	"miscpermnames"		=> array("只能浏览","修改","更改密码","修改及更改密码",
					"管理员"),
	"miscyesno"		=> array("是","否","Y","N"),
	"miscchmod"		=> array("所有人", "群组", "公开的"),
	// from here all new by mic
	'miscowner'			=> '所有者',
	'miscownerdesc'		=> '<strong>描述格式:</strong><br />用户 (UID) /<br />工作组 (GID)<br />当前权限:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'eXtplorer 系统信息',
	'sisysteminfo'		=> '系统信息',
	'sibuilton'			=> '操作系统',
	'sidbversion'		=> '数据库版本 (MySQL)',
	'siphpversion'		=> 'PHP 版本',
	'siphpupdate'		=> '信息: <span style="color: red;">您正在使用的PHP版本是 <strong>not</strong> actual!</span><br />为保证 eXtplorer 及其插件的所有功能正常运行,<br />您必须最低使用 <strong>PHP. 4.3 版本</strong>!',
	'siwebserver'		=> '服务器',
	'siwebsphpif'		=> '服务器 - PHP 界面',
	'simamboversion'	=> 'eXtplorer 版本',
	'siuseragent'		=> '浏览器版本',
	'sirelevantsettings' => '重要 PHP 设置',
	'sisafemode'		=> '安全模式',
	'sibasedir'			=> '打开基础目录',
	'sidisplayerrors'	=> 'PHP 错误信息',
	'sishortopentags'	=> '短的开放标签',
	'sifileuploads'		=> '文件上传',
	'simagicquotes'		=> '魔力引用',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> '输出缓存',
	'sisesssavepath'	=> '保存路径进程',
	'sisessautostart'	=> '线程自动启动',
	'sixmlenabled'		=> 'XML 已激活',
	'sizlibenabled'		=> 'ZLIB 已激活',
	'sidisabledfuncs'	=> '没有启用功能',
	'sieditor'			=> '所见即所得编辑器',
	'siconfigfile'		=> '配置文件',
	'siphpinfo'			=> 'PHP 信息',
	'siphpinformation'	=> 'PHP 信息',
	'sipermissions'		=> '允许',
	'sidirperms'		=> '文件夹允许',
	'sidirpermsmess'	=> '为了保证 eXtplorer的所有功能都正常运行，下列目录必须被允许写入 [chmod 0777]，即您必须看到它们的状态在下面显示是绿色的 “<font color=green> Writeable </font>” ',
	'sionoff'			=> array( '开', '关' ),
	
	'extract_warning' => "您确定要解压缩该文件吗？在当前目录？\\n若使用不当，本操作将要覆盖现有同名文件!",
	'extract_success' => "解压缩成功完成！",
	'extract_failure' => "解压缩失败",
	
	'overwrite_files' => '覆盖现有同名文件？',
	"viewlink"		=> "查看",
	"actview"		=> "显示文件来源",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> '包括子目录？',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> '检查最新版本',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'重命名目录或文件...',
	'newname'		=>	'新名称',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'保存后返回目录?',
	'line'		=> 	'行',
	'column'	=>	'列',
	'wordwrap'	=>	'文本换行: (仅限 IE)',
	'copyfile'	=>	'另存文件为这个名称',
	
	// Bookmarks
	'quick_jump' => '快速跳转到',
	'already_bookmarked' => '此目录已被标为书签',
	'bookmark_was_added' => '此目录已被加入到书签列表',
	'not_a_bookmark' => '此目录尚未被标为书签',
	'bookmark_was_removed' => '此目录已被从书签列表删除',
	'bookmarkfile_not_writable' => " %s 此书签失败。\n 书签文件 '%s' \n不可写。",
	
	'lbl_add_bookmark' => '将此目录加入书签。',
	'lbl_remove_bookmark' => '从书签列表删除此目录。',
	
	'enter_alias_name' => '请为本书签输入别名',
	
	'normal_compression' => '正常压缩',
	'good_compression' => '较好压缩',
	'best_compression' => '最大压缩',
	'no_compression' => '不压缩',
	
	'creating_archive' => '创建存档文件...',
	'processed_x_files' => '已处理 %s 个文件，共有 %s 个文件',
	
	'ftp_header' => '本地 FTP 验证',
	'ftp_login_lbl' => '请为 FTP 服务器输入登录信息',
	'ftp_login_name' => 'FTP 用户名',
	'ftp_login_pass' => 'FTP 密码',
	'ftp_hostname_port' => 'FTP 服务器主机名和端口号<br />(端口可以不填)',
	'ftp_login_check' => '检查 FTP 连接...',
	'ftp_connection_failed' => "FTP 服务器无法连接。 \n请检查你的主机上是否运行了 FTP 服务器。",
	'ftp_login_failed' => "FTP 登录失败。请检查用户名及密码是否正确，然后再试一次。",
		
	'switch_file_mode' => '当前模式: <strong>%s</strong>. 你可以切换到 %s 模式.',
	'symlink_target' => '象征性链接的目标',
	
	"permchange"		=> "CHMOD 成功:",
	"savefile"		=> "文件已被保存.",
	"moveitem"		=> "移动成功.",
	"copyitem"		=> "复制成功.",
	'archive_name' 	=> '存档文件的名称',
	'archive_saveToDir' 	=> '在本目录中保存存档文件',
	
	'editor_simple'	=> '简化编辑器模式',
	'editor_syntaxhighlight'	=> '语法高亮提示模式',

	'newlink'	=> 'New File/Directory',
	'show_directories' => 'Show Directories',
	'actlogin_success' => 'Login successful!',
	'actlogin_failure' => 'Login failed, try again.',
	'directory_tree' => 'Directory Tree',
	'browsing_directory' => 'Browsing Directory',
	'filter_grid' => 'Filter',
	'paging_page' => 'Page',
	'paging_of_X' => 'of {0}',
	'paging_firstpage' => 'First Page',
	'paging_lastpage' => 'Last Page',
	'paging_nextpage' => 'Next Page',
	'paging_prevpage' => 'Previous Page',
	
	'paging_info' => 'Displaying Items {0} - {1} of {2}',
	'paging_noitems' => 'No items to display',
	'aboutlink' => 'About...',
	'password_warning_title' => 'Important - Change your Password!',
	'password_warning_text' => 'The user account you are logged in with (admin with password admin) corresponds to the default eXtplorer priviliged account. Your eXtplorer installation is open to intrusion and you should immediately fix this security hole!',
	'change_password_success' => 'Your Password has been changed!',
	'success' => 'Success',
	'failure' => 'Failure',
	'dialog_title' => 'Website Dialog',
	'upload_processing' => 'Processing Upload, please wait...',
	'upload_completed' => 'Upload successful!',
	'acttransfer' => 'Transfer from another Server',
	'transfer_processing' => 'Processing Server-to-Server Transfer, please wait...',
	'transfer_completed' => 'Transfer completed!',
	'max_file_size' => 'Maximum File Size',
	'max_post_size' => 'Maximum Upload Limit',
	'done' => 'Done.',
	'permissions_processing' => 'Applying Permissions, please wait...',
	'archive_created' => 'The Archive File has been created!',
	'save_processing' => 'Saving File...',
	'current_user' => 'This script currently runs with the permissions of the following user:',
	'your_version' => 'Your Version',
	'search_processing' => 'Searching, please wait...',
	'url_to_file' => 'URL of the File',
	'file' => 'File'
);
?>
