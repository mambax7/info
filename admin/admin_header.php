<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      info module
 * @since
 * @author       XOOPS Development Team
 * @author       Dirk Herrmann <alfred@simple-xoops.de>
 */

use XoopsModules\Info;

require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

//global $xoopsModule;
//$moduleHandler = xoops_getHandler('module');
//$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
//$module_name   = $xoopsModule->getVar('dirname');
require_once  dirname(__DIR__) . '/include/function.php';
require_once  dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));
/** @var Info\Helper $helper */
$helper = Info\Helper::getInstance();
$adminObject = \Xmf\Module\Admin::getInstance();

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

//Handlers
$infoHandler     = new Info\MyInfoHandler($xoopsDB, $moduleDirName);
$infowaitHandler = new Info\MyInfoHandler($xoopsDB, $moduleDirName . '_bak');
$catHandler      = new Info\CategoryHandler($xoopsDB, $moduleDirName);
$infoTree        = new Info\InfoTree($xoopsDB->prefix($moduleDirName), 'info_id', 'parent_id');

$myts = \MyTextSanitizer::getInstance();
