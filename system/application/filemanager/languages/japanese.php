<?php
// $Id: japanese.php 96 2008-02-03 18:13:22Z soeren $
// Japanese Language Module for v2.3 (translated by www.joomlaway.net)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "エラー",
	"message"			=> "メッセージ",
	"back"			=> "戻る",

	// root
	"home"			=> "ホームディレクトリが存在しません。設定を確認して下さい。",
	"abovehome"		=> "The current directory may not be above the home directory.",
	"targetabovehome"	=> "The target directory may not be above the home directory.",

	// exist
	"direxist"		=> "このディレクトリは存在しません。",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"		=> "このファイルは存在しません。",
	"itemdoesexist"		=> "このアイテムは既に存在します。",
	"itemexist"		=> "このアイテムは存在しません。",
	"targetexist"		=> "ターゲットディレクトリは存在しません。",
	"targetdoesexist"	=> "ターゲットアイテムは既に存在します。",

	// open
	"opendir"		=> "ディレクトリを開けません。",
	"readdir"		=> "ディレクトリを読めません。",

	// access
	"accessdir"		=> "あなたはこのディレクトリへのアクセスを許可されていません。",
	"accessfile"		=> "あなたはこのファイルへのアクセスを許可されていません。",
	"accessitem"		=> "あなたはこのアイテムへのアクセスを許可されていません。",
	"accessfunc"		=> "あなたはこの機能の使用を許可されていません。",
	"accesstarget"		=> "あなたはターゲットディレクトリへのアクセスを許可されていません。",

	// actions
	"permread"		=> "パーミッションの取得に失敗しました。",
	"permchange"		=> "CHMOD 失敗 (ほとんどの場合、これはファイルの所有者問題が原因です。（例えばHTTPユーザ('wwwrun'または 'nobody')とFTPユーザが同じでない場合）",
	"openfile"		=> "ファイルを開くことに失敗しました。",
	"savefile"		=> "ファイルの保存に失敗しました。",
	"createfile"		=> "ファイルの作成に失敗しました。",
	"createdir"		=> "ディレクトリの作成に失敗しました。",
	"uploadfile"		=> "ファイルのアップロードに失敗しました。",
	"copyitem"		=> "コピーに失敗しました。",
	"moveitem"		=> "移動に失敗しました。",
	"delitem"		=> "削除に失敗しました。",
	"chpass"		=> "パスワードの変更に失敗しました。",
	"deluser"		=> "ユーザの削除に失敗しました。",
	"adduser"		=> "ユーザの追加に失敗しました。",
	"saveuser"		=> "ユーザの保存に失敗しました。",
	"searchnothing"		=> "検索する文字列を入力する必要があります。",

	// misc
	"miscnofunc"		=> "機能は利用できません。",
	"miscfilesize"		=> "ファイルは最大サイズを超えます。",
	"miscfilepart"		=> "ファイルは一部分のみアップロードされました。",
	"miscnoname"		=> "名前を入力する必要があります。",
	"miscselitems"		=> "アイテムを選択していません。",
	"miscdelitems"		=> "これら {0} アイテムを削除してよろしいですか？",
	"miscdeluser"		=> "ユーザ {0} を削除してよろしいですか？",
	"miscnopassdiff"	=> "新しいパスワードは現在のパスワードと同じです。",
	"miscnopassmatch"	=> "パスワードが一致しません。",
	"miscfieldmissed"	=> "重要なフィールドが入力されていません。",
	"miscnouserpass"	=> "ユーザ名またはパスワードが適切ではありません。",
	"miscselfremove"	=> "自分自身を削除できません。",
	"miscuserexist"		=> "ユーザは既に存在しています。",
	"miscnofinduser"	=> "ユーザが見つかりません。",
	"extract_noarchive" => "ファイルは解凍可能なアーカイブではありません。",
	"extract_unknowntype" => "不明なアーカイブタイプ",
	
	'chmod_none_not_allowed' => '<none> へのパーミション変更は許可されていません。',
	'archive_dir_notexists' => '指定した保存ディレクトリは存在しません。',
	'archive_dir_unwritable' => 'アーカイブを保存する書き込み可能なディレクトリを指定して下さい。',
	'archive_creation_failed' => 'アーカイブファイルの保存に失敗しました。'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "パーミッション変更",
	"editlink"		=> "編集",
	"downlink"		=> "ダウンロード",
	"uplink"		=> "上へ",
	"homelink"		=> "ホーム",
	"reloadlink"		=> "再読込み",
	"copylink"		=> "コピー",
	"movelink"		=> "移動",
	"dellink"		=> "削除",
	"comprlink"		=> "圧縮",
	"adminlink"		=> "管理",
	"logoutlink"		=> "ログアウト",
	"uploadlink"		=> "アップロード",
	"searchlink"		=> "検索",
	"extractlink"	=> "解凍",
	'chmodlink'		=> '権限変更 (chmod) ', // new mic
	'mossysinfolink'	=> 'eXtplorerシステム情報 (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'joomlaXplorer Webサイトへ行く(新しいウインドウ)', // new mic

	// list
	"nameheader"		=> "名前",
	"sizeheader"		=> "サイズ",
	"typeheader"		=> "種類",
	"modifheader"		=> "修正日時",
	"permheader"		=> "パーミッション",
	"actionheader"		=> "操作",
	"pathheader"		=> "パス",

	// buttons
	"btncancel"		=> "キャンセル",
	"btnsave"		=> "保存",
	"btnchange"		=> "変更",
	"btnreset"		=> "リセット",
	"btnclose"		=> "閉じる",
	"btncreate"		=> "作成",
	"btnsearch"		=> "検索",
	"btnupload"		=> "アップロード",
	"btncopy"		=> "コピー",
	"btnmove"		=> "移動",
	"btnlogin"		=> "ログイン",
	"btnlogout"		=> "ログアウト",
	"btnadd"		=> "追加",
	"btnedit"		=> "編集",
	"btnremove"		=> "削除",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> '名前の変更',
	'confirm_delete_file' => 'このファイルを削除してもよいですか？ <br />%s',
	'success_delete_file' => 'アイテムを正常に削除しました。',
	'success_rename_file' => 'ディレクトリ/ファイル %s は正常に %s へ名前を変更しました。',
	
	// actions
	"actdir"		=> "ディレクトリ",
	"actperms"		=> "パーミッション変更",
	"actedit"		=> "ファイル編集",
	"actsearchresults"	=> "検索結果",
	"actcopyitems"		=> "アイテムコピー",
	"actcopyfrom"		=> "/%s から /%s へコピー ",
	"actmoveitems"		=> "アイテム移動",
	"actmovefrom"		=> "/%s から /%s へ移動 ",
	"actlogin"		=> "ログイン",
	"actloginheader"	=> "eXtplorerを使用するためにログイン",
	"actadmin"		=> "管理",
	"actchpwd"		=> "パスワード変更",
	"actusers"		=> "ユーザ",
	"actarchive"		=> "アイテム圧縮",
	"actupload"		=> "ファイルアップロード",

	// misc
	"miscitems"		=> "アイテム",
	"miscfree"		=> "空き",
	"miscusername"		=> "ユーザ名",
	"miscpassword"		=> "パスワード",
	"miscoldpass"		=> "旧パスワード",
	"miscnewpass"		=> "新パスワード",
	"miscconfpass"		=> "パスワード確認",
	"miscconfnewpass"	=> "新パスワード確認",
	"miscchpass"		=> "パスワード変更",
	"mischomedir"		=> "ホームディレクトリ",
	"mischomeurl"		=> "ホームURL",
	"miscshowhidden"	=> "隠しアイテムを表示",
	"mischidepattern"	=> "隠しパターン",
	"miscperms"		=> "パーミッション",
	"miscuseritems"		=> "(名前,ホームディレクトリ,隠しアイテム表示,パーミッション,有効)",
	"miscadduser"		=> "ユーザ追加",
	"miscedituser"		=> "ユーザ編集 '%s'",
	"miscactive"		=> "有効",
	"misclang"		=> "言語",
	"miscnoresult"		=> "利用可能な結果はありません。",
	"miscsubdirs"		=> "サブディレクトリを検索",
	"miscpermnames"		=> array("閲覧のみ","変更","パスワード変更","変更およびパスワード変更",
					"管理者"),
	"miscyesno"		=> array("はい","いいえ","Y","N"),
	"miscchmod"		=> array("所有者", "グループ", "公開"),

	// from here all new by mic
	'miscowner'			=> '所有者',
	'miscownerdesc'		=> '<strong>説明:</strong><br />ユーザ (UID) /<br />グループ (GID)<br />現在の権限:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "eXtplorerシステム情報",
	'sisysteminfo'		=> 'システム情報',
	'sibuilton'			=> 'オペレーティングシステム',
	'sidbversion'		=> 'データベースバージョン (MySQL)',
	'siphpversion'		=> 'PHPバージョン',
	'siphpupdate'		=> 'インフォメーション: <span style="color: red;">お使いのPHPのバージョンが<strong>古い</strong>です！</span><br />Joomla!およびアドオンの全ての機能/特徴を保証するには、<br />最低でも<strong>PHPバージョン4.3</strong>を使用するべきです！',
	'siwebserver'		=> 'Webサーバ',
	'siwebsphpif'		=> 'Webサーバ - PHPインタフェース',
	'simamboversion'	=> 'eXtplorerバージョン',
	'siuseragent'		=> 'ブラウザバージョン',
	'sirelevantsettings' => '重要なPHP設定',
	'sisafemode'		=> 'セーフモード',
	'sibasedir'			=> 'ベースディレクトリを開く',
	'sidisplayerrors'	=> 'PHPエラー',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'File Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML enabled',
	'sizlibenabled'		=> 'ZLIB enabled',
	'sidisabledfuncs'	=> 'Disabled functions',
	'sieditor'			=> 'WYSIWYGエディタ',
	'siconfigfile'		=> '設定ファイル',
	'siphpinfo'			=> 'PHP情報',
	'siphpinformation'	=> 'PHP情報',
	'sipermissions'		=> 'パーミション',
	'sidirperms'		=> 'ディレクトリパーミッション',
	'sidirpermsmess'	=> 'eXtplorerの全ての機能および特徴が正しく動作させるには、次のフォルダが書込みパーミッション[chmod 0777]を持つ必要があります。',
	'sionoff'			=> array( 'オン', 'オフ' ),
	
	'extract_warning' => "このファイルを本当にここへ解凍しますか？<br />注意して使用しないと既存のファイルを上書きします！",
	'extract_success' => "解凍は成功しました。",
	'extract_failure' => "解凍は失敗しました。",
	
	'overwrite_files' => '既存のファイルを上書きしますか？',
	"viewlink"		=> "参照",
	"actview"		=> "ファイルのソースを表示",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'サブディレクトリ内を再帰しますか？',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> '最新バージョンを確認',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'ディレクトリまたはファイル名を変更...',
	'newname'		=>	'新しい名前',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'保存後ディレクトリに戻りますか？',
	'line'		=> 	'行',
	'column'	=>	'列',
	'wordwrap'	=>	'ワードラップ (IEのみ)',
	'copyfile'	=>	'以下の名前でファイルをコピー',
	
	// Bookmarks
	'quick_jump' => 'クイックジャンプ',
	'already_bookmarked' => 'このディレクトリは既にブックマークしました。',
	'bookmark_was_added' => 'このディレクトリはブックマークに追加されました。',
	'not_a_bookmark' => 'このディレクトリはブックマークではありません。',
	'bookmark_was_removed' => 'このディレクトリはブックマークから削除されました。',
	'bookmarkfile_not_writable' => "%s ブックマークに失敗しました。\n ブックマークファイル '%s' \nは書込みできません。",
	
	'lbl_add_bookmark' => 'ブックマークとしてこのディレクトリを追加しますか',
	'lbl_remove_bookmark' => 'ブックマークからこのディレクトリを削除しますか',
	
	'enter_alias_name' => 'このブックマークの別名を入力して下さい',
	
	'normal_compression' => '通常圧縮',
	'good_compression' => '高圧縮',
	'best_compression' => '最高圧縮',
	'no_compression' => '圧縮なし',
	
	'creating_archive' => '圧縮ファイルを作成中...',
	'processed_x_files' => '%s 個のファイル(%s 個中）を処理しました。',
	
	'ftp_header' => 'ローカルFTP認証',
	'ftp_login_lbl' => 'FTPサーバへのログイン証明を入力してください。',
	'ftp_login_name' => 'FTPユーザ名',
	'ftp_login_pass' => 'FTPパスワード',
	'ftp_hostname_port' => 'FTPサーバホスト名およびポート <br />(ポートは任意です)',
	'ftp_login_check' => 'FTP接続を確認中...',
	'ftp_connection_failed' => "FTPサーバとコンタクトできませんでした。 \nあなたのサーバでFTPサーバが実行中か確認して下さい。",
	'ftp_login_failed' => "FTPログインに失敗しました。ユーザ名とパスワードを確認し、再度試して下さい。",
		
	'switch_file_mode' => '現在のモード: <strong>%s</strong>です。%s モードへ切り替え可能です。',
	'symlink_target' => 'シンボリックリンクのターゲット',
	
	"permchange"		=> "CHMOD成功:",
	"savefile"		=> "ファイルは保存されました。",
	"moveitem"		=> "移動は成功しました。",
	"copyitem"		=> "コピーは成功しました。",
	'archive_name' 	=> '圧縮ファイル名',
	'archive_saveToDir' 	=> 'このディレクトリに圧縮ファイルを保存',
	
	'editor_simple'	=> 'シンプルエディタモード',
	'editor_syntaxhighlight'	=> 'シンタックスハイライトモード',

	'newlink'	=> '新規ファイル/ディレクトリ',
	'show_directories' => 'ディレクトリを表示',
	'actlogin_success' => 'ログインは成功しました！',
	'actlogin_failure' => 'ログインは失敗しました。再度試して下さい。',
	'directory_tree' => 'ディレクトリツリー',
	'browsing_directory' => 'ディレクトリを参照',
	'filter_grid' => 'フィルタ',
	'paging_page' => 'ページ',
	'paging_of_X' => 'of {0}',
	'paging_firstpage' => '先頭ページ',
	'paging_lastpage' => '最終ページ',
	'paging_nextpage' => '次ページ',
	'paging_prevpage' => '前ページ',
	
	'paging_info' => '表示中のアイテム {0} - {1} of {2}',
	'paging_noitems' => '表示するアイテムはありません',
	'aboutlink' => 'About...',
	'password_warning_title' => '重要 - パスワードを変更して下さい！',
	'password_warning_text' => 'ログインしたあなたのユーザアカウント（ユーザ名、パスワード共にadmin）は、デフォルトのeXtplorer特権アカウントと一致します。あなたのeXtplorerインストールは侵入を許します。すぐにこのセキュリティホールを修正する必要があります！',
	'change_password_success' => 'あなたのパスワードは変更されました！',
	'success' => '成功',
	'failure' => '失敗',
	'dialog_title' => 'Webサイトダイアログ',
	'upload_processing' => 'アップロード処理中。お待ち下さい...',
	'upload_completed' => 'アップロードは成功しました！',
	'acttransfer' => '他サーバから転送',
	'transfer_processing' => 'サーバ間転送を処理中。お待ち下さい...',
	'transfer_completed' => '転送が完了しました！',
	'max_file_size' => '最大ファイルサイズ',
	'max_post_size' => '最大アップロード制限',
	'done' => 'Done.',
	'permissions_processing' => 'パーミッション適用中。お待ち下さい...',
	'archive_created' => '圧縮ファイルが作成されました！',
	'save_processing' => 'ファイルを保存中...',
	'current_user' => 'このスクリプトは、現在次のユーザ権限で実行しています:',
	'your_version' => 'バージョン',
	'search_processing' => '検索中。お待ち下さい',
	'url_to_file' => 'ファイルのURL',
	'file' => 'ファイル'
	
);
?>
