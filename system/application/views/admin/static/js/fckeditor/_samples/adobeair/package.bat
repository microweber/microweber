@ECHO OFF

::
:: FCKeditor - The text editor for Internet - http://www.fckeditor.net
:: Copyright (C) 2003-2008 Frederico Caldeira Knabben
::
:: == BEGIN LICENSE ==
::
:: Licensed under the terms of any of the following licenses at your
:: choice:
::
::  - GNU General Public License Version 2 or later (the "GPL")
::    http://www.gnu.org/licenses/gpl.html
::
::  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
::    http://www.gnu.org/licenses/lgpl.html
::
::  - Mozilla Public License Version 1.1 or later (the "MPL")
::    http://www.mozilla.org/MPL/MPL-1.1.html
::
:: == END LICENSE ==
::

:: adt -package SIGNING_OPTIONS air_file app_xml [file_or_dir | -C dir file_or_dir | -e file dir ...] ...

"C:\Adobe AIR SDK\bin\adt" -package -storetype pkcs12 -keystore sample01_cert.pfx -storepass 123abc FCKeditor.air application.xml -C ../../ .
