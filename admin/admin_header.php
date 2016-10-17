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
//  @package admin_header.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: admin_header.php 76 2013-09-06 17:00:56Z alfred $

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
require_once XOOPS_ROOT_PATH . '/include/cp_header.php';

global $xoopsModule;
$moduleHandler = xoops_getHandler('module');
$moduleInfo  = $moduleHandler->get($xoopsModule->getVar('mid'));
$module_name = $xoopsModule->getVar('dirname');
include_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/include/function.php';

if (Info_checkXoopsVersion('2.6.0')) {
    // XOOPS ab 2.6.0
    $xoops = Xoops::getInstance();
    XoopsLoad::load('system', 'system');
    $indexAdmin = new XoopsModuleAdmin();
} else {
    if (!Info_checkModuleAdmin()) {
        redirect_header('../../../admin.php', 5, _AM_INFO_MODULEADMIN_MISSING, false);
    }
    $pathIcon16 = XOOPS_URL . '/' . $moduleInfo->getInfo('icons16');
    $pathIcon32 = XOOPS_URL . '/' . $moduleInfo->getInfo('icons32');
    $indexAdmin = new ModuleAdmin();
}

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/class/infotree.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/class/info.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/class/category.php';

$infoHandler     = new InfoInfoHandler($xoopsDB, $module_name);
$infowaitHandler = new InfoInfoHandler($xoopsDB, $module_name . '_bak');
$catHandler      = new InfoCategoryHandler($xoopsDB, $module_name);
$infoTree       = new InfoTree($xoopsDB->prefix($module_name), 'info_id', 'parent_id');

$myts = MyTextSanitizer::getInstance();
