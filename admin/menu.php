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
//  @package menu.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: menu.php 68 2012-12-26 18:22:18Z alfred $

$module_name   = basename(dirname(__DIR__));
$moduleHandler = xoops_getHandler('module');
$xoopsModule   = XoopsModule::getByDirname($module_name);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$pathIcon32    = $moduleInfo->getInfo('icons32');

include_once dirname(__DIR__) . '/include/constants.php';
include_once dirname(__DIR__) . '/class/info.php';
$infowaitHandler = new InfoInfoHandler($GLOBALS['xoopsDB'], $module_name . '_bak');
$wait_site       = $infowaitHandler->getCount();

$adminmenu               = array();
$i                       = 0;
$adminmenu[$i]['title']  = _MI_INFO_INDEX;
$adminmenu[$i]['link']   = 'admin/index.php';
$adminmenu[$i++]['icon'] = '../../' . $pathIcon32 . '/home.png';

$adminmenu[$i]['title']  = _MI_INFO_ADMENU2;
$adminmenu[$i]['link']   = 'admin/admin_categorie.php';
$adminmenu[$i++]['icon'] = '../../' . $pathIcon32 . '/category.png';

$adminmenu[$i]['title']  = _MI_INFO_ADMENU3;
$adminmenu[$i]['link']   = 'admin/admin_seiten.php';
$adminmenu[$i++]['icon'] = '../../' . $pathIcon32 . '/view_detailed.png';

$adminmenu[$i]['title']  = '(' . $wait_site . ') ' . _MI_INFO_ADMENU5;
$adminmenu[$i]['link']   = 'admin/admin_seiten.php?op=approved';
$adminmenu[$i++]['icon'] = '../../' . $pathIcon32 . '/manage.png';

$adminmenu[$i]['title']  = _MI_INFO_ADMENU4;
$adminmenu[$i]['link']   = 'admin/admin_permission.php';
$adminmenu[$i++]['icon'] = '../../' . $pathIcon32 . '/permissions.png';

$adminmenu[$i]['title']  = _MI_INFO_ADMENU_ABOUT;
$adminmenu[$i]['link']   = 'admin/about.php';
$adminmenu[$i++]['icon'] = '../../' . $pathIcon32 . '/about.png';

unset($i);
