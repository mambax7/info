<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//  @package constants.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: constants.php 72 2013-02-04 18:48:06Z alfred $

if (defined('_CON_INFO_PERMNAME')) return;

define('_CON_INFO_PERMNAME', 'InfoPerm');

define('_CON_INFO_CANCREATE', 1);
define('_CON_INFO_CANUPDATE', 2);
define('_CON_INFO_CANUPDATEALL', 3);

//erstellen
define('_CON_INFO_ALLCANUPDATE_CAT', 20);
define('_CON_INFO_ALLCANUPDATE_POSITION', 21);
define('_CON_INFO_ALLCANUPDATE_GROUPS', 22);
define('_CON_INFO_ALLCANUPDATE_SITEART', 23);
define('_CON_INFO_ALLCANUPDATE_SITEFULL', 24);
define('_CON_INFO_ALLCANUPDATE_HTML', 25);
define('_CON_INFO_ALLCANUPLOAD', 26);

//updaten
define('_CON_INFO_CANUPDATE_CAT', 50);
define('_CON_INFO_CANUPDATE_POSITION', 51);
define('_CON_INFO_CANUPDATE_GROUPS', 52);
define('_CON_INFO_CANUPDATE_SITEART', 53);
define('_CON_INFO_CANUPDATE_SITEFULL', 54);
define('_CON_INFO_CANUPDATE_DELETE', 55);
