<?php
/**
 * KFM - Kae's File Manager
 *
 * configuration example file
 *
 * do not delete this file. copy it to configuration.php, remove the lines
 *   you don't want to change, and edit the rest to your own needs.
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */

// what type of database to use
// values allowed: mysql, pgsql, sqlitepdo
$kfm_db_type = 'sqlitepdo';

// the following options should only be filled if you are not using sqlitepdo as the database
$kfm_db_prefix   = 'kfm_';
$kfm_db_host     = 'localhost';
$kfm_db_name     = 'kfm';
$kfm_db_username = 'username';
$kfm_db_password = 'password';
$kfm_db_port     = '';

/**
 * This setting specifies if you want to use the KFM security. If set to false, no login form will be displayd
 * Note that the user_root_folder setting will not work when the user is the main user
 *
 * Please change this to 'true' if you want to use usernames and passwords.
 */
$use_kfm_security=false;

/**
 * where on the server should the uploaded files be kept?
 * if the first two characters of this setting are './', then the files are relative to the directory that KFM is in.
 * Here are some examples:
 *    $kfm_userfiles_address = '/home/kae/userfiles'; # absolute address in Linux
 *    $kfm_userfiles_address = 'D:/Files';            # absolute address in Windows
 *    $kfm_userfiles_address = './uploads';           # relative address
 */
$kfm_userfiles_address = '/home/kae/Desktop/userfiles';

// where should a browser look to find the files?
// This setting assumes that the files are available throught a public address.
// This is not secure. To securely store files, put them outside the public hierarchy, make sure that the setting
// $kfm_userfiles_address is correct and set kfm_url to secure in the admin panel or put in this place the correct
// values for the secure settings if you are not using the admin panel:
// $kfm->setting('kfm_url', '/kfm/'); // Web address of KFM
// $kfm->setting('file_url', 'secure');
// Examples for public accessable files:
//   $kfm_userfiles_output = 'http://thisdomain.com/files/';
//   $kfm_userfiles_output = '/files/';
$kfm_userfiles_output = '/userfiles/';

// directory in which KFM keeps its database and generated files
// if this starts with '/', then the address is absolute. otherwise, it is relative to $kfm_userfiles_address.
// $kfm_workdirectory = '.files';
// $kfm_workdirectory = '/home/kae/files_cache';
// warning: if you use the '/' method, then you must use the get.php method for $kfm_userfiles_output.
$kfm_workdirectory = '.files';

// where is the 'convert' program kept, if you have it installed?
$kfm_imagemagick_path = '/usr/bin/convert';

// use server's version of Pear?
$kfm_use_servers_pear = false;

// we would like to keep track of installations, to see how many there are, and what versions are in use.
// if you do not want us to have this information, then set the following variable to '1'.
$kfm_dont_send_metrics = 0;

// hours to offset server time by.
// for example, if the server is in GMT, and you are in Northern Territory, Australia, then the value to use is 9.5
$kfm_server_hours_offset = 1;

// thumb format. use .png if you need transparencies. .jpg for lower file size
$kfm_thumb_format='.jpg';

// what plugin should handle double-clicks by default
$kfm_default_file_selection_handler='return_url';

/**
 * Ignore DB Session - leave this as "false", unless you are a developer and accessing KFM through an API.
 * Developers: this is for cases where KFM files are included and you just need to use its functions without going through the whole setup.
 */
if(!isset($kfm_do_not_save_session))$kfm_do_not_save_session=false;

/**
 * This function is called in the admin area. To specify your own admin requirements or security, un-comment and edit this function
 */
//	function kfm_admin_check(){
//		return false; // Disable the admin area
//	}
