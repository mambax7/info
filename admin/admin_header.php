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

require_once __DIR__ . '/../../../include/cp_header.php';

//global $xoopsModule;
//$moduleHandler = xoops_getHandler('module');
//$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
//$module_name   = $xoopsModule->getVar('dirname');
require_once __DIR__ . '/../include/function.php';

$moduleDirName = basename(dirname(__DIR__));
$helper = \Xmf\Module\Helper::getHelper($moduleDirName);
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once __DIR__ . '/../class/infotree.php';
require_once __DIR__ . '/../class/info.php';
require_once __DIR__ . '/../class/category.php';

//Handlers
$infoHandler     = new InfoInfoHandler($xoopsDB, $moduleDirName);
$infowaitHandler = new InfoInfoHandler($xoopsDB, $moduleDirName . '_bak');
$catHandler      = new InfoCategoryHandler($xoopsDB, $moduleDirName);
$infoTree        = new InfoTree($xoopsDB->prefix($moduleDirName), 'info_id', 'parent_id');

$myts = \MyTextSanitizer::getInstance();
