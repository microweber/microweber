----------------------------------------------------------------------------------------------------
eXtplorer 2.1.0 - README
----------------------------------------------------------------------------------------------------

Requirements:
-------------------
* PHP > 4.3

Supported Browsers:
-------------------
* Internet Explorer >= 6.0
* Firefox >= 2
* Safari >= 4
* Google Chrome/Iron >= 3
* Konqueror (KDE > 4.0)
* Opera 10.5+

Opera and Konqueror don't allow custom Context Menus. Use Double Click on a record in the grid instead.

Installation as Standalone Script
-------------------
	1. Unpack the Archive (where this README.txt is contained) to a local directory
	2. Login to your server via FTP and create a subdirectory for eXtplorer
	3. Upload all eXtplorer files from your computer into the subdirectory on the server
	4. Browse to the URL http://<YOURSERVER>/<SUBDIRECTORY> and login. Remember to immediately change the admin password.
	5. If you can't change your admin password, this is most likely because the user file is not writable. With your FTP program browse to
		the eXtplorer directory /config and make the file ".htusers.php" writable (CHMOD 666).
	
  Done.
  
Installation into Joomla!/Mambo:
-------------------
	1. Login to Joomla!'s Administration Backend /administrator
	2. Go to "Components", "Install/Uninstall".
	3. Browse to the file "com_eXtplorer_xx.tar.gz" on your computer and click on "Upload File & Install".
	
  Done.
  You can now access eXtplorer through "Components" => "eXtplorer"


License:
--------------------
The software is dual-licensed and subject to the 
	- Mozilla Public License Version 1.1 (the "License") 
	or 
	- to the terms of the GNU General Public License Version 2 or later (the "GPL"); 
	
You may not use this software except in compliance with one of these Licenses. 

You may obtain a copy of the Mozilla Public License at http://www.mozilla.org/MPL/.
You may obtain a copy of the GNU General Public License at http://www.gnu.org/copyleft/gpl.html.

Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
License for the specific language governing rights and limitations under the License.

Alternatively, the software may be used under the terms of the GNU General Public License Version 2 or later (the "GPL"), in
which case the provisions of the GPL are applicable instead of those above. If you wish to allow use of your version of this software only
under the terms of the GPL and not to allow others to use your version of this file under the MPL, indicate your decision by
deleting  the provisions above and replace  them with the notice and other provisions required by the GPL.  If you do not delete
the provisions above, a recipient may use your version of this software under either the MPL or the GPL.


Credits:
--------------------
eXtplorer is based on QuiXplorer 2.3.1 (available at http://quixplorer.sourceforge.net/).

eXtplorer makes use of the fabulous ExtJS Javascript Library by Jack Slocum, Ext JS, LCC (http://extjs.com/), and that does indeed explain
the name eXtplorer. 
Please note that ExtJS is released under the terms of the GNU General Public license version 3 (GPL v3). Because the MPL and
the GPL are not compliant to each other, you are not allowed to use the ExtJS library in eXtplorer if you decide to use and/or distribute eXtplorer
under the terms of the MPL.


----------------------------------------------------------------------------------------------------
Facts, you should know of:
* If you're running in trouble, because you don't have permissions to chmod() or
  write to files: That's a fact! Switch to FTP mode (or file mode if you're in ftp mode)
* eXtplorer is a multi-language Script.
  The Language is automatically picked from the browser (or from the global language in Joomla!/Mambo)
  
* eXtplorer comes with an User Management feature. As Joomla!/Mambo have their own framework, User Management isn't
	available in non-standalone mode.
* All users must login to access eXtplorer. You can allow visitors to view files by creating a "guest" account with "View" permissions.

* In Joomla!/Mambo the access to this  script is restricted to Super Administrators by default. If you want to change this,
  edit the file header of "admin.extplorer.php" and change the following:
  ****
		if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
			mosRedirect( 'index2.php', _NOT_AUTH );
		}
  ****
  to something different.
----------------------------------------------------------------------------------------------------

Troubleshooting:
	* Some browsers (e.g. Konqueror) may want to save a download as index.php.
	  To solve this, just supply the correct name when saving.
	* Internet Explorer may behave strangely when downloading files.
	  If you open the php-file download, the real download window should open.
	* Mozilla may add the extension 'php' to a file being downloaded.
	  Save as 'any file (*.*)' and remove the 'php' extension to get the proper name.
	  (NOTE: for php-files, this extension is correct)
	* If you are unable to perform certain operations,
	  try using an FTP-chmod to set the directories to 755 and the files to 644.
	* If you don't know the full name of a directory on your website,
	  you can use a php-script containing '<?php echo getcwd(); ?>' to get it.
	* The Search Function uses PCRE regex syntax to search; though wildcards like * and ?
	  should work (like with 'ls' on Linux), it may show unexpected behaviour.
	* User-management may logout unexpectedly or show other strange behaviour.
	  This is due to a bug in PHP 4.1.2; we would advise you to upgrade to a higher version.
----------------------------------------------------------------------------------------------------
Users:
	* You can easily magage users using the "admin" section of eXtplorer.
	* Standard, there is only one user: "admin", with password "admin";
	  you should change this password immediately.
----------------------------------------------------------------------------------------------------
Languages:
	* Users can select one of all available languages on login.
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
Developer: 	Soeren Eberhardt <soeren[aat]virtuemart.net>
original author:	the QuiX project
----------------------------------------------------------------------------------------------------
